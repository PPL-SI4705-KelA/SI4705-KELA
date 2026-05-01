<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kegiatan;

class PetugasDashboardController extends Controller
{
    public function index()
    {
        // Fetch active activities for the logged-in petugas
        $kegiatans = Kegiatan::with('lokasiLahan')
            ->where('petugas_id', auth()->id())
            ->whereIn('status', ['Persiapan', 'Berlangsung'])
            ->get();

        return view('petugas.dashboard', compact('kegiatans'));
    }
}
