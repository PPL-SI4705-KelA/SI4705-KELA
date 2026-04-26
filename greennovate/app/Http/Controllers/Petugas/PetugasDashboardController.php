<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PetugasDashboardController extends Controller
{
    /**
     * Halaman utama Petugas Dashboard.
     * Hanya bisa diakses oleh user dengan role 'petugas'.
     */
    public function index()
    {
        return view('petugas.dashboard');
    }
}
