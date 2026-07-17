<?php

namespace App\Imports;

use App\Models\Catatan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CatatanImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new Catatan([
            'user_id'    => auth()->id(),
            'nama'       => $row['nama'] ?? 'Pajri',
            'hari_ke'    => $row['hari_ke'],
            'tanggal'    => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal']),
            'pendapatan' => $row['pendapatan'],
            'status'     => in_array($row['status'] ?? null, ['sudah_bayar', 'belum_bayar'])
                                ? $row['status']
                                : 'belum_bayar',
        ]);
    }

    public function rules(): array
    {
        return [
            'hari_ke'    => 'required|integer|min:1',
            'tanggal'    => 'required',
            'pendapatan' => 'required|integer|min:0',
        ];
    }
}