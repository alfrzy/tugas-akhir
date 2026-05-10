<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan user sudah login DAN punya role admin
        if (auth()->check() && auth()->user()->role === 'admin') {
            return $next($request);
        }

        // Jika bukan admin, arahkan ke dashboard dengan pesan peringatan
        return redirect()->route('dashboard')->with('error', 'Akses ditolak! Anda bukan Admin.');
    }
}
