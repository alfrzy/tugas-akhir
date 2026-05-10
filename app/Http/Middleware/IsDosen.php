<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsDosen
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah login dan rolenya dosen
        if (auth()->check() && auth()->user()->role === 'dosen') {
            return $next($request);
        }

        // Jika bukan dosen (misal mahasiswa coba-coba), tendang ke dashboard
        return redirect()->route('dashboard')->with('error', 'Akses ditolak! Halaman ini khusus Dosen.');
    }
}
