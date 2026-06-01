<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\PendaftaranKegiatan;
use App\Models\Donasi;
use App\Models\Pembelian;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\CsvSafeFormatter;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminMonitoringController extends Controller
{
    /**
     * Redirects to the first activity's participant page if it exists.
     */
    public function pesertaIndex()
    {
        $firstKegiatan = Kegiatan::orderBy('id', 'asc')->first();

        if ($firstKegiatan) {
            return redirect()->route('admin.kegiatan.peserta', ['id' => $firstKegiatan->id]);
        }

        return view('admin.monitoring.peserta', [
            'kegiatans' => collect(),
            'selectedKegiatan' => null,
            'peserta' => collect(),
        ]);
    }

    /**
     * Lists participants for a specific activity with status filtering.
     */
    public function pesertaKegiatan(Request $request, $id)
    {
        $selectedKegiatan = Kegiatan::find($id);

        if (!$selectedKegiatan) {
            abort(404, 'Kegiatan tidak ditemukan.');
        }

        $kegiatans = Kegiatan::orderBy('nama', 'asc')->get();

        $query = PendaftaranKegiatan::with('user')
            ->where('kegiatan_id', $id);

        if ($request->filled('status')) {
            $status = strtolower($request->status);
            if ($status === 'terdaftar') {
                $query->where('status', 'Terdaftar');
            } elseif ($status === 'hadir') {
                $query->where('status', 'Hadir');
            } elseif ($status === 'batal' || $status === 'dibatalkan') {
                $query->where('status', 'Dibatalkan');
            } elseif ($status === 'selesai') {
                $query->where('status', 'Selesai');
            }
        }

        $peserta = $query->latest()->paginate(20)->withQueryString();

        return view('admin.monitoring.peserta', compact('kegiatans', 'selectedKegiatan', 'peserta'));
    }

    /**
     * Lists donations with status filtering.
     */
    public function donasiIndex(Request $request)
    {
        $query = Donasi::with('user');

        if ($request->filled('status')) {
            $status = strtolower($request->status);
            if ($status === 'pending') {
                $query->where('status', 'Pending');
            } elseif ($status === 'success' || $status === 'sukses') {
                $query->where('status', 'Sukses');
            } elseif ($status === 'expired' || $status === 'kadaluarsa' || $status === 'kedaluwarsa') {
                $query->where('status', 'Expired');
            } elseif ($status === 'gagal') {
                $query->where('status', 'Gagal');
            }
        }

        $donasis = $query->latest()->paginate(20)->withQueryString();

        return view('admin.monitoring.donasi', compact('donasis'));
    }

    /**
     * Lists purchases with status filtering.
     */
    public function pembelianIndex(Request $request)
    {
        $query = Pembelian::with('user');

        if ($request->filled('status')) {
            $status = strtolower($request->status);
            if ($status === 'pending') {
                $query->where('status', 'Pending');
            } elseif ($status === 'success' || $status === 'sukses') {
                $query->where('status', 'Sukses');
            } elseif ($status === 'expired' || $status === 'kadaluarsa' || $status === 'kedaluwarsa') {
                $query->where('status', 'Expired');
            } elseif ($status === 'gagal') {
                $query->where('status', 'Gagal');
            }
        }

        $pembelians = $query->latest()->paginate(20)->withQueryString();

        return view('admin.monitoring.pembelian', compact('pembelians'));
    }

    /**
     * Lists users with search and filtering by role and status.
     */
    public function penggunaIndex(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'aktif') {
                $query->where('is_active', true);
            } elseif ($status === 'nonaktif') {
                $query->where('is_active', false);
            }
        }

        $penggunas = $query->latest()->paginate(20)->withQueryString();

        return view('admin.monitoring.pengguna', compact('penggunas'));
    }

    /**
     * Exports donations as CSV.
     */
    public function exportDonasiCsv(Request $request)
    {
        $query = Donasi::with('user');

        if ($request->filled('status')) {
            $status = strtolower($request->status);
            if ($status === 'pending') {
                $query->where('status', 'Pending');
            } elseif ($status === 'success' || $status === 'sukses') {
                $query->where('status', 'Sukses');
            } elseif ($status === 'expired' || $status === 'kadaluarsa' || $status === 'kedaluwarsa') {
                $query->where('status', 'Expired');
            } elseif ($status === 'gagal') {
                $query->where('status', 'Gagal');
            }
        }

        $count = $query->count();
        if ($count > 1000 || $request->has('simulate_large')) {
            return redirect()->route('admin.donasi.index')->with('error', 'Export sedang diproses, file akan tersedia sebentar lagi');
        }

        return new StreamedResponse(function () use ($query) {
            $handle = fopen('php://output', 'w');
            
            // Add UTF-8 BOM
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($handle, ['ID Donasi', 'Nama Pengguna', 'Nominal', 'Tanggal Donasi', 'Status Pembayaran']);

            $query->chunk(200, function ($donasis) use ($handle) {
                foreach ($donasis as $donasi) {
                    $statusLabel = match ($donasi->status) {
                        'Pending' => 'Menunggu',
                        'Sukses' => 'Berhasil',
                        'Expired' => 'Kadaluarsa',
                        'Gagal' => 'Gagal',
                        default => $donasi->status,
                    };

                    fputcsv($handle, [
                        CsvSafeFormatter::escapeCell($donasi->id),
                        CsvSafeFormatter::escapeCell($donasi->user ? $donasi->user->name : 'N/A'),
                        CsvSafeFormatter::escapeCell($donasi->jumlah),
                        CsvSafeFormatter::escapeCell($donasi->created_at ? $donasi->created_at->toDateTimeString() : ''),
                        CsvSafeFormatter::escapeCell($statusLabel),
                    ]);
                }
            });

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="daftar-donasi.csv"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}
