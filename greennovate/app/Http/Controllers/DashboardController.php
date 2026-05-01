<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'petugas') {
            return redirect()->route('petugas.dashboard');
        }

        // For regular users, maybe we don't have a specific dashboard yet, so redirect to profile or home.
        // Or if we have a user dashboard view, return it here.
        return view('dashboard');
    }
}
