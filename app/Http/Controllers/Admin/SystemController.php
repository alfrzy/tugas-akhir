<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\Subject;

class SystemController extends Controller
{
    // 1. Logika Pantau Ujian Global
    public function monitorExams()
    {
        // Mengambil semua ujian, diurutkan dari yang terbaru,
        // beserta relasi ke mata kuliah, dosen pengampu, dan jumlah submission.
        $exams = Exam::with(['subject.user'])
                    ->withCount('submissions') // Menghitung otomatis jumlah esai masuk
                    ->latest()
                    ->paginate(10); // Menampilkan 10 per halaman agar rapi

        return view('admin.exams-monitor.index', compact('exams'));
    }

    // 2. Logika Pengaturan Sistem (Tampilan)
    public function settings()
    {
        // Untuk sekarang kita pakai data dummy. 
        // Nanti bisa kamu sambungkan ke tabel `settings` di database jika diperlukan.
        $currentSettings = [
            'academic_year' => '2025/2026',
            'semester' => 'Genap',
            'maintenance_mode' => false,
        ];

        return view('admin.settings.index', compact('currentSettings'));
    }

    // 3. Logika Simpan Pengaturan
    public function updateSettings(Request $request)
    {
        // Validasi dan simpan pengaturan...
        // (Logika simpan ke database bisa ditambahkan di sini)

        return redirect()->back()->with('success', 'Pengaturan sistem berhasil diperbarui!');
    }
}