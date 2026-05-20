<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\Question;
use Carbon\Carbon;

class LatePenaltySeeder extends Seeder
{
    public function run()
    {
        // 1. Ambil satu dosen (Jika tidak ada, buat baru)
        $dosen = User::where('role', 'dosen')->first() ?? User::create([
            'name' => 'Dosen Penguji Telat',
            'email' => 'dosen.telat@example.com',
            'password' => bcrypt('password'),
            'role' => 'dosen'
        ]);

        // 2. Buat mata kuliah khusus untuk test ini
        $subject = Subject::create([
            'user_id' => $dosen->id,
            'subject_code' => 'TEST-LATE-101',
            'subject_name' => 'Mata Kuliah Test Keterlambatan',
        ]);

        // 3. Daftarkan semua mahasiswa ke matkul ini agar mudah ditest oleh siapa saja
        $mahasiswaIds = User::where('role', 'mahasiswa')->pluck('id');
        $subject->students()->sync($mahasiswaIds);

        // 4. Buat ujian dengan status 'Terlambat' (contoh: batas waktu 2 jam 30 menit yang lalu)
        $exam = Exam::create([
            'subject_id' => $subject->id,
            'title'      => 'Ujian Susulan (Simulasi Telat 3 Jam)',
            'duration'   => 60, // 60 menit
            'start_time' => Carbon::now()->subDays(1), // Mulai kemarin
            'end_time'   => Carbon::now()->subHours(2)->subMinutes(30) // Berakhir 2.5 jam yang lalu
        ]);

        // Dengan batas akhir 2.5 jam yang lalu, sistem (ceil(2.5)) akan membulatkan menjadi telat 3 jam
        // Denda = 3 * 10% = 30% pemotongan nilai.

        // 5. Buat 1 pertanyaan simple
        Question::create([
            'exam_id'       => $exam->id,
            'question_text' => 'Tuliskan "Saya terlambat mengerjakan ujian ini" untuk mensimulasikan nilai.',
            'key_answer'    => 'Saya terlambat mengerjakan ujian ini'
        ]);

        $this->command->info('Berhasil membuat ujian simulasi keterlambatan!');
        $this->command->info('Silakan login sebagai Mahasiswa dan kerjakan "Ujian Susulan (Simulasi Telat 3 Jam)".');
    }
}
