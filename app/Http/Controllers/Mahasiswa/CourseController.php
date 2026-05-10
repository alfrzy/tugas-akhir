<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Sastrawi\Stemmer\StemmerFactory;
use Sastrawi\StopWordRemover\StopWordRemoverFactory;

class CourseController extends Controller
{
    public function index()
{
    // Ambil mata kuliah yang sudah di-join oleh mahasiswa yang sedang login
    $mySubjects = auth()->user()->enrolledSubjects()->with('user')->get();

    return view('mahasiswa.subjects.index', compact('mySubjects'));
}

public function available(Request $request)
{
    // Logika pencarian
    $query = Subject::whereNotNull('user_id')
        ->whereDoesntHave('students', function($q) {
            $q->where('users.id', auth()->id());
        });

    if ($request->has('search')) {
        $query->where('subject_name', 'like', '%' . $request->search . '%')
              ->orWhere('subject_code', 'like', '%' . $request->search . '%');
    }

    $subjects = $query->with('user')->get();

    return view('mahasiswa.subjects.available', compact('subjects'));
}

    public function join(Subject $subject)
    {
        // Hubungkan mahasiswa ke mata kuliah (simpan ke tabel pivot subject_user)
        auth()->user()->enrolledSubjects()->attach($subject->id);

        return redirect()->route('dashboard')->with('success', 'Berhasil bergabung ke kelas ' . $subject->subject_name);
    }

    public function exams(\App\Models\Subject $subject)
    {
        // Memastikan mata kuliah ini memang diikuti oleh mahasiswa tersebut
        if (!$subject->students->contains(auth()->id())) abort(403);

        // Ambil ujian beserta jumlah soalnya
        $exams = \App\Models\Exam::where('subject_id', $subject->id)
                    ->withCount('questions')
                    ->get();

        return view('mahasiswa.exams.index', compact('subject', 'exams'));
    }

    public function takeExam(\App\Models\Exam $exam)
{
    // 1. Validasi pendaftaran
    $isEnrolled = auth()->user()->enrolledSubjects()->where('subjects.id', $exam->subject_id)->exists();
    if (!$isEnrolled) {
        return redirect()->route('mahasiswa.subjects.index')->with('error', 'Anda tidak terdaftar.');
    }

    // 2. Tentukan Key Session yang unik
    $sessionKey = 'exam_start_time_' . auth()->id() . '_' . $exam->id;

    // 3. Cek atau Set waktu mulai di session
    if (!session()->has($sessionKey)) {
        // Simpan dalam format string ISO agar aman di session
        session([$sessionKey => now()->toDateTimeString()]);
    }

    // 4. MENGUBAH STRING MENJADI OBJEK CARBON (Penting!)
    // Kita gunakan \Illuminate\Support\Carbon::parse() supaya fungsi copy() dan addMinutes() bisa jalan
    $startTime = \Illuminate\Support\Carbon::parse(session($sessionKey));
    
    $endTime = $startTime->copy()->addMinutes($exam->duration);
    $remainingSeconds = now()->diffInSeconds($endTime, false);

    // 5. Proteksi jika waktu habis
    if ($remainingSeconds <= 0) {
        session()->forget($sessionKey);
        return redirect()->route('mahasiswa.subjects.exams', $exam->subject_id)
                         ->with('error', 'Waktu ujian telah habis!');
    }

    $exam->load('questions');
    return view('mahasiswa.exams.take', compact('exam', 'remainingSeconds'));
}
    public function submitExam(Request $request, \App\Models\Exam $exam)
    {
        $answers = $request->input('answers') ?? []; 
        $sumScores = 0; // Variabel penampung total nilai

        foreach ($answers as $questionId => $studentText) {
            $question = \App\Models\Question::find($questionId);
            
            // Hindari error jika pertanyaan tidak ditemukan
            if (!$question) continue; 

            $keyText = $question->key_answer;

            // 1. Dapatkan Skor Murni dan Log Perhitungan dari fungsi
            $calculationResult = $this->calculateCosineSimilarity($keyText, $studentText);
            
            // Ekstrak data dari bungkusan hasil
            $rawScore = $calculationResult['score'];
            $logData = $calculationResult['log'];

            // 2. Hitung Skor Akhir (Tanpa pendongkrak, murni matematika)
            $finalScore = min(round($rawScore * 100), 100);

            // Tambahkan ke total nilai
            $sumScores += $finalScore;

            // 3. Simpan Jawaban beserta Log-nya
            \App\Models\Answer::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'question_id' => $questionId,
                ],
                [
                    'answer_text' => $studentText,
                    'score' => $finalScore, 
                    'calculation_log' => $logData, // <-- Simpan rekam jejak sistem di sini
                ]
            );
        }

        // 4. Hitung Nilai Rata-rata (Total Score)
        $totalQuestions = $exam->questions()->count();
        $averageScore = $totalQuestions > 0 ? ($sumScores / $totalQuestions) : 0;

        // 5. Ambil waktu mulai dari session
        $sessionKey = 'exam_start_time_' . auth()->id() . '_' . $exam->id;
        $startedAt = session()->has($sessionKey) ? \Illuminate\Support\Carbon::parse(session($sessionKey)) : null;

        // 6. Simpan Rekapitulasi ke Tabel Submissions
        \App\Models\Submission::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'exam_id' => $exam->id,
            ],
            [
                'total_score' => $averageScore,
                'is_published' => false,
                'started_at' => $startedAt,
                'finished_at' => now(), 
            ]
        );

        // 7. Bersihkan Session agar ujian tidak bisa diulang
        session()->forget($sessionKey);

        return redirect()->route('mahasiswa.subjects.exams', $exam->subject_id)
                         ->with('success', 'Ujian selesai! Skor Anda telah dihitung secara otomatis.');
    }

    private function preprocessText($text)
    {
        // 1. Case Folding: Mengubah semua huruf menjadi kecil
        $text = strtolower($text);

        // 2. Filtering: Menghapus tanda baca dan angka
        $text = preg_replace('/[^a-z\s]/', '', $text);

        // 3. Stopword Removal: Menghapus kata umum (yang, adalah, di, ke, dll)
        $stopwordFactory = new StopWordRemoverFactory();
        $remover = $stopwordFactory->createStopWordRemover();
        $text = $remover->remove($text);

        // 4. Stemming: Mengubah kata berimbuhan menjadi kata dasar
        // Contoh: "Memakan" -> "Makan", "Berlari" -> "Lari"
        $stemmerFactory = new StemmerFactory();
        $stemmer = $stemmerFactory->createStemmer();
        $text = $stemmer->stem($text);

        return $text;
    }

    // Fungsi Inti TF-IDF & Cosine Similarity
    private function calculateCosineSimilarity($keyText, $studentText)
    {
        // 1. Preprocessing
        $cleanKey = $this->preprocessText($keyText);
        $cleanStudent = $this->preprocessText($studentText);

        // 2. Tokenizing Unigram
        $u1 = array_filter(explode(' ', $cleanKey));
        $u2 = array_filter(explode(' ', $cleanStudent));

        // 3. Membuat Bigram
        $b1 = [];
        $keys1 = array_values($u1);
        for($i=0; $i < count($keys1)-1; $i++) { 
            $b1[] = $keys1[$i].' '.$keys1[$i+1]; 
        }

        $b2 = [];
        $keys2 = array_values($u2);
        for($i=0; $i < count($keys2)-1; $i++) { 
            $b2[] = $keys2[$i].' '.$keys2[$i+1]; 
        }

        // 4. Gabungkan Unigram dan Bigram 
        $t1 = array_merge($u1, $b1);
        $t2 = array_merge($u2, $b2);

        // 5. Vocabulary & Frequency
        $words = array_unique(array_merge($t1, $t2));
        $counts1 = array_count_values($t1);
        $counts2 = array_count_values($t2);

        $v1 = [];
        $v2 = [];
        $logTerms = []; // Variabel baru untuk mencatat daftar kata

        foreach ($words as $word) {
            $bobot1 = $counts1[$word] ?? 0;
            $bobot2 = $counts2[$word] ?? 0;
            
            $v1[] = $bobot1;
            $v2[] = $bobot2;

            // Catat bobot setiap kata untuk ditampilkan di View
            $logTerms[$word] = [
                'bobot_kunci' => $bobot1,
                'bobot_jawaban' => $bobot2
            ];
        }

        // 6. Dot Product & Magnitude
        $dotProduct = 0;
        foreach ($v1 as $i => $val) {
            $dotProduct += $v1[$i] * $v2[$i];
        }

        $mag1 = sqrt(array_sum(array_map(fn($x) => $x * $x, $v1)));
        $mag2 = sqrt(array_sum(array_map(fn($x) => $x * $x, $v2)));

        $score = ($mag1 * $mag2 == 0) ? 0 : ($dotProduct / ($mag1 * $mag2));
        
        // 7. Kembalikan Skor Murni DAN Log Perhitungan sebagai Array
        return [
            'score' => $score,
            'log' => [
                'clean_key' => $cleanKey,
                'clean_student' => $cleanStudent,
                'terms' => $logTerms,
                'dot_product' => $dotProduct,
                'magnitude_kunci' => $mag1,
                'magnitude_jawaban' => $mag2
            ]
        ];
    }

    public function examResult(\App\Models\Exam $exam)
{
    $user = auth()->user();

    // Ambil semua jawaban mahasiswa untuk ujian ini
    $answers = \App\Models\Answer::where('user_id', $user->id)
        ->whereHas('question', function ($query) use ($exam) {
            $query->where('exam_id', $exam->id);
        })
        ->with('question')
        ->get();

    if ($answers->isEmpty()) {
        return redirect()->route('mahasiswa.subjects.index')->with('error', 'Hasil tidak ditemukan.');
    }

    // Hitung nilai rata-rata
    $totalScore = $answers->avg('score');

    return view('mahasiswa.exams.result', compact('exam', 'answers', 'totalScore'));
}
}