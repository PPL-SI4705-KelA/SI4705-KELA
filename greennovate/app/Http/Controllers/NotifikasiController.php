<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    /**
     * GET /notifikasi — Halaman daftar notifikasi
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $tab  = $request->get('tab', 'belum_dibaca');

        $belumDibaca = Notifikasi::where('user_id', $user->id)
            ->where('is_read', 'false') // Ubah false menjadi string 'false'
            ->orderBy('created_at', 'desc')
            ->get();

        $sudahDibaca = Notifikasi::where('user_id', $user->id)
            ->where('is_read', 'true') // Ubah true menjadi string 'true'
            ->orderBy('created_at', 'desc')
            ->get();

        return view('notifikasi.index', compact('belumDibaca', 'sudahDibaca', 'tab'));
    }

    /**
     * PATCH /notifikasi/{id}/baca — Tandai satu notifikasi sebagai dibaca
     */
    public function tandaiBaca(int $id)
    {
        $notif = Notifikasi::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (!$notif->is_read) {
            $notif->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('notifikasi.index')
            ->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    /**
     * PATCH /notifikasi/baca-semua — Tandai semua notifikasi sebagai dibaca
     */
    public function tandaiBacaSemua()
    {
        Notifikasi::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Semua notifikasi telah ditandai sebagai sudah dibaca.']);
        }

        return redirect()->route('notifikasi.index')
            ->with('success', 'Semua notifikasi telah ditandai sebagai sudah dibaca.');
    }

    /**
     * GET /api/notifikasi/unread-count — Badge counter untuk navbar
     */
    public function unreadCount()
    {
        $count = Notifikasi::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }
}
