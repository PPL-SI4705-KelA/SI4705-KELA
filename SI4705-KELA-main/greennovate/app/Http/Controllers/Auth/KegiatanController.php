<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class KegiatanController extends Controller
{
    /**
     * PBI-52: Controller listing dengan pagination
     * PBI-54: Menggunakan caching (GN-26) agar query lambat tidak membebani DB
     */
    public function index(Request $request)
    {
        $lokasi = $request->input('lokasi');
        $tanggal = $request->input('tanggal');
        $status = $request->input('status');

        // Jika ada filter aktif → lewati cache agar hasil selalu segar
        $hasFilter = $lokasi || $tanggal || $status;

        if ($hasFilter) {
            $kegiatan = $this->getFiltered($lokasi, $tanggal, $status);
        } else {
            // GN-26: Cache halaman pertama tanpa filter selama 5 menit
            $page = $request->input('page', 1);
            $cacheKey = "kegiatan_list_page_{$page}";

            $kegiatan = Cache::remember($cacheKey, now()->addMinutes(5), function () {
                return $this->getFiltered();
            });
        }

        // Daftar lokasi unik untuk opsi dropdown filter
        $lokasiList = Cache::remember('kegiatan_lokasi_list', now()->addMinutes(10), function () {
            return Kegiatan::select('lokasi')->distinct()->orderBy('lokasi')->pluck('lokasi');
        });

        return view('kegiatan.index', compact('kegiatan', 'lokasiList'));
    }

    /**
     * Bangun query dengan filter + pagination.
     */
    private function getFiltered(?string $lokasi = null, ?string $tanggal = null, ?string $status = null)
    {
        return Kegiatan::byLokasi($lokasi)
            ->byTanggal($tanggal)
            ->byStatus($status)
            ->orderBy('tanggal', 'asc')
            ->paginate(9)
            ->withQueryString();
    }
}
