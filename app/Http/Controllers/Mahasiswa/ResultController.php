<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Exam;
use App\Models\Submission;

class ResultController extends Controller
{
    public function index()
    {
        // Ambil semua submission milik mahasiswa yang login, urutkan dari yang terbaru
        // Kita gunakan eager loading (with) untuk mengambil data ujian dan mata kuliah sekaligus
        $submissions = Submission::where('user_id', auth()->id())
            ->with(['exam.subject'])
            ->latest('finished_at')
            ->get();

        // Hitung rata-rata keseluruhan (opsional, untuk dipajang di dashboard nilai)
        // Hanya menghitung nilai yang sudah di-publish oleh dosen
        $averageOverall = $submissions->where('is_published', true)->avg('total_score');

        return view('mahasiswa.results.index', compact('submissions', 'averageOverall'));
    }

    public function show(Exam $exam)
    {
        $user = auth()->user();

        $answers = Answer::where('user_id', $user->id)
            ->whereHas('question', function ($query) use ($exam) {
                $query->where('exam_id', $exam->id);
            })
            ->with('question')
            ->get();

        if ($answers->isEmpty()) {
            return redirect()->route('mahasiswa.subjects.index')->with('error', 'Hasil tidak ditemukan.');
        }

        $totalScore = $answers->avg('score');

        return view('mahasiswa.exams.result', compact('exam', 'answers', 'totalScore'));
    }
}
