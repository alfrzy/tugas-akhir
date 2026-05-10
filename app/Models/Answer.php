<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    // Tambahkan baris ini
    protected $fillable = [
        'user_id',
        'question_id',
        'answer_text',
        'score',
        'calculation_log',
    ];

    protected $casts = [
        'calculation_log' => 'array',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Question
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}