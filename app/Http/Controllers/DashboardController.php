<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\Submission;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // ==========================================
        // DATA UNTUK ADMIN
        // ==========================================
        if ($user->role === 'admin') {
            $totalDosen = User::where('role', 'dosen')->count();
            $totalMahasiswa = User::where('role', 'mahasiswa')->count();
            $totalMatkuliah = Subject::count();
            $totalUjian = Exam::count();

            $recentSubmissions = Submission::with(['user', 'exam.subject'])
                ->latest()
                ->limit(10)
                ->get();

            $subjectsWithInstructors = Subject::with(['user', 'exams', 'students'])
                ->get()
                ->map(function ($subject) {
                    $totalExams = $subject->exams->count();
                    $totalStudents = $subject->students->count();
                    $expectedSubmissions = $totalExams * $totalStudents;

                    $examIds = $subject->exams->pluck('id'); 
                    $actualSubmissions = Submission::whereIn('exam_id', $examIds)->count();

                    $progress = 0;
                    if ($expectedSubmissions > 0) {
                        $progress = round(($actualSubmissions / $expectedSubmissions) * 100);
                    }

                    return [
                        'id' => $subject->id,
                        'name' => $subject->subject_name,
                        'instructors' => $subject->user ? $subject->user->name : 'Belum ada pengampu',
                        'code' => $subject->subject_code ?? '-',
                        'progress' => $progress,
                    ];
                });

            return view('dashboard', compact(
                'totalDosen',
                'totalMahasiswa',
                'totalMatkuliah',
                'totalUjian',
                'recentSubmissions',
                'subjectsWithInstructors'
            ));
        }

        // ==========================================
        // DATA UNTUK DOSEN
        // ==========================================
        elseif ($user->role === 'dosen') {
            
            // 1. Total Kelas (Mata Kuliah yang diajar oleh dosen ini)
            $totalKelas = Subject::where('user_id', $user->id)->count();

            // 2. Total Ujian (Mencari ujian dari mata kuliah dosen ini)
            $totalUjian = Exam::whereHas('subject', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->count();

            // 3. Total Esai Dinilai (Submission pada ujian milik dosen ini)
            $totalDinilai = Submission::whereHas('exam.subject', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('is_published', true) 
            ->count();

            // 4. Ujian Terbaru (2 ujian terakhir yang dibuat dosen ini)
            $ujianTerbaru = Exam::with(['subject', 'submissions'])
                ->whereHas('subject', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->latest()
                ->limit(2)
                ->get();

            // 5. Submission Terbaru (5 aktivitas terakhir dari mahasiswa)
            $submissionTerbaru = Submission::with(['user', 'exam.subject'])
                ->whereHas('exam.subject', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->latest()
                ->limit(5)
                ->get();

            return view('dashboard', compact(
                'totalKelas', 
                'totalUjian', 
                'totalDinilai', 
                'ujianTerbaru', 
                'submissionTerbaru'
            ));
        }

        // ==========================================
        // DATA UNTUK MAHASISWA
        // ==========================================
        elseif ($user->role === 'mahasiswa') {
            
            // 1. Total Kelas Diikuti (Asumsi: model User memiliki relasi subjects() ke tabel pivot)
            $totalKelasMahasiswa = $user->subjects()->count(); 

            // 2. Ujian Selesai (Jumlah esai/submission yang sudah dikerjakan mahasiswa ini)
            $ujianSelesai = \App\Models\Submission::where('user_id', $user->id)->count();

            // 3. Rata-rata Nilai (Dari esai yang sudah dinilai/di-publish dosen)
            $rataRataNilai = \App\Models\Submission::where('user_id', $user->id)
                                ->where('is_published', true)
                                ->avg('total_score') ?? 0; // avg() menghitung rata-rata otomatis

            // 4. Daftar Kelas Saya (Mengambil 3 kelas terbaru yang diikuti)
            $kelasSaya = $user->subjects()
                            ->with(['user', 'exams']) // Bawa data Dosen (user) dan daftar Ujian (exams)
                            ->latest()
                            ->limit(3)
                            ->get();

            // Kirim variabel-variabel ini ke tampilan (view)
            return view('dashboard', compact(
                'totalKelasMahasiswa',
                'ujianSelesai',
                'rataRataNilai',
                'kelasSaya'
            ));
        }

        return view('dashboard');
    }
}