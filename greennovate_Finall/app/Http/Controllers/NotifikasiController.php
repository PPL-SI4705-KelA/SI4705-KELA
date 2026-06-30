<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    /**
     * Tampilkan halaman notifikasi user.
     * Tab: belum_dibaca / sudah_dibaca
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $tab  = $request->query('tab', 'belum_dibaca');

        $belumDibaca = Notifikasi::where('user_id', $user->id)
            ->belumDibaca()
            ->latest()
            ->get();

        $sudahDibaca = Notifikasi::where('user_id', $user->id)
            ->sudahDibaca()
            ->latest()
            ->limit(50)
            ->get();

        return view('notifikasi.index', compact('belumDibaca', 'sudahDibaca', 'tab'));
    }

    /**
     * PATCH /notifikasi/{id}/baca
     * Tandai satu notifikasi milik user yang login sebagai sudah dibaca.
     */
    public function markAsRead(int $id)
    {
        $user = Auth::user();

        $notifikasi = Notifikasi::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (! $notifikasi) {
            return response()->json([
                'message' => 'Notifikasi tidak ditemukan atau sudah dihapus.',
            ], 404);
        }

        $notifikasi->tandaiDibaca();

        if (request()->expectsJson()) {
            return response()->json([
                'message'       => 'Notifikasi berhasil ditandai sudah dibaca.',
                'unread_count'  => Notifikasi::where('user_id', $user->id)->belumDibaca()->count(),
            ]);
        }

        return redirect()->route('notifikasi.index')
            ->with('success', 'Notifikasi telah ditandai sebagai sudah dibaca.');
    }

    /**
     * PATCH /notifikasi/baca-semua
     * Tandai seluruh notifikasi belum dibaca milik user yang login.
     */
    public function markAllAsRead()
    {
        $user = Auth::user();

        Notifikasi::where('user_id', $user->id)
            ->belumDibaca()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        if (request()->expectsJson()) {
            return response()->json([
                'message'      => 'Semua notifikasi telah ditandai sebagai sudah dibaca.',
                'unread_count' => 0,
            ]);
        }

        return redirect()->route('notifikasi.index', ['tab' => 'sudah_dibaca'])
            ->with('success', 'Semua notifikasi telah ditandai sebagai sudah dibaca.');
    }
}
