<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['user_id', 'subject_name', 'subject_code'];

    // TAMBAHKAN RELASI INI:
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function exams() {
        return $this->hasMany(Exam::class, 'subject_id');
    }

    // Relasi ke Mahasiswa (Many-to-Many)
    public function students()
    {
        return $this->belongsToMany(User::class)->where('role', 'mahasiswa');
    }

    // Relasi ke semua users (dosen & mahasiswa)
    public function users()
    {
        return $this->belongsToMany(User::class, 'subject_user');
    }
}
