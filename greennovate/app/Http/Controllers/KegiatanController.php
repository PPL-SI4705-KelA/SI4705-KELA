<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\PendaftaranKegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KegiatanController extends Controller
{
    /**
     * Tampilkan daftar kegiatan yang tersedia.
     */
    public function index()
    {
        $kegiatan = Kegiatan::orderBy('tanggal', 'asc')->get();

        return view('kegiatan.index', compact('kegiatan'));
    }

    /**
     * Tampilkan detail satu kegiatan berdasarkan slug.
     */
    public function show(string $slug)
    {
        $kegiatan = Kegiatan::where('slug', $slug)->first();

        if (!$kegiatan) {
            abort(404);
        }

        return view('kegiatan.show', compact('kegiatan'));
    }

    /**
     * Tampilkan form pendaftaran kegiatan.
     */
    public function showDaftarForm(string $slug)
    {
        $kegiatan = Kegiatan::where('slug', $slug)->first();

        if (!$kegiatan) {
            abort(404);
        }

        if (!$kegiatan->isRegistrationOpen()) {
            return redirect()
                ->route('kegiatan.show', $slug)
                ->with('error', 'Pendaftaran tidak tersedia: ' . $kegiatan->registration_disabled_reason);
        }

        $user = Auth::user();

        return view('kegiatan.daftar', compact('kegiatan', 'user'));
    }

    /**
     * Proses pendaftaran kegiatan (submit form).
     */
    public function daftar(Request $request, string $slug)
    {
        $kegiatan = Kegiatan::where('slug', $slug)->first();

        if (!$kegiatan) {
            abort(404);
        }

        if (!$kegiatan->isRegistrationOpen()) {
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

        // Simpan data pendaftaran ke tabel riwayat
        PendaftaranKegiatan::create([
            'user_id'      => Auth::id(),
            'kegiatan_id'  => $kegiatan->id,
            'nama_lengkap' => $request->nama_lengkap,
            'no_hp'        => $request->no_hp,
            'alamat'       => $request->alamat,
            'status'       => 'Menunggu', // Default status
        ]);

        $kegiatan->increment('registered_count');

        return redirect()
            ->route('kegiatan.show', $slug)
            ->with('success', 'Pendaftaran berhasil! Anda telah terdaftar untuk kegiatan "' . $kegiatan->nama . '". Silakan cek menu Riwayat Partisipasi untuk melihat status pendaftaran Anda.');
    }
}