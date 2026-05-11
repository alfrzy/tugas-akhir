<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function show(Exam $exam)
    {
        $user = auth()->user();

        $answers = \App\Models\Answer::where('user_id', $user->id)
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