<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LokasiLahan;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_pengguna'   => User::where('role', '!=', 'admin')->count(),
            'total_admin'      => User::where('role', 'admin')->count(),
            'total_lokasi'     => LokasiLahan::count(),
            'pengguna_terbaru' => User::where('role', '!=', 'admin')
                                      ->latest()
                                      ->take(5)
                                      ->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
