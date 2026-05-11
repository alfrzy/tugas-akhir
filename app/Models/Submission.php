<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    // Kolom yang diizinkan untuk diisi secara otomatis
    protected $fillable = [
        'user_id',
        'exam_id',
        'total_score',
        'is_published',
        'started_at',
        'finished_at',
        'is_finalized',
        'auto_submitted',

    ];

    // Mengatur format tanggal
    protected $casts = [
        'is_published' => 'boolean',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'is_finalized' => 'boolean',
        'auto_submitted' => 'boolean',
    ];

    // Relasi ke User (Mahasiswa)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Exam (Ujian)
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
