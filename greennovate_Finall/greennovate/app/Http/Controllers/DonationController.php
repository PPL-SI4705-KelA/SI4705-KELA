<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Donasi;
use App\Http\Requests\StoreDonasiRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DonationController extends Controller
{
    /**
     * Menampilkan halaman donasi
     */
    public function index()
    {

        $daftarKegiatan = Kegiatan::where('status', 'Berlangsung')->get();

        return view('donations.index', compact('daftarKegiatan'));
    }

    /**
     * Validasi pertama
     */
    public function prosesValidasiPertama(StoreDonasiRequest $request)
    {
        session([
            'temporary_donation_data' => $request->validated()
        ]);

        return redirect()->route('donations.confirmation');
    }

    /**
     * Halaman konfirmasi donasi
     */
    public function confirmation()
    {
        $data = session('temporary_donation_data');

        if (!$data) {
            return redirect()
                ->route('donations.index')
                ->with('error', 'Silakan isi form donasi terlebih dahulu.');
        }

        $kegiatan = Kegiatan::findOrFail($data['kegiatan_id']);

        return view(
            'donations.confirmation',
            compact('data', 'kegiatan')
        );
    }

    /**
     * Validasi kedua dan pembuatan transaksi
     */
    public function lanjutPembayaran()
    {
        $data = session('temporary_donation_data');

        if (!$data) {
            return redirect()
                ->route('donations.index')
                ->with('error', 'Sesi donasi telah berakhir.');
        }

        try {

            return DB::transaction(function () use ($data) {

                $kegiatan = Kegiatan::lockForUpdate()
                    ->findOrFail($data['kegiatan_id']);

                // duplicate check (FIX: jangan pakai nama_donasi)
                $existingPending = Donasi::where('user_id', auth()->id())
                    ->where('kegiatan_id', $kegiatan->id)
                    ->where('jumlah', $data['jumlah'])
                    ->where('status', 'pending')
                    ->where('created_at', '>=', now()->subMinutes(2))
                    ->first();

                if ($existingPending) {
                    return redirect()
                        ->route('donations.payment', $existingPending->id)
                        ->with('warning', 'Transaksi serupa sedang diproses.');
                }

                $donasi = Donasi::create([
                    'user_id' => auth()->id(),
                    'kegiatan_id' => $kegiatan->id,

                    // FIX: pakai kegiatan sebagai nama donasi
                    'nama_donasi' => $kegiatan->nama,

                    'jumlah' => $data['jumlah'],

                    'metode_pembayaran' => 'Transfer Bank BCA',

                    // FIX: sesuai ENUM DB kamu
                    'status' => 'pending',

                    'kode_transaksi' =>
                        'DNS-' . strtoupper(Str::random(8)) . time(),

                    'catatan' => $data['catatan'] ?? null,
                ]);

                session()->forget('temporary_donation_data');

                return redirect()->route('donations.payment', $donasi->id);
            });

        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Halaman pembayaran dummy
     */
    public function payment(Donasi $donasi)
    {
        if ($donasi->user_id !== auth()->id()) {
            abort(403);
        }

        /**
         * Expired otomatis setelah 10 menit
         */
        if (
            $donasi->status === 'pending' &&
            $donasi->created_at->addMinutes(10)->isPast()
        ) {
            $donasi->update([
                'status' => 'expired'
            ]);
        }

        return view(
            'donations.payment',
            compact('donasi')
        );
    }

    /**
     * Upload bukti pembayaran
     */
    public function uploadBuktiPembayaran(
        Request $request,
        Donasi $donasi
    ) {

        if ($donasi->user_id !== auth()->id()) {
            abort(403);
        }

        if ($donasi->status !== 'pending') {

            return back()->with(
                'error',
                'Status transaksi tidak valid.'
            );
        }

        /**
         * Cek expired 10 menit
         */
        if (
            $donasi->created_at->addMinutes(10)->isPast()
        ) {

            $donasi->update([
                'status' => 'expired'
            ]);

            return back()->with(
                'error',
                'Transaksi telah kedaluwarsa.'
            );
        }

        $request->validate([
            'bukti_pembayaran' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048'
            ]
        ]);

        try {

            $path = $request
                ->file('bukti_pembayaran')
                ->store(
                    'bukti-donasi',
                    'public'
                );

            $donasi->update([
                'bukti_dokumentasi' => $path,
                'status' => 'menunggu_verifikasi'
            ]);

            return redirect()
                ->route(
                    'riwayat.index'
                )
                ->with(
                    'success',
                    'Bukti pembayaran berhasil dikirim.'
                );

        } catch (\Exception $e) {

            return back()->with(
                'error',
                'Upload bukti pembayaran gagal. Silakan coba kembali.'
            );
        }
    }

    /**
     * Optional scheduler/command
     * Mengubah transaksi menjadi expired
     */
    public function expirePendingTransactions()
    {
        Donasi::where(
                'status',
                'pending'
            )
            ->where(
                'created_at',
                '<=',
                now()->subMinutes(10)
            )
            ->update([
                'status' => 'expired'
            ]);
    }
}