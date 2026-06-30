<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PembelianController extends Controller
{
    // Menampilkan Form Pemilihan Lokasi & Jenis Pohon
    public function index()
    {
        $lokasiLahans = DB::table('lokasi_lahans')->get();
        $jenisPohons = DB::table('jenis_pohons')->get();

        return view('pembelian.index', compact('lokasiLahans', 'jenisPohons'));
    }

    // Memproses Kalkulasi Biaya & Simpan Data ke Tabel pembelians di Neon
    public function checkout(Request $request)
    {
        $request->validate([
            'jenis_pohon_id' => 'required',
            'lokasi_lahan_id' => 'required',
            'catatan'         => 'nullable|string',
        ]);

        $pohon = DB::table('jenis_pohons')->where('id', $request->jenis_pohon_id)->first();
        $lahan = DB::table('lokasi_lahans')->where('id', $request->lokasi_lahan_id)->first();

        if (!$pohon || !$lahan) {
            return redirect()->back()->with('error', 'Data pohon atau lokasi tidak ditemukan.');
        }

       // Kalkulasi Komponen Biaya
        $hargaPohon = $pohon->harga;
        $biayaLayanan = 25000;
        $totalHarga = $hargaPohon + $biayaLayanan;

        // Generate Kode Transaksi Unik
        $kodeTransaksi = 'GNV-' . date('Ymd') . '-' . rand(1000, 9999);

    
        $pembelianId = DB::table('pembelians')->insertGetId([
            'user_id'           => Auth::id(),
            'nama_item'         => $pohon->nama . ' (' . $pohon->nama_latin . ') - Lahan: ' . ($lahan->nama_lahan ?? $lahan->nama),
            'jumlah_item'       => 1,
            'total_harga'       => $totalHarga,
            'status'            => 'Pending',
            'kode_transaksi'    => $kodeTransaksi,
            'catatan'           => $request->catatan,
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        return redirect()->route('pembelian.invoice', $pembelianId)->with('success', 'Pesanan kontribusi berhasil dibuat!');
    }

    // Menampilkan Halaman Invoice Rangkuman
    public function invoice($id)
    {
        $pembelian = DB::table('pembelians')->where('id', $id)->first();

        if (!$pembelian) {
            abort(404, 'Invoice tidak ditemukan.');
        }

        return view('pembelian.invoice', compact('pembelian'));
    }
    public function uploadBukti(Request $request, $id)
{
        $request->validate([
        'bukti_transfer' => 'required|image|mimes:jpg,jpeg,png|max:2048'
    ]);

    $file = $request->file('bukti_transfer');

    $namaFile = time().'_'.$file->getClientOriginalName();

    $file->move(
        public_path('uploads/bukti-transfer'),
        $namaFile
    );

    DB::table('pembelians')
        ->where('id', $id)
        ->update([
            'bukti_dokumentasi' => $namaFile,
            'status' => 'Pending',
            'updated_at' => now()
        ]);

    return redirect()
        ->route('riwayat.index')
        ->with('success', 'Bukti transfer berhasil dikirim.');
}
}