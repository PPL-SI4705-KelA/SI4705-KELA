<?php

namespace App\Http\Controllers;

use App\Models\Donasi;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BuktiPembayaranController extends Controller
{
    /**
     * Menampilkan instruksi pembayaran
     */
    public function show(string $tipe, int $id)
    {
        $user = Auth::user();
        $record = $this->getRecord($tipe, $id, $user->id);

        if (!$record) {
            abort(403, 'Anda tidak memiliki akses ke transaksi ini.');
        }

        // Siapkan data untuk view
        $data = [
            'id' => $record->id,
            'tipe' => $tipe,
            'status' => $record->status,
            'nomor_rekening' => 'BCA 1234567890 a.n. Greennovate Foundation', // Contoh statis
        ];

        if ($tipe === 'donasi') {
            $data['nama_item'] = $record->nama_donasi;
            $data['nominal'] = $record->jumlah;
        } else {
            $data['nama_item'] = $record->nama_item;
            $data['nominal'] = $record->total_harga;
        }

        return view('pembayaran.instruksi', $data);
    }

    /**
     * Mengunggah bukti pembayaran pertama kali
     */
    public function upload(Request $request, string $tipe, int $id)
    {
        $user = Auth::user();
        $record = $this->getRecord($tipe, $id, $user->id);

        if (!$record) {
            abort(403, 'Anda tidak memiliki akses ke transaksi ini.');
        }

        if (!in_array($record->status, ['Pending', 'Ditolak'])) {
            return back()->with('error', 'Status transaksi saat ini tidak mengizinkan unggah bukti transfer.');
        }

        $request->validate([
            'bukti_transfer' => 'required|mimes:jpg,jpeg,png|max:2048'
        ], [
            'bukti_transfer.required' => 'Bukti transfer wajib diunggah.',
            'bukti_transfer.mimes' => 'Format file tidak valid. Hanya JPG, JPEG, dan PNG yang diizinkan.',
            'bukti_transfer.max' => 'Ukuran file maksimal 2MB.',
        ]);

        try {
            $file = $request->file('bukti_transfer');
            $path = $file->store("bukti_{$tipe}", 'public');

            // Hapus file lama jika ada (terutama untuk kasus re-upload)
            if ($record->bukti_dokumentasi && Storage::disk('public')->exists($record->bukti_dokumentasi)) {
                Storage::disk('public')->delete($record->bukti_dokumentasi);
            }

            $record->bukti_dokumentasi = $path;
            $record->status = 'Menunggu Konfirmasi';
            $record->save();

            return back()->with('success', 'Bukti transfer berhasil dikirim. Silakan tunggu verifikasi dari Admin.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengunggah file. Silakan coba lagi.');
        }
    }

    /**
     * Mengunggah ulang bukti pembayaran jika ditolak
     */
    public function reupload(Request $request, string $tipe, int $id)
    {
        // Re-upload logic is identical to upload since we check for both 'Pending' and 'Ditolak'
        return $this->upload($request, $tipe, $id);
    }

    /**
     * Helper: Ambil record transaksi berdasarkan tipe dan user
     */
    private function getRecord(string $tipe, int $id, int $userId)
    {
        if ($tipe === 'donasi') {
            return Donasi::where('id', $id)->where('user_id', $userId)->first();
        } elseif ($tipe === 'pembelian') {
            return Pembelian::where('id', $id)->where('user_id', $userId)->first();
        }

        abort(404, 'Tipe transaksi tidak valid.');
    }
}
