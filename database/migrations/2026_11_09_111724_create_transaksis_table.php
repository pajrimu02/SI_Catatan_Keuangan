<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('transaksis', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->date('tanggal');
        $table->enum('jenis', ['masuk', 'keluar']);
        $table->string('kategori', 50);
        $table->string('keterangan')->nullable();
        $table->unsignedBigInteger('jumlah');
        $table->timestamps();

        $table->index(['user_id', 'tanggal']);
    });
}

public function down(): void
{
    Schema::dropIfExists('transaksis');
}
};
