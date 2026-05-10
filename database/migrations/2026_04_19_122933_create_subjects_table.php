<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('subjects', function (Blueprint $table) {
        $table->id();
        // Relasi ke tabel users (Dosen yang login)
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('subject_name'); // Contoh: Algoritma & Pemrograman
        $table->string('subject_code')->unique(); // Contoh: IF101
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
