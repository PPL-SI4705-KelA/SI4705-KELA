<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard admin.
     */
    public function index()
    {
        $stats = [
            'total_users'    => User::where('role', 'user')->count(),
            'total_petugas'  => User::where('role', 'petugas')->count(),
            'total_admin'    => User::where('role', 'admin')->count(),
            'inactive_users' => User::where('is_active', false)->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
