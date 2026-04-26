<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Halaman utama Admin Dashboard.
     * Hanya bisa diakses oleh user dengan role 'admin'.
     */
    public function index()
    {
        $stats = [
            'total_users'    => User::where('role', 'user')->count(),
            'total_petugas'  => User::where('role', 'petugas')->count(),
            'total_aktif'    => User::where('is_active', true)->count(),
            'total_nonaktif' => User::where('is_active', false)->count(),
        ];

        $users = User::where('role', 'user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'users'));
    }
}
