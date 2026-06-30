<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Http\Request;

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
}
