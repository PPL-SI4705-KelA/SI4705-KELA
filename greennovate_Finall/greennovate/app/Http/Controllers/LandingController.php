<?php

namespace App\Http\Controllers;

use App\Models\LokasiLahan;
use App\Models\User;

class LandingController extends Controller
{
    public function index()
    {
        $stats = [
            // Data real dari database
            'total_relawan' => User::where('role', '!=', 'admin')->count(),
            'total_lokasi'  => LokasiLahan::count(),

            // Data dari tabel Kegiatan
            'total_program' => \App\Models\Kegiatan::count(),
            'pohon_ditanam' => \App\Models\Kegiatan::sum('realisasi_pohon'),
        ];

        // Ambil 3 lokasi lahan terbaru sebagai pengganti kampanye
        $lokasi_terbaru = LokasiLahan::latest()->take(3)->get();

        return view('welcome', compact('stats', 'lokasi_terbaru'));
    }
}
