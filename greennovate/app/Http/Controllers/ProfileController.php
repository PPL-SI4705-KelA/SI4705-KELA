<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index');
    }

    public function edit()
    {
        return view('profile.edit');
    }

    public function update(Request $request)
    {
        
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'city'  => 'nullable|string|max:100',
        ]);    
    
        $user = Auth::user();

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'city'  => $request->city,
        ]);

        return redirect()->route('profile.index')
            ->with('success', 'Profil berhasil diupdate');
    }

    public function showChangePasswordForm()
    {
        return view('profile.change-password'); 
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
                'old_password' => 'required',
                'new_password' => 'required|min:6|confirmed',
            ]);    

        $user = Auth::user();

        if (!Hash::check($request->old_password, $user->password)) {
            return back()->with('error', 'Password lama salah');
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password berhasil diubah');
    }
}