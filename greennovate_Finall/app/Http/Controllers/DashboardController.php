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
        $user = Auth::user();

        if ($user->role === 'user') {
            $stat = $user->o2Stat;
            $currentO2 = $stat ? $stat->total_o2_kg_per_bulan : 0.0;
            
            $nextBadge = \App\Models\Achievement::where('threshold_o2', '>', $currentO2)
                ->orderBy('threshold_o2', 'asc')
                ->first();
                
            $achievements = $user->achievements()->with('achievement')->get();

            return view('user.dashboard', [
                'stat' => $stat,
                'nextBadge' => $nextBadge,
                'achievements' => $achievements,
            ]);
        }

        return match ($user->role) {
            'admin'   => redirect()->route('admin.dashboard'),
            'petugas' => redirect()->route('petugas.dashboard'),
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