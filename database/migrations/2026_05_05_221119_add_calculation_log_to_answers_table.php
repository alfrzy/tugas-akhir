<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration (Menambah kolom ke database).
     */
    public function up(): void
    {
        Schema::table('answers', function (Blueprint $table) {
            // Kita tambahkan kolom berjenis json.
            // 'nullable' memastikan jawaban ujian lama yang belum punya log tidak menjadi error.
            $table->json('calculation_log')->nullable()->after('score');
        });
    }

    /**
     * Kembalikan migration (Menghapus kolom jika migration di-rollback).
     */
    public function down(): void
    {
        Schema::table('answers', function (Blueprint $table) {
            $table->dropColumn('calculation_log');
        });
    }
};