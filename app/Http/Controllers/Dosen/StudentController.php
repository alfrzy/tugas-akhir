<?php

namespace App\Http\Middleware; // <-- Pastikan namespace ini benar, tadi kamu tulis Controller kan?
namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;    // Import Model User
use App\Models\Subject; // Import Model Subject

class StudentController extends Controller
{
    public function index()
    {
        // 1. Cari ID Mata Kuliah yang diampu dosen yang sedang login
        $subjectIds = Subject::where('user_id', auth()->id())->pluck('id');

        // 2. Ambil data mahasiswa
        $students = User::where('role', 'mahasiswa')
            ->whereHas('subjects', function($query) use ($subjectIds) {
                $query->whereIn('subjects.id', $subjectIds);
            })
            ->with(['subjects' => function($q) use ($subjectIds) {
                // Hanya memuat subjek yang diajar oleh dosen ini saja
                $q->whereIn('subjects.id', $subjectIds);
            }])
            ->get();

        return view('dosen.students.index', compact('students'));
    }
}