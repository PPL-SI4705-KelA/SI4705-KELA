<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Redirect pengguna ke dashboard sesuai rolenya.
     */
    public function index()
    {
        $user = Auth::user();

        return redirect()->route($user->dashboardRoute());
    }
}
