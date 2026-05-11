<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\Services\NlpScoringService; // Import Service NLP kita!

class ExamController extends Controller
{
    // Daftar Ujian per Matkul
    public function index(Subject $subject)
    {
        if (!$subject->students->contains(auth()->id())) abort(403);

        $exams = Exam::where('subject_id', $subject->id)->withCount('questions')->get();
        return view('mahasiswa.exams.index', compact('subject', 'exams'));
    }

    // Tampilkan Soal (Mulai Ujian)
    public function show(Exam $exam)
    {
        $isEnrolled = auth()->user()->enrolledSubjects()->where('subjects.id', $exam->subject_id)->exists();
        if (!$isEnrolled) return redirect()->route('mahasiswa.subjects.index')->with('error', 'Anda tidak terdaftar.');

        $sessionKey = 'exam_start_time_' . auth()->id() . '_' . $exam->id;

        if (!session()->has($sessionKey)) {
            session([$sessionKey => now()->toDateTimeString()]);
        }

        $startTime = \Illuminate\Support\Carbon::parse(session($sessionKey));
        $endTime = $startTime->copy()->addMinutes($exam->duration);
        $remainingSeconds = now()->diffInSeconds($endTime, false);

        if ($remainingSeconds <= 0) {
            session()->forget($sessionKey);
            return redirect()->route('mahasiswa.subjects.exams', $exam->subject_id)->with('error', 'Waktu ujian telah habis!');
        }

        $exam->load('questions');
        return view('mahasiswa.exams.take', compact('exam', 'remainingSeconds'));
    }

    // Submit & Hitung Skor
    public function store(Request $request, Exam $exam, NlpScoringService $nlpService)
    {
        $answers = $request->input('answers') ?? []; 
        $sumScores = 0;

        foreach ($answers as $questionId => $studentText) {
            $question = \App\Models\Question::find($questionId);
            if (!$question) continue; 

            // PANGGIL SERVICE NLP DI SINI! (Jauh lebih rapi)
            $calculationResult = $nlpService->calculateCosineSimilarity($question->key_answer, $studentText);
            
            $rawScore = $calculationResult['score'];
            $finalScore = min(round($rawScore * 100), 100);
            $sumScores += $finalScore;

            \App\Models\Answer::updateOrCreate(
                ['user_id' => auth()->id(), 'question_id' => $questionId],
                ['answer_text' => $studentText, 'score' => $finalScore, 'calculation_log' => $calculationResult['log']]
            );
        }

        $totalQuestions = $exam->questions()->count();
        $averageScore = $totalQuestions > 0 ? ($sumScores / $totalQuestions) : 0;

        // Logika Denda Keterlambatan
        $sekarang = now();
        if ($exam->end_time && $sekarang > $exam->end_time) {
            $jamTerlambat = min(ceil($exam->end_time->diffInMinutes($sekarang) / 60), 5);
            $persenDenda = $jamTerlambat * 10; 
            $averageScore = $averageScore - (($averageScore * $persenDenda) / 100);
        }

        $sessionKey = 'exam_start_time_' . auth()->id() . '_' . $exam->id;
        $startedAt = session()->has($sessionKey) ? \Illuminate\Support\Carbon::parse(session($sessionKey)) : null;

        \App\Models\Submission::updateOrCreate(
            ['user_id' => auth()->id(), 'exam_id' => $exam->id],
            ['total_score' => $averageScore, 'is_published' => false, 'started_at' => $startedAt, 'finished_at' => now()]
        );

        session()->forget($sessionKey);

        return redirect()->route('mahasiswa.subjects.exams', $exam->subject_id)
                         ->with('success', 'Ujian selesai! Skor Anda telah dihitung.');
    }
}