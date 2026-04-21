<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Tampilkan halaman registrasi.
     */
    public function showForm()
    {
        return view('auth.register');
    }

    /**
     * Proses registrasi pengguna baru.
     * FR-01: Validasi input, simpan dengan password ter-hash (bcrypt), login otomatis.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'login'    => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ], [
            'name.required'     => 'Nama wajib diisi.',
            'login.required'    => 'Email atau No HP wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed'=> 'Konfirmasi password tidak cocok.',
            'password.min'      => 'Password minimal 8 karakter.',
        ]);

        $loginValue = $request->input('login');

        // Tentukan apakah input adalah email atau nomor HP
        if (filter_var($loginValue, FILTER_VALIDATE_EMAIL)) {
            $userData = [
                'name'     => $request->name,
                'email'    => $loginValue,
                'password' => $request->password,
            ];
        } else {
            $userData = [
                'name'     => $request->name,
                'phone'    => $loginValue,
                'password' => $request->password,
            ];
        }

        $user = User::create($userData);

        // Login otomatis setelah registrasi
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Selamat datang di Greennovate!');
    }
}
