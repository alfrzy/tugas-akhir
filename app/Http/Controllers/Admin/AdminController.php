<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Subject;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Halaman Kelola Semua User (Dosen & Mahasiswa)
    public function usersIndex(Request $request)
    {
        // Ambil parameter 'role' dari URL, jika tidak ada default-nya 'dosen'
        $role = $request->query('role', 'dosen');

        // Filter user berdasarkan role yang dipilih
        $users = \App\Models\User::where('role', $role)->get();

        return view('admin.users.index', compact('users', 'role'));
    }

    // Halaman Kelola Semua Mata Kuliah
    public function subjectsIndex() {
    $subjects = \App\Models\Subject::with('user')->get();
    return view('admin.subjects.index', compact('subjects'));
}
    // Fungsi untuk menghapus User (Admin power)
    public function destroyUser(User $user)
    {
        $user->delete();
        return redirect()->back()->with('success', 'User berhasil dihapus.');
    }

    public function assignTutor(\App\Models\Subject $subject)
{
    // Ambil semua user yang rolenya 'dosen'
    $dosens = User::where('role', 'dosen')->get();
    
    return view('admin.subjects.assign', compact('subject', 'dosens'));
}

public function updateTutor(Request $request, \App\Models\Subject $subject)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
    ]);

    // Update user_id di tabel subjects
    $subject->update([
        'user_id' => $request->user_id
    ]);

    return redirect()->route('admin.subjects.index')->with('success', 'Dosen pengampu berhasil diperbarui!');
}

public function createSubject()
{
    // Kita ambil data dosen juga agar admin bisa langsung menugaskan dosen saat membuat matkul
    $dosens = User::where('role', 'dosen')->get();
    return view('admin.subjects.create', compact('dosens'));
}

public function storeSubject(Request $request)
{
    $request->validate([
        'subject_code' => 'required|unique:subjects,subject_code',
        'subject_name' => 'required|string|max:255',
        'user_id'      => 'nullable|exists:users,id', // Opsional
    ]);

    \App\Models\Subject::create([
        'subject_code' => $request->subject_code,
        'subject_name' => $request->subject_name,
        'user_id'      => $request->user_id,
    ]);

    return redirect()->route('admin.subjects.index')->with('success', 'Mata Kuliah berhasil ditambahkan!');
}

}
