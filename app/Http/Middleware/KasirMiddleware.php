<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class KasirMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cek apakah role user adalah kasir
        if (Auth::user()->role !== 'kasir') {
            return redirect()->route('home')
                ->with('error', 'Akses ditolak! Hanya kasir yang dapat mengakses halaman ini.');
        }

        // Jika lolos semua pengecekan, lanjutkan request
        return $next($request);
    }
}