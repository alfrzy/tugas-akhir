<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Submission;
use App\Models\Answer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UniversitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Dosen
        $dosen = User::firstOrCreate(
            ['email' => 'dosen.seeder@example.com'],
            [
                'name' => 'Dr. Budi Santoso, M.Kom',
                'password' => Hash::make('password'),
                'role' => 'dosen',
            ]
        );

        // 2. Create Mahasiswa (5 students)
        $mahasiswaList = [];
        for ($i = 1; $i <= 5; $i++) {
            $mahasiswaList[] = User::firstOrCreate(
                ['email' => "mhs{$i}.seeder@example.com"],
                [
                    'name' => "Mahasiswa {$i} Seeder",
                    'password' => Hash::make('password'),
                    'role' => 'mahasiswa',
                    'nim' => "2023000{$i}",
                ]
            );
        }

        // 3. Create Subjects (5 mata kuliah)
        $subjectsData = [
            ['subject_name' => 'Pemrograman Web Lanjut', 'subject_code' => 'PWL201'],
            ['subject_name' => 'Kecerdasan Buatan', 'subject_code' => 'AI302'],
            ['subject_name' => 'Jaringan Komputer', 'subject_code' => 'JK101'],
            ['subject_name' => 'Basis Data Lanjut', 'subject_code' => 'BDL205'],
            ['subject_name' => 'Rekayasa Perangkat Lunak', 'subject_code' => 'RPL401'],
        ];

        $subjects = [];
        foreach ($subjectsData as $data) {
            $subjects[] = Subject::firstOrCreate(
                ['subject_code' => $data['subject_code']],
                [
                    'user_id' => $dosen->id,
                    'subject_name' => $data['subject_name'],
                    'join_code' => strtoupper(substr(md5(mt_rand()), 0, 6)),
                ]
            );
        }

        // 4. Enroll Mahasiswa to all subjects
        foreach ($subjects as $subject) {
            $syncData = [];
            foreach ($mahasiswaList as $mhs) {
                $syncData[] = $mhs->id;
            }
            $subject->users()->syncWithoutDetaching($syncData);
        }

        // 5. Create Exam for one subject (Pemrograman Web Lanjut)
        $targetSubject = $subjects[0];
        
        $exam = Exam::firstOrCreate(
            ['title' => 'Ujian Tengah Semester - Pemrograman Web Lanjut', 'subject_id' => $targetSubject->id],
            [
                'duration' => 90,
                'start_time' => Carbon::now()->subDays(1),
                'end_time' => Carbon::now()->addDays(1),
            ]
        );

        // 6. Create Questions and Key Answers (5 essay questions)
        $questionsData = [
            [
                'question_text' => 'Jelaskan apa yang dimaksud dengan pola arsitektur MVC (Model-View-Controller) pada framework Laravel!',
                'key_answer' => 'MVC adalah pola arsitektur yang memisahkan aplikasi menjadi tiga komponen utama: Model (mengelola data dan logika bisnis), View (menangani tampilan antarmuka pengguna), dan Controller (menerima input, memproses request, dan menghubungkan Model dengan View). Pemisahan ini memudahkan pengembangan dan pemeliharaan aplikasi.'
            ],
            [
                'question_text' => 'Apa fungsi utama dari Middleware dalam pengembangan aplikasi web berbasis Laravel?',
                'key_answer' => 'Middleware berfungsi sebagai lapisan penengah antara HTTP request dan aplikasi. Tugas utamanya adalah memfilter request yang masuk, misalnya melakukan autentikasi (memastikan user sudah login) atau autorisasi sebelum request tersebut mencapai route atau controller.'
            ],
            [
                'question_text' => 'Jelaskan perbedaan antara Eloquent ORM dan Query Builder pada Laravel!',
                'key_answer' => 'Eloquent ORM adalah fitur pemetaan objek-relasional (Object-Relational Mapping) bawaan Laravel yang memungkinkan interaksi dengan database menggunakan model (objek). Sangat mudah digunakan karena otomatis mengelola relasi antar tabel. Sedangkan Query Builder adalah antarmuka untuk membuat dan menjalankan query database secara lebih manual dan langsung, biasanya lebih cepat untuk query kompleks namun kodenya bisa lebih panjang dibanding Eloquent.'
            ],
            [
                'question_text' => 'Mengapa kita perlu menggunakan CSRF Token pada setiap form dengan metode POST di Laravel?',
                'key_answer' => 'CSRF (Cross-Site Request Forgery) Token diperlukan untuk melindungi aplikasi dari serangan pihak ketiga yang mencoba mengirimkan request palsu atas nama pengguna yang sedang login. Laravel secara otomatis akan menolak form POST, PUT, atau DELETE jika tidak menyertakan token CSRF yang valid.'
            ],
            [
                'question_text' => 'Jelaskan langkah-langkah membuat RESTful API sederhana menggunakan Resource Controller di Laravel!',
                'key_answer' => 'Langkah-langkahnya: 1. Buat resource controller menggunakan command `php artisan make:controller ApiController --resource`. 2. Daftarkan route di `routes/api.php` menggunakan `Route::apiResource()`. 3. Di dalam controller, implementasikan metode standar seperti index() untuk GET, store() untuk POST, show() untuk GET spesifik, update() untuk PUT/PATCH, dan destroy() untuk DELETE, lalu kembalikan response dalam bentuk JSON.'
            ]
        ];

        $questions = [];
        foreach ($questionsData as $qData) {
            $questions[] = Question::firstOrCreate(
                ['question_text' => $qData['question_text'], 'exam_id' => $exam->id],
                ['key_answer' => $qData['key_answer']]
            );
        }

        // 7. Create Submissions and Answers for Mahasiswa
        // Let's make Mahasiswa 1 have perfect answers, Mahasiswa 2 good, Mahasiswa 3 average, etc.
        $studentAnswers = [
            // Mahasiswa 1: Perfect (similar to key)
            [
                'MVC adalah pola desain yang membagi aplikasi ke tiga bagian: Model untuk data, View untuk tampilan, dan Controller untuk menghubungkan keduanya. Ini memisahkan logika dan tampilan.',
                'Middleware bertindak sebagai perantara yang memfilter HTTP request. Contohnya untuk mengecek apakah user sudah login atau belum sebelum masuk ke halaman tertentu.',
                'Eloquent adalah ORM Laravel yang berinteraksi dengan DB pakai model, sedangkan Query builder dipakai untuk nulis query secara lebih manual dan biasanya lebih cepat.',
                'CSRF token dipakai untuk keamanan, mencegah serangan cross-site request forgery, memastikan form dikirim dari aplikasi kita sendiri, bukan dari website penyerang.',
                'Caranya bikin controller dengan flag --resource, daftarkan route dengan Route::apiResource di api.php, dan kembalikan data dalam format JSON pada setiap method.'
            ],
            // Mahasiswa 2: Good (some slight differences)
            [
                'Model View Controller atau MVC adalah konsep memisahkan kodingan database (Model), interface (View) dan proses logika (Controller) di Laravel.',
                'Middleware itu fungsinya buat ngefilter akses, jadi kalau ada orang belum login dia bakal ditendang ke halaman login dan gak bisa buka halaman admin.',
                'Eloquent itu pakai class Model untuk query jadi lebih gampang dibaca, kalau query builder itu langsung pakai DB::table dan lebih mirip query SQL biasa.',
                'Biar aman dari hacker yang nyoba kirim request palsu (CSRF). Laravel otomatis minta token ini kalau kita submit form pakai POST.',
                'Pertama buat controller api resource, terus tambahkan di route api.php, lalu di controller kita buat logic untuk nampilin data dan menyimpannya pakai format JSON.'
            ],
            // Mahasiswa 3: Average (short, misses some points)
            [
                'MVC adalah arsitektur yang membagi web jadi 3 bagian: model, view, controller.',
                'Middleware adalah filter request HTTP. Bisa untuk autentikasi user.',
                'Eloquent itu ORM bawaan laravel, query builder itu untuk buat query sql.',
                'Untuk mencegah serangan CSRF, jadi aman.',
                'Buat controller, buat route api, lalu return json.'
            ],
            // Mahasiswa 4: Poor (very short/partially incorrect)
            [
                'Model untuk database, View untuk html, Controller untuk php.',
                'Middleware itu buat mengatur rute web.',
                'Eloquent buat database, query builder buat tabel.',
                'Token untuk login keamanan.',
                'Pakai route api.'
            ],
            // Mahasiswa 5: Empty/Missed Exam (no submission)
        ];

        $nlpService = new \App\Services\NlpScoringService();

        foreach ($mahasiswaList as $index => $mhs) {
            // Skip the 5th student to simulate a student who didn't take the exam
            if ($index == 4) continue;

            $submission = Submission::updateOrCreate(
                ['user_id' => $mhs->id, 'exam_id' => $exam->id],
                [
                    'started_at' => Carbon::now()->subMinutes(60),
                    'finished_at' => Carbon::now()->subMinutes(10),
                    'is_finalized' => true,
                    'total_score' => 0, // Akan diupdate di bawah
                ]
            );

            $totalScore = 0;

            // Insert Answers
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
    }
}
