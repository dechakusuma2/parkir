<?php

namespace App\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Membatasi akses permintaan berdasarkan peran pengguna. */
class RoleMiddleware
{
    /** Memastikan pengguna memiliki salah satu peran yang diizinkan sebelum melanjutkan. */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, $roles, true)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
