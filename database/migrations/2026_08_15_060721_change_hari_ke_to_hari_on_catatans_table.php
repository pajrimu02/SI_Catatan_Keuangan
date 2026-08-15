<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('catatans', function (Blueprint $table) {
            $table->string('hari')->nullable()->after('nama');
        });

        Schema::table('catatans', function (Blueprint $table) {
            $table->dropColumn('hari_ke');
        });
    }

    public function down(): void
    {
        Schema::table('catatans', function (Blueprint $table) {
            $table->integer('hari_ke')->nullable()->after('nama');
            $table->dropColumn('hari');
        });
    }
};