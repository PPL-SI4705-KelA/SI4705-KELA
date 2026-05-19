<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsPetugas
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role === 'petugas') {
            return $next($request);
        }

        // If not petugas, check if admin (admins usually can access everything, but maybe let's just restrict strictly or redirect to respective dashboards)
        if (auth()->check() && auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect('/')->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk halaman tersebut.');
    }
}
