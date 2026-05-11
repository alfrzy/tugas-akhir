<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Exam extends Model
{
    protected $fillable = ['subject_id', 'title', 'duration', 'start_time', 'end_time'];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function getStatusAttribute()
    {
        $sekarang = now(); // Waktu saat ini

        if ($this->start_time && $sekarang < $this->start_time) {
            return 'Belum Mulai';
        } elseif ($this->end_time && $sekarang > $this->end_time) {
            return 'Ditutup';
        } else {
            return 'Aktif';
        }
    }
    
    public function subject() {
    return $this->belongsTo(Subject::class);
}

public function questions()
{
    return $this->hasMany(Question::class);
}

public function answers(): HasManyThrough
    {
        return $this->hasManyThrough(
            Answer::class,   // Model tujuan (Hasil yang ingin diambil)
            Question::class, // Model perantara
            'exam_id',       // Foreign key di tabel questions
            'question_id',   // Foreign key di tabel answers
            'id',            // Local key di tabel exams
            'id'             // Local key di tabel questions
        );
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
