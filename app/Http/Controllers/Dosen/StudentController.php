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
        // 1. Ambil data mata kuliah milik dosen yang sedang login beserta mahasiswanya
        $subjects = Subject::where('user_id', auth()->id())
            ->with('students')
            ->get();

        return view('dosen.students.index', compact('subjects'));
    }

    public function removeStudent(Subject $subject, User $student)
    {
        // Pastikan dosen hanya bisa menghapus mahasiswa dari kelasnya sendiri
        if ($subject->user_id !== auth()->id()) {
            abort(403, 'Anda tidak berhak menghapus mahasiswa dari kelas ini.');
        }

        // Hapus relasi mahasiswa dari kelas
        $subject->students()->detach($student->id);

        return back()->with('success', 'Mahasiswa berhasil dikeluarkan dari kelas ' . $subject->subject_name . '.');
    }

    public function showProgress(Subject $subject, User $student)
    {
        // Pastikan dosen mengakses kelas miliknya
        if ($subject->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Ambil ujian pada mata kuliah ini
        $exams = $subject->exams()->get();

        // Ambil submission (nilai) mahasiswa pada ujian-ujian tersebut
        $submissions = \App\Models\Submission::where('user_id', $student->id)
            ->whereIn('exam_id', $exams->pluck('id'))
            ->get()
            ->keyBy('exam_id');

        return view('dosen.students.progress', compact('subject', 'student', 'exams', 'submissions'));
    }
}