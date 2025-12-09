<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class BatasanPengguna
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$peran): Response
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        // Cek apakah tipe_pekerjaan user ada di dalam daftar peran yang diizinkan
        if (in_array($user->pengguna()->get()->first()->tipe_pekerjaan, $peran)) {
            return $next($request);
        }

        // Jika tidak punya akses, arahkan kembali atau tampilkan error 403
        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}
