<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Donasi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class SertifikatController extends Controller
{
    /**
     * Generate dan download sertifikat PDF
     */
    public function generateSertifikat($id)
    {
        try {
            $donasi = Donasi::with(['user.o2Stat', 'kegiatan.lokasiLahan'])->findOrFail($id);

            // Validasi kepemilikan (Access Control)
            if (auth()->id() !== $donasi->user_id) {
                return abort(403, 'Anda tidak memiliki akses ke data ini');
            }

            // Validasi status donasi
            if (!$donasi->isSuccess()) {
                return redirect()->back()->with('error', 'Sertifikat tersedia setelah donasi berhasil diverifikasi');
            }

            // Validasi dokumentasi
            if (!$donasi->hasDokumentasi()) {
                return redirect()->back()->with('error', 'Sertifikat akan tersedia setelah petugas mengupload dokumentasi penanaman');
            }

            // Generate nomor sertifikat unik: CERT-{tahun}-{id}
            $tahun = $donasi->created_at->format('Y');
            $nomorSertifikat = "CERT-{$tahun}-{$donasi->id}";

            // Hitung jumlah pohon dari transaksi ini
            $jumlahPohonTrx = 0;
            if ($donasi->kegiatan && $donasi->kegiatan->tipe_kegiatan === 'tanam_pohon') {
                $targetDana = $donasi->kegiatan->target_dana ?: 1;
                $estimasi = $donasi->kegiatan->estimasi_pohon ?: 0;
                $jumlahPohonTrx = floor(($donasi->jumlah / $targetDana) * $estimasi);
            } elseif ($donasi->kegiatan && $donasi->kegiatan->tipe_kegiatan === 'beli_pohon') {
                $jumlahPohonTrx = floor(($donasi->jumlah / ($donasi->kegiatan->harga ?? 1)) * 1);
            }

            // Total keseluruhan O2 user dari PB-26
            $totalO2 = $donasi->user->o2Stat ? $donasi->user->o2Stat->total_o2_kg_per_bulan : 0;
            $totalPohonSeluruhnya = $donasi->user->o2Stat ? $donasi->user->o2Stat->total_pohon : 0;
            
            // O2 khusus transaksi ini
            $o2Trx = $jumlahPohonTrx * 8.3;

            $data = [
                'nomor_sertifikat'  => $nomorSertifikat,
                'nama_penyumbang'   => $donasi->user->name,
                'nama_kegiatan'     => $donasi->kegiatan->nama ?? 'Penanaman Pohon',
                'lokasi'            => $donasi->kegiatan->lokasiLahan->nama ?? 'Lokasi Penghijauan',
                'tanggal_penanaman' => $donasi->kegiatan->tanggal ?? $donasi->created_at->format('Y-m-d'),
                'jumlah_pohon'      => $jumlahPohonTrx,
                'o2_trx'            => $o2Trx,
                'total_o2_user'     => $totalO2,
                'tanggal_terbit'    => now()->format('d F Y'),
            ];

            $pdf = Pdf::loadView('sertifikat.pdf', $data)
                      ->setPaper('a4', 'landscape');

            return $pdf->download("{$nomorSertifikat}.pdf");

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return abort(404, 'Donasi tidak ditemukan');
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Gagal generate sertifikat: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal membuat sertifikat. Silakan coba lagi.');
        }
    }
}
