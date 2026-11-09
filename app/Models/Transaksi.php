<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = ['user_id', 'tanggal', 'jenis', 'kategori', 'keterangan', 'jumlah'];

    protected $casts = ['tanggal' => 'date'];

    // Kategori bisa kamu ubah sesuka hati
    public const KATEGORI = [
        'keluar' => ['Makan & Minum', 'Belanja', 'Transport', 'Tagihan', 'Hiburan', 'Kesehatan', 'Lainnya'],
        'masuk'  => ['Gaji', 'Bonus', 'Transfer', 'Lainnya'],
    ];
}