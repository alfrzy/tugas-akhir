<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Exam extends Model
{
    protected $fillable = ['subject_id', 'title', 'duration'];
    
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
