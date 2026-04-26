<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Redirect user ke dashboard sesuai role-nya setelah login.
     * - admin   → /admin/dashboard
     * - petugas → /petugas/dashboard
     * - user    → halaman user/dashboard
     * - lainnya → logout + 403
     */
    public function index()
    {
        $role = Auth::user()->role;

        return match ($role) {
            'admin'   => redirect()->route('admin.dashboard'),
            'petugas' => redirect()->route('petugas.dashboard'),
            'user'    => view('user.dashboard'),
            default   => $this->forbiddenAndLogout(),
        };
    }

    /**
     * Logout dan abort 403 untuk role tidak dikenal.
     */
    private function forbiddenAndLogout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        abort(403, 'Role tidak dikenal. Akun Anda telah dikeluarkan.');
    }
}