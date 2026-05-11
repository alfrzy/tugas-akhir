<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResultController extends Controller
{
    public function index()
    {
        // 1. Ambil Mata Kuliah milik Dosen beserta jumlah mahasiswa yang terdaftar
        $subjects = \App\Models\Subject::where('user_id', auth()->id())
            ->withCount('students') // Menghitung total mahasiswa di kelas ini (students_count)
            ->with(['exams' => function($query) {
                // 2. Ambil ujian di dalam matkul tersebut, dan hitung yang sudah submit
                $query->withCount('submissions')->latest();
            }])
            ->get();

        return view('dosen.results.index', compact('subjects'));
    }

    public function show(Exam $exam)
    {
        // Pastikan dosen hanya bisa melihat detail ujian miliknya sendiri
        if ($exam->subject->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke hasil ujian ini.');
        }

        // Ambil daftar mahasiswa yang sudah mengerjakan ujian ini
        $students = \App\Models\User::whereHas('answers', function ($query) use ($exam) {
            $query->whereHas('question', function ($q) use ($exam) {
                $q->where('exam_id', $exam->id);
            });
        })
        ->with(['answers' => function ($query) use ($exam) {
            $query->whereHas('question', function ($q) use ($exam) {
                $q->where('exam_id', $exam->id);
            });
        }])
        ->get();

        return view('dosen.results.show', compact('exam', 'students'));
    }

    public function showStudentAnswers($examId, $studentId)
    {
        // 1. Ambil data ujian dan mahasiswa
        $exam = \App\Models\Exam::with('questions')->findOrFail($examId);
        $student = \App\Models\User::findOrFail($studentId);

        // 2. Ambil data nilai akhir (Submission) dari tabel baru kita
        $submission = \App\Models\Submission::where('user_id', $studentId)
            ->where('exam_id', $examId)
            ->first();

        // 3. Ambil rincian jawaban per soal
        $answers = \App\Models\Answer::where('user_id', $studentId)
            ->whereHas('question', function ($query) use ($examId) {
                $query->where('exam_id', $examId);
            })
            ->with('question')
            ->get()
            ->keyBy('question_id'); // Mempermudah pemanggilan berdasarkan ID soal

        // Kita kirim $submission ke view bersama data lainnya
        return view('dosen.results.check', compact('exam', 'student', 'answers', 'submission'));
    }

    public function publishScore($examId, $studentId)
    {
        // 1. Cari data submission milik mahasiswa untuk ujian ini
        $submission = \App\Models\Submission::where('user_id', $studentId)
            ->where('exam_id', $examId)
            ->first();

        // 2. Jika datanya ada, ubah status is_published menjadi true
        if ($submission) {
            $submission->update([
                'is_published' => true
            ]);

            return back()->with('success', 'Seluruh nilai ujian berhasil dipublikasikan!');
        }

        // 3. Jika terjadi error (data tidak ditemukan)
        return back()->with('error', 'Gagal mempublikasikan nilai. Data ujian tidak ditemukan.');
    }
}