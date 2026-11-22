<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Memeriksa apakah pengguna memiliki role yang diizinkan.
     *
     * @param  \Closure(\Illuminate\Http\Request): \Symfony\Component\HttpFoundation\Response  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Ambil pengguna yang sedang login.
        $user = $request->user();

        // Jika belum login, arahkan ke halaman Login.
        if (! $user) {
            return redirect()->route('login');
        }

        // Jika role pengguna tidak termasuk dalam daftar yang diizinkan,
        // kembalikan respons 403 (Forbidden).
        if (! in_array($user->role, $roles, true)) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        // Jika role sesuai, lanjutkan permintaan ke halaman tujuan.
        return $next($request);
    }
}
