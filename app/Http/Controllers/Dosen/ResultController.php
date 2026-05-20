<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function index()
    {
        // 1. Ambil Mata Kuliah milik Dosen beserta jumlah mahasiswa yang terdaftar
        $subjects = Subject::where('user_id', auth()->id())
            ->withCount('students') // Menghitung total mahasiswa di kelas ini (students_count)
            ->with(['exams' => function ($query) {
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
        $students = User::whereHas('answers', function ($query) use ($exam) {
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
        $exam = Exam::with('questions')->findOrFail($examId);
        $student = User::findOrFail($studentId);

        // 2. Ambil data nilai akhir (Submission) dari tabel baru kita
        $submission = Submission::where('user_id', $studentId)
            ->where('exam_id', $examId)
            ->first();

        // 3. Ambil rincian jawaban per soal
        $answers = Answer::where('user_id', $studentId)
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
        $submission = Submission::where('user_id', $studentId)
            ->where('exam_id', $examId)
            ->first();

        // 2. Jika datanya ada, ubah status is_published menjadi true
        if ($submission) {
            $submission->update([
                'is_published' => true,
            ]);

            return back()->with('success', 'Seluruh nilai ujian berhasil dipublikasikan!');
        }

        // 3. Jika terjadi error (data tidak ditemukan)
        return back()->with('error', 'Gagal mempublikasikan nilai. Data ujian tidak ditemukan.');
    }

    public function requestAiReview(Request $request, $answerId, \App\Services\AiReviewService $aiService)
    {
        $answer = Answer::with('question.exam.subject')->findOrFail($answerId);

        // Pastikan dosen yang mengakses berhak
        if ($answer->question->exam->subject->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $result = $aiService->reviewAnswer($answer->question->key_answer, $answer->answer_text);
            
            $answer->update([
                'ai_score' => $result['score'],
                'ai_feedback' => $result['feedback']
            ]);

            return back()->with('success', 'AI Review berhasil didapatkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memanggil AI: ' . $e->getMessage());
        }
    }

    public function requestAiReviewAll(Request $request, $examId, $studentId, \App\Services\AiReviewService $aiService)
    {
        $exam = Exam::with('subject')->findOrFail($examId);

        // Pastikan dosen yang mengakses berhak
        if ($exam->subject->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $answers = Answer::where('user_id', $studentId)
            ->whereHas('question', function ($query) use ($examId) {
                $query->where('exam_id', $examId);
            })
            ->with('question')
            ->get();

        if ($answers->isEmpty()) {
            return back()->with('error', 'Tidak ada jawaban untuk di-review.');
        }

        try {
            foreach ($answers as $answer) {
                // Hanya review jika ada jawaban
                if (!empty($answer->answer_text)) {
                    $result = $aiService->reviewAnswer($answer->question->key_answer, $answer->answer_text);
                    
                    $answer->update([
                        'ai_score' => $result['score'],
                        'ai_feedback' => $result['feedback']
                    ]);
                }
            }

            return back()->with('success', 'Seluruh jawaban AI Review berhasil didapatkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memanggil AI: ' . $e->getMessage());
        }
    }

    public function updateScore(Request $request, $answerId)
    {
        $request->validate([
            'score' => 'required|numeric|min:0|max:100',
        ]);

        $answer = Answer::with(['question.exam.submissions' => function($q) use ($request, &$answer) {
            // we will find the specific submission later
        }])->findOrFail($answerId);

        if ($answer->question->exam->subject->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $newScore = $request->input('score');
        $answer->update(['score' => $newScore]);

        // Kalkulasi ulang total score di submission
        $examId = $answer->question->exam_id;
        $userId = $answer->user_id;

        $totalExamQuestions = $answer->question->exam->questions()->count();
        $allAnswers = Answer::where('user_id', $userId)
            ->whereHas('question', function($q) use ($examId) {
                $q->where('exam_id', $examId);
            })->sum('score');

        $averageScore = $totalExamQuestions > 0 ? ($allAnswers / $totalExamQuestions) : 0;

        // Denda keterlambatan (replicate logic from ExamController)
        $exam = $answer->question->exam;
        $submission = Submission::where('user_id', $userId)->where('exam_id', $examId)->first();
        
        if ($submission) {
            if ($exam->end_time && $submission->finished_at > $exam->end_time) {
                $jamTerlambat = min(ceil($exam->end_time->diffInMinutes($submission->finished_at) / 60), 5);
                $persenDenda = $jamTerlambat * 10;
                $averageScore = $averageScore - (($averageScore * $persenDenda) / 100);
            }
            
            $submission->update(['total_score' => max(0, $averageScore)]);
        }

        return back()->with('success', 'Skor berhasil diperbarui. Total skor juga telah dikalkulasi ulang.');
    }

    public function recap()
    {
        // 1. Ambil semua mata kuliah yang diampu dosen ini
        $subjects = Subject::where('user_id', auth()->id())
            ->with(['exams.submissions.user', 'students'])
            ->get();

        return view('dosen.results.recap', compact('subjects'));
    }
}
