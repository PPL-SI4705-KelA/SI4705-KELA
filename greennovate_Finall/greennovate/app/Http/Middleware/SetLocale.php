<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * Prioritas penentuan locale:
     * 1. Session (diset saat user mengubah preferensi bahasa)
     * 2. User preference dari database (jika sudah login)
     * 3. Default dari config/app.php
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = config('app.locale'); // Default fallback

        if (session()->has('locale')) {
            $locale = session('locale');
        } elseif (Auth::check() && Auth::user()->locale) {
            $locale = Auth::user()->locale;
            session(['locale' => $locale]);
        }

        // Pastikan hanya locale yang valid
        if (in_array($locale, ['id', 'en'])) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
