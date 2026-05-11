<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        // 1. Validasi
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:dosen,mahasiswa'],
            'nim' => ['nullable', 'string', 'unique:users'],
            'teacher_code' => ['required_if:role,dosen', 'in:DOSEN-NLP-2026'],
            ], [
            // Pesan error kustom (Opsional)
            'teacher_code.required_if' => 'Kode Akses Dosen wajib diisi jika mendaftar sebagai Dosen.',
            'teacher_code.in' => 'Kode Akses Dosen tidak valid! Hubungi Admin.',
        ]);

        // 2. Simpan ke Database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'nim' => $request->nim,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}