<?php

namespace App\Http\Middleware;

use Closure;

class OwnerMiddleware
{
  public function handle($request, Closure $next)
{
    $user = auth()->user();

    // Cek apakah user terautentikasi
    if (!$user) {
        return redirect()->route('login');
    }

    // Cek role dengan flexible case
    if (strtolower($user->role) !== 'owner') {
        abort(403, 'Akses khusus owner!');
    }

    return $next($request);
}
}
