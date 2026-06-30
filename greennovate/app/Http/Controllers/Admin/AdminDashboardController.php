<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\LokasiLahan;
use App\Models\User;
use App\Models\Donasi;
use App\Models\Pembelian;
use App\Models\Realisasi;
use App\Models\Message;
use App\Models\JenisPohon;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{

    public function index()
    {
        $stats = [
            'total_users'      => User::where('role', 'user')->count(),
            'total_petugas'    => User::where('role', 'petugas')->count(),
            // 'total_admin'      => User::where('role', 'admin')->count(),
            // 'total_aktif'      => User::where('is_active', 'true')->count(),
            // 'total_nonaktif'   => User::where('is_active', 'false')->count(),
            'total_kegiatan'   => Kegiatan::where('status', 'Berlangsung')->count(),
            'total_lokasi'     => LokasiLahan::count(),
            'total_jenis_pohon'=> JenisPohon::count(),
            
            // Keuangan & Validasi Donasi
            'total_donasi_sukses'   => Donasi::where('status', 'sukses')->sum('jumlah'),
            'donasi_pending_count'  => Donasi::whereIn('status', ['pending', 'menunggu_verifikasi'])->count(),
            
            // Keuangan & Validasi Pembelian
            'total_pembelian_sukses'  => Pembelian::where('status', 'Sukses')->sum('total_harga'),
            'total_pohon_terbeli'     => Pembelian::where('status', 'Sukses')->sum('jumlah_item'),
            'pembelian_pending_count' => Pembelian::where('status', 'Pending')->count(),

            // Dampak Penanaman (Realisasi oleh petugas)
            'total_pohon_ditanam'     => Realisasi::sum('jumlah'),

            // Chat belum dibaca dari user
            'unread_chat_count'       => Message::whereRaw('is_read = false')
                                            ->whereHas('sender', function($q) {
                                                $q->where('role', 'user');
                                            })->count(),

            'pengguna_terbaru' => User::where('role', '!=', 'admin')
                                      ->latest()
                                      ->take(5)
                                      ->get(),
        ];

        $users = User::where('role', 'user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'users'));
    }
}