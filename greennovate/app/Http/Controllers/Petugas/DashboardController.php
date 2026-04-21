<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard petugas.
     */
    public function index()
    {
        return view('petugas.dashboard');
    }
}
