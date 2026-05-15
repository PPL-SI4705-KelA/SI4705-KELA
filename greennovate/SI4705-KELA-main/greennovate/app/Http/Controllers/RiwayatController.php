<?php

namespace App\Http\Controllers;

use App\Helpers\RiwayatMapper;
use App\Models\Donasi;
use App\Models\Pembelian;
use App\Models\PendaftaranKegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RiwayatController extends Controller
{
    /**
     * Tampilkan daftar riwayat partisipasi pengguna.
     * Mengagregasi data dari tabel donasi, pembelian, dan pendaftaran kegiatan.
     *
     * GET /riwayat
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $filterTipe = $request->input('tipe');
        $filterStatus = $request->input('status');

        // ── Ambil data dari berbagai tabel ──────────────────────────────────

        $riwayatItems = collect();

        // 1. Donasi
        if (!$filterTipe || $filterTipe === 'donasi') {
            $donasis = Donasi::where('user_id', $user->id)->get();
            foreach ($donasis as $d) {
                $riwayatItems->push([
                    'id' => $d->id,
                    'tipe' => 'donasi',
                    'tipe_label' => RiwayatMapper::tipeLabel('donasi'),
                    'tipe_color' => RiwayatMapper::tipeColor('donasi'),
                    'nama' => $d->nama_donasi,
                    'tanggal' => $d->created_at,
                    'status_raw' => $d->status,
                    'status_label' => RiwayatMapper::statusMapper($d->status, 'donasi'),
                    'status_color' => RiwayatMapper::statusColor(RiwayatMapper::statusMapper($d->status, 'donasi')),
                    'kode' => $d->kode_transaksi,
                    'detail' => 'Rp ' . number_format((float)($d->jumlah ?? 0), 0, ',', '.'),
                ]);
            }
        }

        // 2. Pembelian
        if (!$filterTipe || $filterTipe === 'pembelian') {
            $pembelians = Pembelian::where('user_id', $user->id)->get();
            foreach ($pembelians as $p) {
                $riwayatItems->push([
                    'id' => $p->id,
                    'tipe' => 'pembelian',
                    'tipe_label' => RiwayatMapper::tipeLabel('pembelian'),
                    'tipe_color' => RiwayatMapper::tipeColor('pembelian'),
                    'nama' => $p->nama_item,
                    'tanggal' => $p->created_at,
                    'status_raw' => $p->status,
                    'status_label' => RiwayatMapper::statusMapper($p->status, 'pembelian'),
                    'status_color' => RiwayatMapper::statusColor(RiwayatMapper::statusMapper($p->status, 'pembelian')),
                    'kode' => $p->kode_transaksi,
                    'detail' => 'Rp ' . number_format((float)($p->total_harga ?? 0), 0, ',', '.'),
                ]);
            }
        }

        // 3. Kegiatan (Pendaftaran)
        if (!$filterTipe || $filterTipe === 'kegiatan') {
            $pendaftarans = PendaftaranKegiatan::where('user_id', $user->id)
                ->with('kegiatan')
                ->get();
            foreach ($pendaftarans as $pk) {
                $riwayatItems->push([
                    'id' => $pk->id,
                    'tipe' => 'kegiatan',
                    'tipe_label' => RiwayatMapper::tipeLabel('kegiatan'),
                    'tipe_color' => RiwayatMapper::tipeColor('kegiatan'),
                    'nama' => $pk->kegiatan->nama ?? 'Kegiatan Tidak Ditemukan',
                    'tanggal' => $pk->created_at,
                    'status_raw' => $pk->status,
                    'status_label' => RiwayatMapper::statusMapper($pk->status, 'kegiatan'),
                    'status_color' => RiwayatMapper::statusColor(RiwayatMapper::statusMapper($pk->status, 'kegiatan')),
                    'kode' => 'KGT-' . str_pad($pk->id, 6, '0', STR_PAD_LEFT),
                    'detail' => $pk->kegiatan->tanggal ? $pk->kegiatan->tanggal->translatedFormat('d F Y') : '-',
                ]);
            }
        }

        // ── Filter berdasarkan status ──────────────────────────────────────
        if ($filterStatus) {
            $riwayatItems = $riwayatItems->filter(function ($item) use ($filterStatus) {
                return $item['status_label'] === $filterStatus;
            });
        }

        // Sort by tanggal terbaru
        $riwayatItems = $riwayatItems->sortByDesc('tanggal')->values();

        // Manual pagination
        $perPage = 10;
        $page = $request->input('page', 1);
        $total = $riwayatItems->count();
        $items = $riwayatItems->forPage($page, $perPage);
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('riwayat.index', [
            'riwayatItems' => $paginator,
            'filterTipe' => $filterTipe,
            'filterStatus' => $filterStatus,
        ]);
    }

    /**
     * Tampilkan detail riwayat per item.
     *
     * GET /riwayat/{tipe}/{id}
     */
    public function detail(string $tipe, int $id)
    {
        $user = Auth::user();
        $data = null;

        switch ($tipe) {
            case 'donasi':
                $record = Donasi::where('id', $id)->where('user_id', $user->id)->first();
                if (!$record)
                    abort(404, 'Data riwayat tidak ditemukan.');

                $data = [
                    'id' => $record->id,
                    'tipe' => 'donasi',
                    'tipe_label' => 'Donasi',
                    'nama' => $record->nama_donasi,
                    'tanggal' => $record->created_at->translatedFormat('d F Y, H:i'),
                    'status_label' => RiwayatMapper::statusMapper($record->status, 'donasi'),
                    'status_color' => RiwayatMapper::statusColor(RiwayatMapper::statusMapper($record->status, 'donasi')),
                    'kode' => $record->kode_transaksi,
                    'jumlah' => 'Rp ' . number_format((float)($record->jumlah ?? 0), 0, ',', '.'),
                    'metode' => $record->metode_pembayaran ?? '-',
                    'catatan' => $record->catatan,
                    'has_qr' => false,
                    'qr_url' => null,
                    'has_dokumentasi' => $record->hasDokumentasi(),
                    'dokumentasi_url' => $record->hasDokumentasi()
                        ? route('riwayat.download', ['tipe' => 'donasi', 'id' => $record->id])
                        : null,
                    'has_sertifikat' => false,
                ];
                break;

            case 'pembelian':
                $record = Pembelian::where('id', $id)->where('user_id', $user->id)->first();
                if (!$record)
                    abort(404, 'Data riwayat tidak ditemukan.');

                $data = [
                    'id' => $record->id,
                    'tipe' => 'pembelian',
                    'tipe_label' => 'Pembelian',
                    'nama' => $record->nama_item,
                    'tanggal' => $record->created_at->translatedFormat('d F Y, H:i'),
                    'status_label' => RiwayatMapper::statusMapper($record->status, 'pembelian'),
                    'status_color' => RiwayatMapper::statusColor(RiwayatMapper::statusMapper($record->status, 'pembelian')),
                    'kode' => $record->kode_transaksi,
                    'jumlah' => 'Rp ' . number_format((float)($record->total_harga ?? 0), 0, ',', '.'),
                    'jumlah_item' => $record->jumlah_item,
                    'catatan' => $record->catatan,
                    'has_qr' => $record->hasQrCode(),
                    'qr_url' => $record->hasQrCode()
                        ? asset('storage/' . $record->qr_code)
                        : null,
                    'has_dokumentasi' => $record->hasDokumentasi(),
                    'dokumentasi_url' => $record->hasDokumentasi()
                        ? route('riwayat.download', ['tipe' => 'pembelian', 'id' => $record->id])
                        : null,
                    'has_sertifikat' => false,
                ];
                break;

            case 'kegiatan':
                $record = PendaftaranKegiatan::where('id', $id)
                    ->where('user_id', $user->id)
                    ->with('kegiatan.lokasiLahan')
                    ->first();
                if (!$record)
                    abort(404, 'Data riwayat tidak ditemukan.');

                $data = [
                    'id' => $record->id,
                    'tipe' => 'kegiatan',
                    'tipe_label' => 'Kegiatan',
                    'nama' => $record->kegiatan->nama ?? 'Kegiatan Tidak Ditemukan',
                    'tanggal' => $record->created_at->translatedFormat('d F Y, H:i'),
                    'tanggal_kegiatan' => $record->kegiatan->tanggal
                        ? $record->kegiatan->tanggal->translatedFormat('d F Y')
                        : '-',
                    'lokasi' => $record->kegiatan->lokasiLahan->nama ?? '-',
                    'status_label' => RiwayatMapper::statusMapper($record->status, 'kegiatan'),
                    'status_color' => RiwayatMapper::statusColor(RiwayatMapper::statusMapper($record->status, 'kegiatan')),
                    'kode' => 'KGT-' . str_pad($record->id, 6, '0', STR_PAD_LEFT),
                    'nama_lengkap' => $record->nama_lengkap,
                    'no_hp' => $record->no_hp,
                    'catatan' => $record->catatan,
                    'has_qr' => $record->hasQrCode(),
                    'qr_url' => $record->hasQrCode()
                        ? asset('storage/' . $record->qr_code)
                        : null,
                    'has_dokumentasi' => $record->hasDokumentasi(),
                    'dokumentasi_url' => $record->hasDokumentasi()
                        ? route('riwayat.download', ['tipe' => 'kegiatan', 'id' => $record->id])
                        : null,
                    'has_sertifikat' => $record->hasSertifikat(),
                    'sertifikat_url' => $record->hasSertifikat()
                        ? route('riwayat.download', ['tipe' => 'kegiatan', 'id' => $record->id, 'file' => 'sertifikat'])
                        : null,
                ];
                break;

            default:
                abort(404, 'Tipe riwayat tidak valid.');
        }

        return response()->json($data);
    }

    /**
     * Download dokumentasi/sertifikat secara aman.
     * Hanya pemilik transaksi yang dapat mengunduh.
     *
     * GET /riwayat/{tipe}/{id}/download
     */
    public function download(Request $request, string $tipe, int $id)
    {
        $user = Auth::user();
        $filePath = null;
        $fileName = 'dokumentasi';

        $fileType = $request->input('file', 'dokumentasi');

        switch ($tipe) {
            case 'donasi':
                $record = Donasi::where('id', $id)->where('user_id', $user->id)->first();
                if (!$record)
                    abort(403, 'Anda tidak memiliki akses ke file ini.');
                $filePath = $record->bukti_dokumentasi;
                $fileName = 'Dokumentasi_Donasi_' . $record->kode_transaksi;
                break;

            case 'pembelian':
                $record = Pembelian::where('id', $id)->where('user_id', $user->id)->first();
                if (!$record)
                    abort(403, 'Anda tidak memiliki akses ke file ini.');
                $filePath = $record->bukti_dokumentasi;
                $fileName = 'Dokumentasi_Pembelian_' . $record->kode_transaksi;
                break;

            case 'kegiatan':
                $record = PendaftaranKegiatan::where('id', $id)->where('user_id', $user->id)->first();
                if (!$record)
                    abort(403, 'Anda tidak memiliki akses ke file ini.');
                if ($fileType === 'sertifikat') {
                    $filePath = $record->sertifikat;
                    $fileName = 'Sertifikat_Kegiatan_' . str_pad($record->id, 6, '0', STR_PAD_LEFT);
                } else {
                    $filePath = $record->bukti_dokumentasi;
                    $fileName = 'Dokumentasi_Kegiatan_' . str_pad($record->id, 6, '0', STR_PAD_LEFT);
                }
                break;

            default:
                abort(404, 'Tipe riwayat tidak valid.');
        }

        // Validasi file exists
        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            return back()->with('error', 'File dokumentasi tidak ditemukan di server. Silakan hubungi admin untuk informasi lebih lanjut.');
        }

        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $downloadName = $fileName . '.' . $extension;

        return response()->download(Storage::disk('public')->path($filePath), $downloadName);
    }

    // ─── API Endpoints ──────────────────────────────────────────────────────

    /**
     * API: Daftar riwayat dengan pagination.
     *
     * GET /api/riwayat
     */
    public function apiIndex(Request $request)
    {
        $user = Auth::user();
        $filterTipe = $request->input('tipe');
        $riwayatItems = collect();

        if (!$filterTipe || $filterTipe === 'donasi') {
            $donasis = Donasi::where('user_id', $user->id)->get();
            foreach ($donasis as $d) {
                $riwayatItems->push([
                    'id' => $d->id,
                    'tipe' => 'donasi',
                    'nama' => $d->nama_donasi,
                    'tanggal' => $d->created_at->toIso8601String(),
                    'status' => RiwayatMapper::statusMapper($d->status, 'donasi'),
                    'kode' => $d->kode_transaksi,
                ]);
            }
        }

        if (!$filterTipe || $filterTipe === 'pembelian') {
            $pembelians = Pembelian::where('user_id', $user->id)->get();
            foreach ($pembelians as $p) {
                $riwayatItems->push([
                    'id' => $p->id,
                    'tipe' => 'pembelian',
                    'nama' => $p->nama_item,
                    'tanggal' => $p->created_at->toIso8601String(),
                    'status' => RiwayatMapper::statusMapper($p->status, 'pembelian'),
                    'kode' => $p->kode_transaksi,
                ]);
            }
        }

        if (!$filterTipe || $filterTipe === 'kegiatan') {
            $pendaftarans = PendaftaranKegiatan::where('user_id', $user->id)
                ->with('kegiatan')->get();
            foreach ($pendaftarans as $pk) {
                $riwayatItems->push([
                    'id' => $pk->id,
                    'tipe' => 'kegiatan',
                    'nama' => $pk->kegiatan->nama ?? '-',
                    'tanggal' => $pk->created_at->toIso8601String(),
                    'status' => RiwayatMapper::statusMapper($pk->status, 'kegiatan'),
                    'kode' => 'KGT-' . str_pad($pk->id, 6, '0', STR_PAD_LEFT),
                ]);
            }
        }

        $sorted = $riwayatItems->sortByDesc('tanggal')->values();

        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $sorted->forPage($page, $perPage)->values(),
            $sorted->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return response()->json($paginator);
    }

    /**
     * API: Detail riwayat per item.
     *
     * GET /api/riwayat/{tipe}/{id}/detail
     */
    public function apiDetail(string $tipe, int $id)
    {
        // Reuse the detail method – returns JSON already
        return $this->detail($tipe, $id);
    }
}
