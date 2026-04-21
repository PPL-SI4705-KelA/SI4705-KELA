<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard user.
     */
    public function index()
    {
        return view('user.dashboard');
    }
}
