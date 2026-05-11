<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $mySubjects = auth()->user()->enrolledSubjects()->with('user')->get();

        return view('mahasiswa.subjects.index', compact('mySubjects'));
    }

    public function available(Request $request)
    {
        // 1. Ambil SEMUA mata kuliah beserta dosennya
        $query = Subject::whereNotNull('user_id')->with('user');

        // 2. Logika pencarian
        if ($request->has('search')) {
            $query->where('subject_name', 'like', '%'.$request->search.'%')
                ->orWhere('subject_code', 'like', '%'.$request->search.'%');
        }

        $subjects = $query->get();

        // 3. Ambil daftar ID mata kuliah yang SUDAH diikuti oleh mahasiswa ini
        $enrolledSubjectIds = auth()->user()->enrolledSubjects()->pluck('subjects.id')->toArray();

        // 4. Kirim kedua data ke view
        return view('mahasiswa.subjects.available', compact('subjects', 'enrolledSubjectIds'));
    }

    public function join(Request $request, Subject $subject)
    {
        // 1. Validasi input harus ada
        $request->validate([
            'join_code' => 'required|string',
        ]);

        // 2. Cek apakah kodenya cocok (Diubah ke huruf besar semua agar tidak sensitif huruf kecil/besar)
        if (strtoupper($request->join_code) !== strtoupper($subject->join_code)) {
            return redirect()->back()->with('error', 'Gagal bergabung: Kode kelas tidak valid atau salah ketik!');
        }

        // 3. Pencegahan tambahan: Cek apakah mahasiswa ini sebenarnya sudah join sebelumnya
        if (auth()->user()->enrolledSubjects()->where('subjects.id', $subject->id)->exists()) {
            return redirect()->back()->with('error', 'Anda sudah terdaftar di kelas ini.');
        }

        // 4. Jika cocok, masukkan ke kelas!
        auth()->user()->enrolledSubjects()->attach($subject->id);

        return redirect()->route('mahasiswa.subjects.index')
            ->with('success', 'Berhasil bergabung ke kelas '.$subject->subject_name);
    }
}
