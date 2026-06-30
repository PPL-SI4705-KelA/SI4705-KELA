<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminNotifikasiController extends Controller
{
    /**
     * GET /admin/notifikasi
     * Log seluruh notifikasi semua user untuk admin.
     */
    public function index(Request $request)
    {
        $tipe       = $request->query('tipe');
        $status     = $request->query('status'); // 'belum' | 'sudah'
        $userId     = $request->query('user_id');

        $query = Notifikasi::with('user')->latest();

        if ($tipe) {
            $query->where('tipe', $tipe);
        }

        if ($status === 'belum') {
            $query->belumDibaca();
        } elseif ($status === 'sudah') {
            $query->sudahDibaca();
        }

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $notifikasis = $query->paginate(25)->withQueryString();
        $users       = User::orderBy('name')->get(['id', 'name', 'email']);

        // Summary stats
        $totalBelum = Notifikasi::belumDibaca()->count();
        $totalSemua = Notifikasi::count();

        return view('admin.notifikasi.index', compact(
            'notifikasis',
            'users',
            'totalBelum',
            'totalSemua',
            'tipe',
            'status',
            'userId',
        ));
    }

    /**
     * GET /admin/inbox
     * Notifikasi pribadi admin yang sedang login.
     */
    public function inbox(Request $request)
    {
        $admin = Auth::user();
        $tab   = $request->query('tab', 'belum_dibaca');

        $belumDibaca = Notifikasi::where('user_id', $admin->id)
            ->belumDibaca()
            ->latest()
            ->get();

        $sudahDibaca = Notifikasi::where('user_id', $admin->id)
            ->sudahDibaca()
            ->latest()
            ->limit(50)
            ->get();

        $unreadCount = $belumDibaca->count();

        return view('admin.notifikasi.inbox', compact('belumDibaca', 'sudahDibaca', 'tab', 'unreadCount'));
    }

    /**
     * PATCH /admin/inbox/{id}/baca
     * Tandai satu notifikasi pribadi admin sebagai sudah dibaca.
     */
    public function markAsRead(int $id)
    {
        $admin = Auth::user();

        $notifikasi = Notifikasi::where('id', $id)
            ->where('user_id', $admin->id)
            ->first();

        if (! $notifikasi) {
            return response()->json(['message' => 'Notifikasi tidak ditemukan.'], 404);
        }

        $notifikasi->tandaiDibaca();

        if (request()->expectsJson()) {
            return response()->json([
                'message'      => 'Notifikasi berhasil ditandai sudah dibaca.',
                'unread_count' => Notifikasi::where('user_id', $admin->id)->belumDibaca()->count(),
            ]);
        }

        return redirect()->route('admin.inbox')->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    /**
     * PATCH /admin/inbox/baca-semua
     * Tandai semua notifikasi pribadi admin sebagai sudah dibaca.
     */
    public function markAllAsRead()
    {
        $admin = Auth::user();

        Notifikasi::where('user_id', $admin->id)
            ->belumDibaca()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        if (request()->expectsJson()) {
            return response()->json([
                'message'      => 'Semua notifikasi telah ditandai sudah dibaca.',
                'unread_count' => 0,
            ]);
        }

        return redirect()->route('admin.inbox', ['tab' => 'sudah_dibaca'])
            ->with('success', 'Semua notifikasi telah ditandai sudah dibaca.');
    }
}
