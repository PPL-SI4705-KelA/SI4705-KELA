<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function showForm()
    {
        return view('auth.login');
    }

    /**
     * Proses login pengguna.
     * FR-01: Validasi kredensial, cek is_active, rate limiting 5x/menit.
     */
    public function login(Request $request)
    {
        $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'login.required'    => 'Email atau No HP wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Rate limiting: maks 5 percobaan per menit per IP+login
        $throttleKey = Str::lower($request->input('login')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()
                ->withInput($request->only('login'))
                ->withErrors(['login' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik."]);
        }

        $loginValue = $request->input('login');

        // Tentukan field login (email atau phone)
        $field = filter_var($loginValue, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $credentials = [
            $field     => $loginValue,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            // Cek status aktif akun
            if (! $user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()
                    ->withInput($request->only('login'))
                    ->withErrors(['login' => 'Akun dinonaktifkan. Hubungi administrator.']);
            }

            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        // Login gagal — tambah hit rate limiter
        RateLimiter::hit($throttleKey, 60);

        return back()
            ->withInput($request->only('login'))
            ->withErrors(['login' => 'Kredensial tidak valid.']);
    }

    /**
     * Logout pengguna.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
