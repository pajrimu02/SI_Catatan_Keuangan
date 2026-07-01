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
        return ['No', 'Nama', 'Hari Ke', 'Tanggal', 'Pendapatan'];
    }

    public function map($catatan): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $catatan->nama,
            $catatan->hari_ke,
            $catatan->tanggal->format('d-m-Y'),
            $catatan->pendapatan,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 20,
            'C' => 12,
            'D' => 16,
            'E' => 20,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Header (baris 1)
        $sheet->getStyle('A1:E1')->applyFromArray([
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

                $lastDataRow = $this->catatans->count() + 1; // +1 karena baris 1 = heading
                $totalRow    = $lastDataRow + 1;

                // Border seluruh tabel data
                $sheet->getStyle("A1:E{$lastDataRow}")->applyFromArray([
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

                // Kolom No & Hari Ke rata tengah
                $sheet->getStyle("A2:A{$lastDataRow}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("C2:C{$lastDataRow}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("D2:D{$lastDataRow}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Baris Total
                $sheet->setCellValue("D{$totalRow}", 'TOTAL PENDAPATAN');
                $sheet->setCellValue("E{$totalRow}", $this->totalPendapatan);

                $sheet->mergeCells("D{$totalRow}:D{$totalRow}");

                $sheet->getStyle("D{$totalRow}:E{$totalRow}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '198754'], // hijau
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'CCCCCC'],
                        ],
                    ],
                ]);

                $sheet->getStyle("E{$totalRow}")
                    ->getNumberFormat()->setFormatCode('"Rp" #,##0');
                $sheet->getStyle("E{$totalRow}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                // Zebra stripe (baris genap sedikit abu-abu)
                for ($row = 2; $row <= $lastDataRow; $row++) {
                    if ($row % 2 === 0) {
                        $sheet->getStyle("A{$row}:E{$row}")->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'F3F4F6'],
                            ],
                        ]);
                    }
                }
            },
        ];
    }
}