<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
<<<<<<< HEAD
use App\Models\LokasiLahan;
use App\Models\PendaftaranKegiatan;
=======
>>>>>>> parent of 5b93d16 (Merge yuka_branch (Participation History) into main)
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class KegiatanController extends Controller
{
    /**
     * Tampilkan daftar kegiatan dengan filter, pagination, dan caching (FR-04 / GN-26).
     */
    public function index(Request $request)
    {
        $lokasiList = Cache::remember('lokasi_lahan_list', 300, fn () => LokasiLahan::orderBy('nama')->get());

        $query = Kegiatan::with(['lokasiLahan', 'petugas'])
            ->orderBy('tanggal', 'asc');

        // ── Filter lokasi ──────────────────────────────────────────────────
        if ($request->filled('lokasi')) {
            $query->where('lokasi_lahan_id', $request->lokasi);
        }

        // ── Filter status ──────────────────────────────────────────────────
        $validStatus = ['Persiapan', 'Berlangsung', 'Selesai', 'Dibatalkan'];
        if ($request->filled('status') && in_array($request->status, $validStatus)) {
            $query->where('status', $request->status);
        }

        // ── Filter bulan ───────────────────────────────────────────────────
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }

        // ── Caching: hanya untuk halaman default (tanpa filter aktif) ─────
        $hasFilter = $request->hasAny(['lokasi', 'status', 'bulan']);
        $page      = $request->get('page', 1);

        if (! $hasFilter) {
            $kegiatan = Cache::remember("kegiatan_index_page_{$page}", 300, fn () => $query->paginate(9));
        } else {
            $kegiatan = $query->paginate(9)->withQueryString();
        }

        return view('kegiatan.index', compact('kegiatan', 'lokasiList'));
    }

    /**
     * Tampilkan detail satu kegiatan berdasarkan slug.
     */
    public function show(string $slug)
    {
        $kegiatan = Cache::remember("kegiatan_show_{$slug}", 300, function () use ($slug) {
            return Kegiatan::with(['lokasiLahan', 'petugas'])
                ->where('slug', $slug)
                ->first();
        });

        if (! $kegiatan) {
            abort(404);
        }

        return view('kegiatan.show', compact('kegiatan'));
    }

    /**
     * Tampilkan form pendaftaran kegiatan.
     */
    public function showDaftarForm(string $slug)
    {
        $kegiatan = Kegiatan::with(['lokasiLahan'])->where('slug', $slug)->first();

        if (! $kegiatan) {
            abort(404);
        }

        if (! $kegiatan->isRegistrationOpen()) {
            return redirect()
                ->route('kegiatan.show', $slug)
                ->with('error', 'Pendaftaran tidak tersedia: ' . $kegiatan->registration_disabled_reason);
        }

        $user = Auth::user();

        return view('kegiatan.daftar', compact('kegiatan', 'user'));
    }

    /**
     * Proses pendaftaran kegiatan (submit form).
     * Menggabungkan: simpan ke PendaftaranKegiatan (HEAD) + increment registered_count (Alvin_Branch).
     */
    public function daftar(Request $request, string $slug)
    {
        $kegiatan = Kegiatan::where('slug', $slug)->first();

        if (! $kegiatan) {
            abort(404);
        }

        if (! $kegiatan->isRegistrationOpen()) {
            return redirect()
                ->route('kegiatan.show', $slug)
                ->with('error', 'Pendaftaran untuk kegiatan ini tidak tersedia.');
        }

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'no_hp'        => 'required|string|max:20',
            'alamat'       => 'required|string|max:500',
            'pernyataan'   => 'accepted',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'no_hp.required'        => 'Nomor HP wajib diisi.',
            'alamat.required'       => 'Alamat wajib diisi.',
            'pernyataan.accepted'   => 'Anda harus menyetujui ketentuan yang berlaku.',
        ]);

<<<<<<< HEAD
        // Simpan data pendaftaran ke tabel riwayat (dari HEAD)
        PendaftaranKegiatan::create([
            'user_id'      => Auth::id(),
            'kegiatan_id'  => $kegiatan->id,
            'nama_lengkap' => $request->nama_lengkap,
            'no_hp'        => $request->no_hp,
            'alamat'       => $request->alamat,
            'status'       => 'Menunggu',
        ]);

        // Update counter kuota (dari Alvin_Branch)
=======
>>>>>>> parent of 5b93d16 (Merge yuka_branch (Participation History) into main)
        $kegiatan->increment('registered_count');

        // Invalidate cache detail kegiatan ini
        Cache::forget("kegiatan_show_{$slug}");

        return redirect()
            ->route('kegiatan.show', $slug)
            ->with('success', 'Pendaftaran berhasil! Anda telah terdaftar untuk kegiatan "' . $kegiatan->nama . '".');
    }
}