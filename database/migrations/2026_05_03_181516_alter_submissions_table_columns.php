<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            // 1. Menghapus kolom lama yang sudah tidak terpakai
            $table->dropColumn(['student_id','student_answer', 'auto_score', 'final_score']);

            // 2. Menambahkan kolom baru yang direkomendasikan
            // Diletakkan setelah exam_id agar susunannya rapi di database 
            $table->decimal('total_score', 5, 2)->nullable()->after('exam_id');
            $table->boolean('is_published')->default(false)->after('total_score');
            $table->timestamp('started_at')->nullable()->after('is_published');
            $table->timestamp('finished_at')->nullable()->after('started_at');
        });
    }

    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            // Membatalkan penambahan kolom baru
            $table->dropColumn(['total_score', 'is_published', 'started_at', 'finished_at']);

            // Mengembalikan kolom lama jika perintah rollback dijalankan
            $table->unsignedBigInteger('student_id')->nullable()->after('exam_id');
            $table->text('student_answer')->nullable();
            $table->float('auto_score')->default(0);
            $table->float('final_score')->nullable();
        });
    }
};