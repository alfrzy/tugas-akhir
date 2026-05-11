<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubjectController extends Controller
{
    // Menampilkan halaman daftar mata kuliah
    public function index()
    {
        $subjects = Subject::where('user_id', auth()->id())->get();

        return view('dosen.subjects.index', compact('subjects'));
    }

    // Menyimpan data mata kuliah baru (Tambah)
    public function store(Request $request)
    {
        $request->validate([
            'subject_code' => 'required|unique:subjects,subject_code',
            'subject_name' => 'required|string|max:255',
        ]);

        $kodeAcak = strtoupper(Str::random(6));

        Subject::create([
            'subject_code' => $request->subject_code,
            'subject_name' => $request->subject_name,
            'user_id' => auth()->id(), // Otomatis set ke dosen yang login
            'join_code' => $kodeAcak,
        ]);

        return redirect()->back()->with('success', 'Mata kuliah berhasil ditambahkan!');
    }

    // Memperbarui data mata kuliah (Edit)
    public function update(Request $request, Subject $subject)
    {
        // Proteksi agar dosen tidak mengedit matkul dosen lain
        if ($subject->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'subject_code' => 'required|unique:subjects,subject_code,'.$subject->id,
            'subject_name' => 'required|string|max:255',
        ]);

        $subject->update($request->only('subject_code', 'subject_name'));

        return redirect()->back()->with('success', 'Mata kuliah berhasil diperbarui!');
    }

    // Menghapus data mata kuliah (Hapus)
    public function destroy(Subject $subject)
    {
        if ($subject->user_id !== auth()->id()) {
            abort(403);
        }

        $subject->delete();

        return redirect()->back()->with('success', 'Mata kuliah berhasil dihapus.');
    }
}
