<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\JenisPohon;
use App\Models\Kegiatan;
use App\Models\Pembelian;
use App\Models\Realisasi;
use App\Models\Dokumentasi;
use App\Models\DokumentasiKegiatan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PetugasDashboardController extends Controller
{
    /**
     * PB-21 – AC-1, AC-2, AC-3: Dashboard utama Petugas.
     * Menampilkan greeting, kegiatan aktif, dan ringkasan progress.
     */
    public function index()
    {
        $user = Auth::user();

        // Greeting berdasarkan waktu (AC-1)
        $hour = (int) Carbon::now()->format('H');
        if ($hour >= 5 && $hour < 12) {
            $greeting = 'Selamat Pagi';
        } elseif ($hour >= 12 && $hour < 15) {
            $greeting = 'Selamat Siang';
        } elseif ($hour >= 15 && $hour < 18) {
            $greeting = 'Selamat Sore';
        } else {
            $greeting = 'Selamat Malam';
        }

        // Kegiatan aktif: status Berlangsung & Persiapan (AC-2)
        $kegiatanAktif = Kegiatan::with('lokasiLahan')
            ->where('petugas_id', $user->id)
            ->whereIn('status', ['Berlangsung', 'Persiapan'])
            ->get()
            ->sortBy(function ($k) {
                // AC-3: Sorting priority – Berlangsung first, then Persiapan
                $statusOrder = match ($k->status) {
                    'Berlangsung' => 0,
                    'Persiapan'   => 1,
                    default       => 2,
                };
                // Within same status: nearest date first, then lowest progress
                $progress = $k->target_pohon > 0
                    ? ($k->realisasi_pohon / $k->target_pohon) * 100
                    : 0;
                return [$statusOrder, $k->tanggal?->timestamp ?? 0, $progress];
            })
            ->values();

        return view('petugas.dashboard', compact('user', 'greeting', 'kegiatanAktif'));
    }

    /**
     * PB-21 – AC-4, AC-5: Halaman Semua Kegiatan (list lengkap dengan search/filter/pagination).
     */
    public function semuaKegiatan(Request $request)
    {
        $user  = Auth::user();
        $query = Kegiatan::with('lokasiLahan')
            ->where('petugas_id', $user->id);

        // Search by nama kegiatan (AC-5)
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        // Filter by status (AC-5)
        if ($request->filled('status') && $request->status !== 'Semua') {
            $query->where('status', $request->status);
        }

        // Filter by lokasi (AC-5)
        if ($request->filled('lokasi')) {
            $query->where('lokasi_lahan_id', $request->lokasi);
        }

        // Sort: default by status priority then date
        $query->orderByRaw("CASE status WHEN 'Berlangsung' THEN 1 WHEN 'Persiapan' THEN 2 WHEN 'Selesai' THEN 3 WHEN 'Dibatalkan' THEN 4 ELSE 5 END")
              ->orderBy('tanggal', 'asc');

        $perPage   = $request->input('per_page', 20);
        $kegiatans = $query->paginate($perPage)->appends($request->query());

        // Get unique lokasi for filter dropdown
        $lokasiList = Kegiatan::where('petugas_id', $user->id)
            ->with('lokasiLahan')
            ->get()
            ->pluck('lokasiLahan')
            ->filter()
            ->unique('id')
            ->values();

        return view('petugas.semua-kegiatan', compact('kegiatans', 'lokasiList'));
    }

    /**
     * PB-21 – AC-6: API untuk mendapatkan jenis pohon aktif (dropdown Catat Realisasi).
     */
    public function getJenisPohon()
    {
        $jenisPohons = JenisPohon::active()
            ->orderBy('nama')
            ->get(['id', 'nama', 'nama_latin', 'harga']);

        return response()->json($jenisPohons);
    }

    /**
     * PB-21 – AC-6: Simpan realisasi pencatatan pohon (via API / modal dashboard).
     */
    public function storeRealisasi(Request $request, Kegiatan $kegiatan)
    {
        $user = Auth::user();

        // Authorization: pastikan kegiatan milik petugas ini
        if ($kegiatan->petugas_id !== $user->id) {
            return response()->json([
                'message' => 'Anda tidak memiliki akses ke kegiatan ini.',
            ], 403);
        }

        // Validasi status kegiatan
        if (!in_array($kegiatan->status, ['Berlangsung', 'Persiapan'])) {
            return response()->json([
                'message' => 'Kegiatan ini tidak dapat menerima realisasi (status: ' . $kegiatan->status . ').',
            ], 422);
        }

        $validated = $request->validate([
            'jenis_pohon_id' => 'required|exists:jenis_pohons,id',
            'jumlah'         => 'required|integer|min:1|max:10000',
            'catatan'        => 'nullable|string|max:500',
        ], [
            'jenis_pohon_id.required' => 'Pilih jenis pohon.',
            'jenis_pohon_id.exists'   => 'Jenis pohon tidak valid.',
            'jumlah.required'         => 'Masukkan jumlah pohon.',
            'jumlah.min'              => 'Jumlah pohon minimal 1.',
            'jumlah.max'              => 'Jumlah pohon maksimal 10.000.',
        ]);

        try {
            DB::beginTransaction();

            // Simpan realisasi
            $realisasi = Realisasi::create([
                'kegiatan_id'    => $kegiatan->id,
                'petugas_id'     => $user->id,
                'jenis_pohon_id' => $validated['jenis_pohon_id'],
                'jumlah'         => $validated['jumlah'],
                'catatan'        => $validated['catatan'] ?? null,
                'recorded_at'    => Carbon::now(),
            ]);

            // Update realisasi_pohon di kegiatan
            $kegiatan->increment('realisasi_pohon', $validated['jumlah']);
            $kegiatan->refresh();

            DB::commit();

            // Hitung progress baru
            $newProgress = $kegiatan->target_pohon > 0
                ? min(100, round(($kegiatan->realisasi_pohon / $kegiatan->target_pohon) * 100))
                : 0;

            return response()->json([
                'message'         => 'Realisasi berhasil dicatat!',
                'realisasi_pohon' => $kegiatan->realisasi_pohon,
                'target_pohon'    => $kegiatan->target_pohon,
                'progress'        => $newProgress,
                'jumlah_dicatat'  => $validated['jumlah'],
                'petugas_nama'    => $user->name,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PB-21: Gagal menyimpan realisasi', [
                'kegiatan_id' => $kegiatan->id,
                'petugas_id'  => $user->id,
                'error'       => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan realisasi. Silakan coba lagi.',
            ], 500);
        }
    }

    /**
     * PB-21 – API: Data dashboard untuk polling / refresh.
     */
    public function apiDashboard()
    {
        $user = Auth::user();

        $kegiatanAktif = Kegiatan::with('lokasiLahan')
            ->where('petugas_id', $user->id)
            ->whereIn('status', ['Berlangsung', 'Persiapan'])
            ->get()
            ->map(function ($k) {
                $progress = $k->target_pohon > 0
                    ? min(100, round(($k->realisasi_pohon / $k->target_pohon) * 100))
                    : 0;
                return [
                    'id'              => $k->id,
                    'nama'            => $k->nama,
                    'lokasi'          => $k->lokasiLahan?->alamat ?? '-',
                    'tanggal'         => $k->tanggal?->format('d F Y'),
                    'status'          => $k->status,
                    'realisasi_pohon' => $k->realisasi_pohon,
                    'target_pohon'    => $k->target_pohon,
                    'progress'        => $progress,
                ];
            });

        return response()->json($kegiatanAktif);
    }

    /**
     * PB-22: Menampilkan form input realisasi penanaman.
     */
    public function showRealisasiForm(Request $request)
    {
        $user = Auth::user();

        // Ambil kegiatan aktif (Persiapan / Berlangsung) yang ditugaskan ke petugas ini
        $kegiatans = Kegiatan::assignedToPetugas($user->id)
            ->whereIn('status', ['Berlangsung', 'Persiapan'])
            ->orderBy('nama')
            ->get();

        // Ambil jenis pohon aktif
        $jenisPohons = JenisPohon::active()
            ->orderBy('nama')
            ->get();

        $selectedKegiatanId = $request->input('kegiatan_id');

        return view('petugas.realisasi', compact('kegiatans', 'jenisPohons', 'selectedKegiatanId'));
    }

    /**
     * PB-22: Menyimpan data realisasi dari form.
     */
    public function storeRealisasiForm(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'kegiatan_id'     => 'required|exists:kegiatan,id',
            'jenis_pohon_id'  => 'required|exists:jenis_pohons,id',
            'jumlah_tertanam' => 'required|integer|min:0',
            'catatan'         => 'nullable|string|max:500',
        ], [
            'jumlah_tertanam.required' => 'Jumlah tertanam wajib diisi',
            'jumlah_tertanam.min'      => 'Jumlah tidak boleh bernilai negatif',
            'jumlah_tertanam.integer'  => 'Masukkan angka bilangan bulat yang valid',
        ]);

        // Pastikan kegiatan ditugaskan ke petugas ini
        $kegiatan = Kegiatan::assignedToPetugas($user->id)->find($validated['kegiatan_id']);
        if (!$kegiatan) {
            abort(403, 'Forbidden');
        }

        // Pengecekan status transaksi terkait
        $jenisPohon = JenisPohon::find($validated['jenis_pohon_id']);
        $hasSuccessTx = Pembelian::where('status', 'Sukses')
            ->where(function ($q) use ($jenisPohon) {
                $q->where('nama_item', 'like', '%' . $jenisPohon->nama . '%')
                  ->orWhere('nama_item', 'like', '%' . strtolower($jenisPohon->nama) . '%');
            })
            ->exists();

        if (!$hasSuccessTx) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Realisasi tidak dapat diinput, transaksi belum diverifikasi',
                    'errors'  => [
                        'jumlah_tertanam' => ['Realisasi tidak dapat diinput, transaksi belum diverifikasi'],
                    ],
                ], 422);
            }
            return back()->withErrors(['jumlah_tertanam' => 'Realisasi tidak dapat diinput, transaksi belum diverifikasi'])->withInput();
        }

        try {
            DB::beginTransaction();

            // Simpan realisasi
            $realisasi = Realisasi::create([
                'kegiatan_id'    => $kegiatan->id,
                'petugas_id'     => $user->id,
                'jenis_pohon_id' => $validated['jenis_pohon_id'],
                'jumlah'         => $validated['jumlah_tertanam'],
                'catatan'        => $validated['catatan'] ?? null,
                'recorded_at'    => Carbon::now(),
            ]);

            // Update realisasi_pohon di kegiatan
            $kegiatan->increment('realisasi_pohon', $validated['jumlah_tertanam']);
            $kegiatan->refresh();

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'message'    => 'Realisasi penanaman berhasil disimpan',
                    'realisasi'  => $realisasi,
                ], 200);
            }

            return redirect()->route('petugas.dashboard')->with('success', 'Realisasi penanaman berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PB-22: Gagal menyimpan realisasi dari form', [
                'error' => $e->getMessage(),
            ]);

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Terjadi kesalahan server.'], 500);
            }
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data.')->withInput();
        }
    }

    /**
     * PB-22 Unit Test helper: Menentukan apakah jumlah_tertanam melebihi target kegiatan.
     */
    public function triggersWarning($jumlah_tertanam, $kegiatan)
    {
        return $jumlah_tertanam > $kegiatan->target_pohon;
    }

    /**
     * Menyimpan file dokumentasi untuk kegiatan (Khusus gambar).
     */
    public function storeDokumentasi(Request $request, Kegiatan $kegiatan)
    {
        $user = Auth::user();

        // Validasi petugas memiliki akses ke kegiatan ini
        if ($kegiatan->petugas_id !== $user->id) {
            return response()->json([
                'message' => 'Anda tidak memiliki akses ke kegiatan ini.',
            ], 403);
        }

        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ], [
            'foto.required' => 'Pilih file foto dokumentasi.',
            'foto.image'    => 'File harus berupa gambar.',
            'foto.mimes'    => 'Format gambar harus jpeg, png, jpg, atau gif.',
            'foto.max'      => 'Ukuran gambar maksimal 5MB.',
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('dokumentasi', 'public');

            $dokumentasi = Dokumentasi::create([
                'kegiatan_id' => $kegiatan->id,
                'petugas_id'  => $user->id,
                'file_path'   => $path,
            ]);

            return response()->json([
                'message'      => 'Dokumentasi berhasil diunggah!',
                'dokumentasi'  => $dokumentasi,
            ], 200);
        }

        return response()->json([
            'message' => 'Gagal mengunggah dokumentasi.',
        ], 400);
    }
}