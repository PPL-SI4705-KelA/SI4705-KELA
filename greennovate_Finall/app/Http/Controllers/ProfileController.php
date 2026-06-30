<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdatePreferencesRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil & pengaturan.
     * Mengelola data diri, keamanan, dan preferensi akun.
     */
    public function edit(): View
    {
        $user = Auth::user();
        $stat = $user->o2Stat;
        $currentO2 = $stat ? $stat->total_o2_kg_per_bulan : 0.0;
        
        $nextBadge = \App\Models\Achievement::where('threshold_o2', '>', $currentO2)
            ->orderBy('threshold_o2', 'asc')
            ->first();
            
        $achievements = $user->achievements()->with('achievement')->get();

        return view('profile.edit', [
            'user' => $user,
            'stat' => $stat,
            'nextBadge' => $nextBadge,
            'achievements' => $achievements,
        ]);
    }

    /**
     * Update data profil (nama, email, phone).
     * Method: PATCH /profile
     * Validasi menggunakan UpdateProfileRequest.
     */
    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        $user->fill($validated);

        // Reset email_verified_at jika email berubah
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()
            ->route('profile.edit')
            ->with('success', __('Profile updated successfully.'));
    }

    /**
     * Update password pengguna.
     * Method: PUT /profile/password
     * Security: Validasi current_password sebelum update.
     */
    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        $request->user()->update([
            'password' => $request->validated('password'),
        ]);

        return redirect()
            ->route('profile.edit')
            ->with('success', __('Password changed successfully.'));
    }

    /**
     * Update preferensi (bahasa & notifikasi).
     * Method: PATCH /profile/preferences
     */
    public function updatePreferences(UpdatePreferencesRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        // Checkbox yang tidak dicentang tidak dikirim, jadi set default false
        $user->update([
            'locale' => $validated['locale'],
            'notif_email' => $request->boolean('notif_email'),
            'notif_push' => $request->boolean('notif_push'),
        ]);

        // Set locale session agar langsung berlaku
        session(['locale' => $validated['locale']]);
        app()->setLocale($validated['locale']);

        return redirect()
            ->route('profile.edit')
            ->with('success', __('Preferences updated successfully.'));
    }

    /**
     * Delete the user's account.
     */
    public function destroy(\Illuminate\Http\Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', __('Account deleted successfully.'));
    }
}