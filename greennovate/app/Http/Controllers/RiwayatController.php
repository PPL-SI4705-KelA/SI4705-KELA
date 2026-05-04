<?php

namespace App\Http\Controllers;

use App\Models\Donasi;
use App\Models\Pembelian;
use App\Models\PendaftaranKegiatan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RiwayatController extends Controller
{
    /**
     * Halaman daftar riwayat partisipasi user.
     * Menampilkan gabungan data dari 3 tabel: pendaftaran_kegiatan, donasi, pembelian.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $type = $request->query('type');
        $status = $request->query('status');

        $items = collect();

        // ── Ambil data pendaftaran kegiatan ─────────────────────────────────
        if (!$type || $type === 'kegiatan') {
            $kegiatanQuery = PendaftaranKegiatan::with('kegiatan')
                ->where('user_id', $user->id);

            if ($status) {
                $kegiatanQuery->where('status', $status);
            }

            $kegiatanItems = $kegiatanQuery->get()->map(function ($item) {
                return $this->mapToUnified('kegiatan', $item);
            });

            $items = $items->merge($kegiatanItems);
        }

        // ── Ambil data donasi ───────────────────────────────────────────────
        if (!$type || $type === 'donasi') {
            $donasiQuery = Donasi::where('user_id', $user->id);

            if ($status) {
                $donasiQuery->where('status', $status);
            }

            $donasiItems = $donasiQuery->get()->map(function ($item) {
                return $this->mapToUnified('donasi', $item);
            });

            $items = $items->merge($donasiItems);
        }

        // ── Ambil data pembelian ────────────────────────────────────────────
        if (!$type || $type === 'pembelian') {
            $pembelianQuery = Pembelian::where('user_id', $user->id);

            if ($status) {
                $pembelianQuery->where('status', $status);
            }

            $pembelianItems = $pembelianQuery->get()->map(function ($item) {
                return $this->mapToUnified('pembelian', $item);
            });

            $items = $items->merge($pembelianItems);
        }

        // ── Sort by tanggal terbaru ─────────────────────────────────────────
        $items = $items->sortByDesc('tanggal')->values();

        // ── Pagination manual (15 per halaman) ──────────────────────────────
        $page = $request->input('page', 1);
        $perPage = 15;
        $total = $items->count();
        $pagedItems = $items->slice(($page - 1) * $perPage, $perPage)->values();

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $pagedItems,
            $total,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // ── Return JSON jika diminta ────────────────────────────────────────
        if ($request->wantsJson() || $request->expectsJson()) {
            return response()->json([
                'data'  => $pagedItems,
                'total' => $total,
                'page'  => (int) $page,
                'per_page' => $perPage,
            ]);
        }

        return view('riwayat.index', [
            'items'      => $paginator,
            'activeType' => $type,
        ]);
    }

    /**
     * Halaman detail riwayat partisipasi.
     */
    public function detail(Request $request, string $type, int $id)
    {
        if (!in_array($type, ['donasi', 'pembelian', 'kegiatan'])) {
            abort(404);
        }

        $item = $this->findRecord($type, $id);

        if (!$item) {
            abort(404);
        }

        // Ownership check
        if ($item->user_id !== Auth::id()) {
            abort(403);
        }

        // Eager load relasi untuk kegiatan
        if ($type === 'kegiatan') {
            $item->load('kegiatan.lokasiLahan');
        }

        // Return JSON jika diminta
        if ($request->wantsJson() || $request->expectsJson()) {
            return response()->json([
                'type' => $type,
                'data' => $item,
            ]);
        }

        return view('riwayat.detail', [
            'type' => $type,
            'item' => $item,
        ]);
    }

    /**
     * Download file dokumentasi.
     */
    public function downloadDokumentasi(string $type, int $id)
    {
        if (!in_array($type, ['donasi', 'pembelian', 'kegiatan'])) {
            abort(404);
        }

        $item = $this->findRecord($type, $id);

        if (!$item) {
            abort(404);
        }

        // Ownership check
        if ($item->user_id !== Auth::id()) {
            abort(403);
        }

        $path = $item->dokumentasi;

        if (!$path) {
            return redirect()->back()->with('error', __('File dokumentasi tidak ditemukan. Silakan hubungi admin.'));
        }

        if (!Storage::exists($path)) {
            return redirect()->back()->with('error', __('File dokumentasi tidak ditemukan. Silakan hubungi admin.'));
        }

        // Friendly file name
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $friendlyName = 'dokumentasi-' . $type . '-' . $id . '.' . $extension;

        return Storage::download($path, $friendlyName);
    }

    // ── Private Helpers ─────────────────────────────────────────────────────

    /**
     * Cari record berdasarkan tipe.
     */
    private function findRecord(string $type, int $id)
    {
        return match ($type) {
            'kegiatan'  => PendaftaranKegiatan::find($id),
            'donasi'    => Donasi::find($id),
            'pembelian' => Pembelian::find($id),
            default     => null,
        };
    }

    /**
     * Map status ke label dan warna.
     */
    private function statusMapper(string $model, string $status): array
    {
        return match ($model) {
            'kegiatan' => match ($status) {
                'Dikonfirmasi' => ['label' => 'Dikonfirmasi', 'color' => 'blue'],
                'Selesai'      => ['label' => 'Selesai',      'color' => 'green'],
                'Ditolak'      => ['label' => 'Ditolak',      'color' => 'red'],
                default        => ['label' => 'Menunggu',     'color' => 'yellow'],
            },
            'donasi', 'pembelian' => match ($status) {
                'Sukses'     => ['label' => 'Sukses',     'color' => 'green'],
                'Gagal'      => ['label' => 'Gagal',      'color' => 'red'],
                'Kadaluarsa' => ['label' => 'Kadaluarsa', 'color' => 'gray'],
                'Dikirim'    => ['label' => 'Dikirim',    'color' => 'blue'],
                'Dibatalkan' => ['label' => 'Dibatalkan', 'color' => 'red'],
                'Selesai'    => ['label' => 'Selesai',    'color' => 'gray'],
                default      => ['label' => 'Pending',    'color' => 'yellow'],
            },
            default => ['label' => $status, 'color' => 'gray'],
        };
    }

    /**
     * Map item ke format seragam.
     */
    private function mapToUnified(string $type, mixed $item): array
    {
        $statusInfo = $this->statusMapper($type, $item->status);

        $nama = match ($type) {
            'kegiatan'  => $item->kegiatan?->nama ?? $item->nama_lengkap,
            'donasi'    => 'Donasi ' . ($item->formatted_jumlah ?? 'Rp ' . number_format($item->jumlah, 0, ',', '.')),
            'pembelian' => $item->nama_produk,
            default     => '-',
        };

        $tanggal = match ($type) {
            'kegiatan'  => $item->kegiatan?->tanggal ?? $item->created_at,
            default     => $item->created_at,
        };

        $hasQr = match ($type) {
            'kegiatan'  => $item->hasQrCode(),
            'pembelian' => $item->hasQrCode(),
            default     => false,
        };

        $hasDokumen = match ($type) {
            'kegiatan'  => $item->hasDokumentasi(),
            'pembelian' => $item->hasDokumentasi(),
            default     => false,
        };

        return [
            'id'          => $item->id,
            'type'        => $type,
            'nama'        => $nama,
            'tanggal'     => $tanggal instanceof Carbon ? $tanggal : Carbon::parse($tanggal),
            'status'      => $statusInfo,
            'has_qr'      => $hasQr,
            'has_dokumen' => $hasDokumen,
            'raw'         => $item,
        ];
    }
}
