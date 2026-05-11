<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Submission;
use Illuminate\Http\Request;
use App\Services\NlpScoringService;
use Illuminate\Support\Facades\DB;

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
        // 1. Cek Pendaftaran
        $isEnrolled = auth()->user()->enrolledSubjects()->where('subjects.id', $exam->subject_id)->exists();
        if (!$isEnrolled) {
            return redirect()->route('mahasiswa.subjects.index')->with('error', 'Anda tidak terdaftar.');
        }

        // 2. Buat atau Ambil Data Submission (Pengganti Session)
        $submission = Submission::firstOrCreate(
            ['user_id' => auth()->id(), 'exam_id' => $exam->id],
            ['started_at' => now(), 'is_finalized' => false]
        );

        // 3. Cek apakah ujian sudah pernah disubmit
        if ($submission->is_finalized) {
            return redirect()->route('mahasiswa.subjects.exams', $exam->subject_id)
                ->with('info', 'Ujian sudah dikumpulkan dan tidak dapat diulang.');
        }

        // 4. Kalkulasi Sisa Waktu
        $deadline = $submission->started_at->copy()->addMinutes($exam->duration);
        if ($exam->end_time && $exam->end_time->lt($deadline)) {
            $deadline = $exam->end_time;
        }

        $remainingSeconds = now()->diffInSeconds($deadline, false);

        // 5. Tampilkan View
        $exam->load('questions');
        return view('mahasiswa.exams.take', compact('exam', 'remainingSeconds'));
    }

    // Submit & Hitung Skor
    public function store(Request $request, Exam $exam, NlpScoringService $nlpService)
    {
        // ==========================================
        // TAHAP 1: VALIDASI STATUS SUBMISSION
        // ==========================================
        $submission = Submission::where('user_id', auth()->id())
            ->where('exam_id', $exam->id)
            ->first();

        if (!$submission) {
            return redirect()->route('mahasiswa.subjects.exams', $exam->subject_id)
                ->with('error', 'Anda belum memulai ujian ini.');
        }

        if ($submission->is_finalized) {
            return redirect()->route('mahasiswa.subjects.exams', $exam->subject_id)
                ->with('info', 'Ujian sudah dikumpulkan.');
        }

        // Hitung deadline untuk mendeteksi auto-submit
        $deadline = $submission->started_at->copy()->addMinutes($exam->duration);
        if ($exam->end_time && $exam->end_time->lt($deadline)) {
            $deadline = $exam->end_time;
        }

        // Toleransi 10 detik keterlambatan jaringan
        $autoSubmitted = now()->gt($deadline->copy()->addSeconds(10));

        // ==========================================
        // TAHAP 2: PROSES PENILAIAN (SCORING)
        // ==========================================
        $answers = $request->input('answers', []);
        $sumScores = 0;

        DB::transaction(function () use ($exam, $answers, &$sumScores, $nlpService) {
            foreach ($exam->questions as $question) {
                $raw = $answers[$question->id] ?? '';
                $studentText = trim((string) $raw);

                if ($studentText === '') {
                    $finalScore = 0;
                    $log = null;
                } else {
                    $result = $nlpService->calculateCosineSimilarity($question->key_answer, $studentText);
                    $finalScore = min(round($result['score'] * 100), 100);
                    $log = $result['log'];
                }

                $sumScores += $finalScore;

                Answer::updateOrCreate(
                    ['user_id' => auth()->id(), 'question_id' => $question->id],
                    ['answer_text' => $studentText, 'score' => $finalScore, 'calculation_log' => $log]
                );
            }
        });

        $totalQuestions = $exam->questions->count();
        $averageScore = $totalQuestions > 0 ? ($sumScores / $totalQuestions) : 0;

        // Logika Denda Keterlambatan (Jika melewati end_time dari Dosen)
        $sekarang = now();
        if ($exam->end_time && $sekarang > $exam->end_time) {
            $jamTerlambat = min(ceil($exam->end_time->diffInMinutes($sekarang) / 60), 5);
            $persenDenda = $jamTerlambat * 10; 
            $averageScore = $averageScore - (($averageScore * $persenDenda) / 100);
        }

        // ==========================================
        // TAHAP 3: UPDATE EXISTING SUBMISSION
        // ==========================================
        $submission->update([
            'total_score'    => max(0, $averageScore), // Memastikan nilai tidak minus
            'finished_at'    => now(),
            'is_finalized'   => true,
            'auto_submitted' => $autoSubmitted,
            'is_published'   => false,
        ]);

        // ==========================================
        // TAHAP 4: REDIRECT DENGAN FLASH MESSAGE
        // ==========================================
        $flashType = $autoSubmitted ? 'warning' : 'success';
        $flashMsg  = $autoSubmitted
            ? 'Waktu ujian telah habis. Jawaban Anda telah tersimpan otomatis.'
            : 'Ujian selesai! Jawaban Anda telah dikirim.';

        return redirect()->route('mahasiswa.subjects.exams', $exam->subject_id)
            ->with($flashType, $flashMsg);
    }
}