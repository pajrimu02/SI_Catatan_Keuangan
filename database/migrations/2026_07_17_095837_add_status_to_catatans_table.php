<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('catatans', function (Blueprint $table) {
            $table->enum('status', ['sudah_bayar', 'belum_bayar'])
                  ->default('belum_bayar')
                  ->after('pendapatan');
        });
    }

    public function down(): void
    {
        Schema::table('catatans', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};