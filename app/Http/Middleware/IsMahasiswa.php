<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsMahasiswa
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
{
    if (auth()->check() && auth()->user()->role === 'mahasiswa') {
        return $next($request);
    }

    return redirect()->route('dashboard')->with('error', 'Akses ditolak! Halaman ini khusus Mahasiswa.');
}
}
