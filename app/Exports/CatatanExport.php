<?php

namespace App\Exports;

use App\Models\Catatan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CatatanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithEvents
{
    protected $catatans;
    protected $totalPendapatan;

    public function collection()
    {
        $this->catatans = Catatan::where('user_id', auth()->id())
            ->orderBy('tanggal', 'desc')
            ->get();

        $this->totalPendapatan = $this->catatans->sum('pendapatan');

        return $this->catatans;
    }

    public function headings(): array
    {
        return ['No', 'Nama', 'Hari', 'Tanggal', 'Pendapatan', 'Status'];
    }

    public function map($catatan): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $catatan->nama,
            $catatan->hari,
            $catatan->tanggal->format('d-m-Y'),
            $catatan->pendapatan,
            $catatan->status === 'sudah_bayar' ? 'Sudah Bayar' : 'Belum Bayar',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 20,
            'C' => 12,
            'D' => 16,
            'E' => 18,
            'F' => 16,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '111827'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->getRowDimension(1)->setRowHeight(22);

        return [];
    }

    public function registerEvents(): array
{
    return [
        AfterSheet::class => function (AfterSheet $event) {
            $sheet = $event->sheet->getDelegate();

            $lastDataRow = $this->catatans->count() + 1;
            $totalRow    = $lastDataRow + 1;

            // Border seluruh tabel data
            $sheet->getStyle("A1:F{$lastDataRow}")->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC'],
                    ],
                ],
            ]);

            // Kolom Pendapatan (E) rata kanan + format ribuan
            $sheet->getStyle("E2:E{$lastDataRow}")
                ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("E2:E{$lastDataRow}")
                ->getNumberFormat()->setFormatCode('#,##0');

            // Kolom No, Hari Ke, Tanggal, Status rata tengah
            foreach (['A', 'C', 'D', 'F'] as $col) {
                $sheet->getStyle("{$col}2:{$col}{$lastDataRow}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }
 
            foreach ($this->catatans as $i => $catatan) {
                $row = $i + 2;

                if ($catatan->status === 'sudah_bayar') {
                    $sheet->getStyle("F{$row}")->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'color' => ['rgb' => '198754'],
                        ],
                    ]);
                } else {
                    $sheet->getStyle("F{$row}")->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'color' => ['rgb' => 'DC3545'],
                        ],
                    ]);
                }
            }

            // Zebra stripe
            for ($row = 2; $row <= $lastDataRow; $row++) {
                if ($row % 2 === 0) {
                    $sheet->getStyle("A{$row}:F{$row}")->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'F3F4F6'],
                        ],
                    ]);
                }
            }

            // Baris Total
            $sheet->setCellValue("D{$totalRow}", 'TOTAL PENDAPATAN');
            $sheet->setCellValue("E{$totalRow}", $this->totalPendapatan);

            $sheet->getStyle("D{$totalRow}:F{$totalRow}")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '198754']],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC'],
                    ],
                ],
            ]);

            $sheet->getStyle("E{$totalRow}")->getNumberFormat()->setFormatCode('"Rp" #,##0');
            $sheet->getStyle("E{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            // Ringkasan Sudah Bayar & Belum Bayar
            $totalSudahBayar = $this->catatans->where('status', 'sudah_bayar')->sum('pendapatan');
            $totalBelumBayar = $this->catatans->where('status', 'belum_bayar')->sum('pendapatan');

            $rowSudah = $totalRow + 1;
            $rowBelum = $totalRow + 2;

            $sheet->setCellValue("D{$rowSudah}", 'SUDAH BAYAR');
            $sheet->setCellValue("E{$rowSudah}", $totalSudahBayar);

            $sheet->setCellValue("D{$rowBelum}", 'BELUM BAYAR');
            $sheet->setCellValue("E{$rowBelum}", $totalBelumBayar);

            $sheet->getStyle("D{$rowSudah}:E{$rowSudah}")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '198754']],
            ]);

            $sheet->getStyle("D{$rowBelum}:E{$rowBelum}")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DC3545']],
            ]);

            $sheet->getStyle("E{$rowSudah}")->getNumberFormat()->setFormatCode('"Rp" #,##0');
            $sheet->getStyle("E{$rowBelum}")->getNumberFormat()->setFormatCode('"Rp" #,##0');
        },
    ];
}
}