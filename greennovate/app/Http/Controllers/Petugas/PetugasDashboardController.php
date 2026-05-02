<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

class PetugasDashboardController extends Controller
{
    /**
     * Halaman utama Petugas Dashboard.
     * Hanya bisa diakses oleh user dengan role 'petugas'.
     */
    public function index()
    {
        // Ambil kegiatan aktif milik petugas yang sedang login
        $kegiatans = Kegiatan::with('lokasiLahan')
            ->where('petugas_id', auth()->id())
            ->whereIn('status', ['Persiapan', 'Berlangsung'])
            ->get();

        return view('petugas.dashboard', compact('kegiatans'));
    }
}