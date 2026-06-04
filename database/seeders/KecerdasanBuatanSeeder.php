<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Submission;
use App\Models\Answer;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class KecerdasanBuatanSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil Dosen
        $dosen = User::where('email', 'dosen.seeder@example.com')->first();
        if (!$dosen) {
            $this->command->error('Dosen seeder tidak ditemukan. Jalankan UniversitySeeder terlebih dahulu.');
            return;
        }

        // 2. Ambil Mahasiswa
        $mahasiswaList = User::where('role', 'mahasiswa')->where('email', 'like', 'mhs%seeder@example.com')->orderBy('id')->get();

        // 3. Ambil Mata Kuliah Kecerdasan Buatan
        $targetSubject = Subject::where('subject_code', 'AI302')->first();
        if (!$targetSubject) {
             $targetSubject = Subject::create([
                 'user_id' => $dosen->id,
                 'subject_name' => 'Kecerdasan Buatan',
                 'subject_code' => 'AI302',
                 'join_code' => strtoupper(substr(md5(mt_rand()), 0, 6)),
             ]);
             $targetSubject->users()->syncWithoutDetaching($mahasiswaList->pluck('id'));
        }

        // 4. Create Exam
        $exam = Exam::firstOrCreate(
            ['title' => 'Ujian Akhir Semester - Kecerdasan Buatan', 'subject_id' => $targetSubject->id],
            [
                'duration' => 120,
                'start_time' => Carbon::now()->subDays(2),
                'end_time' => Carbon::now()->addDays(2),
            ]
        );

        // 5. Create Questions
        $questionsData = [
            [
                'question_text' => 'Jelaskan perbedaan mendasar antara Machine Learning dan Deep Learning!',
                'key_answer' => 'Machine Learning adalah cabang dari AI yang memberikan kemampuan sistem untuk belajar dari data tanpa diprogram secara eksplisit. Deep Learning adalah subset dari Machine Learning yang menggunakan Artificial Neural Networks (jaringan saraf tiruan) dengan banyak lapisan (layer) tersembunyi untuk mengekstraksi fitur kompleks dari data besar secara otomatis.'
            ],
            [
                'question_text' => 'Apa yang dimaksud dengan proses Training dan Testing dalam pengembangan model AI?',
                'key_answer' => 'Training adalah proses di mana model AI belajar mengenali pola dari dataset (data latih) dengan menyesuaikan parameter internalnya untuk meminimalkan error. Testing adalah proses mengevaluasi kinerja model yang sudah dilatih menggunakan data baru (data uji) yang belum pernah dilihat sebelumnya untuk memastikan model dapat melakukan generalisasi dengan baik.'
            ],
            [
                'question_text' => 'Jelaskan secara singkat apa itu algoritma Supervised Learning dan berikan satu contoh penerapannya!',
                'key_answer' => 'Supervised learning adalah pendekatan di mana model dilatih menggunakan data berlabel, artinya setiap input data sudah memiliki target output yang benar. Contoh penerapannya adalah klasifikasi email spam (input berupa teks email, label berupa spam/bukan spam) atau prediksi harga rumah berdasarkan fitur-fitur rumah.'
            ]
        ];

        $questions = [];
        foreach ($questionsData as $qData) {
            $questions[] = Question::firstOrCreate(
                ['question_text' => $qData['question_text'], 'exam_id' => $exam->id],
                ['key_answer' => $qData['key_answer']]
            );
        }

        // 6. Create Submissions
        $studentAnswers = [
            // Mahasiswa 1: Perfect
            [
                'Machine learning itu sistem belajar dari data tanpa diprogram manual. Deep learning itu bagian dari machine learning tapi dia pake jaringan saraf tiruan (neural networks) berlapis-lapis untuk otomatis ekstrak fitur kompleks dari data yang sangat besar.',
                'Training itu melatih model pakai data latih biar dia belajar pola dan parameternya pas. Testing itu menguji model pakai data baru (data uji) yang belum pernah dia lihat untuk ngecek apakah modelnya bisa generalisasi dengan baik atau cuma hafal data training.',
                'Supervised learning adalah algoritma yang belajar pakai data berlabel, jadi input dan target outputnya sudah jelas. Contohnya untuk mendeteksi email spam, datanya dikasih label spam atau tidak.'
            ],
            // Mahasiswa 2: Good
            [
                'ML adalah subset AI untuk belajar dari data. Deep learning itu menggunakan artificial neural network yang punya banyak hidden layer.',
                'Training untuk melatih model, testing untuk menguji model dengan data baru untuk tahu kinerjanya.',
                'Supervised learning dilatih pakai data yang ada labelnya. Contohnya prediksi harga rumah.'
            ],
            // Mahasiswa 3: Average
            [
                'Machine learning belajar data, deep learning pake neural network.',
                'Training untuk melatih, testing untuk menguji akurasi.',
                'Supervised pakai data berlabel. Contoh deteksi spam.'
            ],
            // Mahasiswa 4: Poor
            [
                'Deep learning lebih bagus dari machine learning.',
                'Training itu sebelum testing.',
                'Pake label datanya.'
            ]
        ];

        $nlpService = new \App\Services\NlpScoringService();

        foreach ($mahasiswaList as $index => $mhs) {
            if ($index > 3) continue; // Only first 4 students

            $submission = Submission::updateOrCreate(
                ['user_id' => $mhs->id, 'exam_id' => $exam->id],
                [
                    'started_at' => Carbon::now()->subMinutes(80),
                    'finished_at' => Carbon::now()->subMinutes(20),
                    'is_finalized' => true,
                    'total_score' => 0,
                ]
            );

            $totalScore = 0;

            foreach ($questions as $qIndex => $question) {
                $ansText = $studentAnswers[$index][$qIndex] ?? '';
                $score = 0;
                $log = null;
                
                if (!empty($ansText)) {
                    $result = $nlpService->calculateCosineSimilarity($question->key_answer, $ansText);
                    $score = round($result['score'] * 100, 2);
                    $log = $result['log'];
                }

                Answer::updateOrCreate(
                    ['user_id' => $mhs->id, 'question_id' => $question->id],
                    [
                        'answer_text' => $ansText,
                        'score' => $score,
                        'calculation_log' => $log,
                        'ai_score' => 0,
                    ]
                );
                
                $totalScore += $score;
            }
            
            $averageScore = count($questions) > 0 ? ($totalScore / count($questions)) : 0;
            $submission->update(['total_score' => $averageScore]);
        }
        
        $this->command->info('Berhasil membuat seeder ujian Kecerdasan Buatan!');
    }
}
