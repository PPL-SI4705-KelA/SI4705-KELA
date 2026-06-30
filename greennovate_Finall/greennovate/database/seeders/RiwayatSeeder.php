<?php

namespace Database\Seeders;

use App\Models\Donasi;
use App\Models\Kegiatan;
use App\Models\Pembelian;
use App\Models\PendaftaranKegiatan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Seeder untuk data dummy riwayat partisipasi (PB-11).
 * Membuat contoh donasi, pembelian, dan pendaftaran kegiatan
 * untuk user pertama yang ditemukan di database.
 */
class RiwayatSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('role', 'user')->first();
        if (!$user) {
            $this->command->warn('Tidak ada user dengan role "user". Seeder di-skip.');
            return;
        }

        // ── Donasi ──────────────────────────────────────────────────────────
        $donasiData = [
            ['nama_donasi' => 'Donasi Bibit Pohon Mangga', 'jumlah' => 150000, 'status' => 'Sukses', 'metode_pembayaran' => 'Transfer Bank'],
            ['nama_donasi' => 'Donasi Penanaman Hutan Kota', 'jumlah' => 500000, 'status' => 'Pending', 'metode_pembayaran' => 'E-Wallet'],
            ['nama_donasi' => 'Donasi Alat Tanam', 'jumlah' => 75000, 'status' => 'Sukses', 'metode_pembayaran' => 'QRIS'],
        ];

        foreach ($donasiData as $d) {
            Donasi::create([
                'user_id'            => $user->id,
                'nama_donasi'        => $d['nama_donasi'],
                'jumlah'             => $d['jumlah'],
                'metode_pembayaran'  => $d['metode_pembayaran'],
                'status'             => $d['status'],
                'kode_transaksi'     => 'DON-' . strtoupper(Str::random(8)),
            ]);
        }

        // ── Pembelian ───────────────────────────────────────────────────────
        $pembelianData = [
            ['nama_item' => 'Bibit Pohon Jati', 'jumlah_item' => 5, 'total_harga' => 250000, 'status' => 'Sukses'],
            ['nama_item' => 'Pupuk Organik 5kg', 'jumlah_item' => 2, 'total_harga' => 120000, 'status' => 'Pending'],
            ['nama_item' => 'Tiket Event Green Festival', 'jumlah_item' => 1, 'total_harga' => 50000, 'status' => 'Sukses'],
        ];

        foreach ($pembelianData as $p) {
            Pembelian::create([
                'user_id'         => $user->id,
                'nama_item'       => $p['nama_item'],
                'jumlah_item'     => $p['jumlah_item'],
                'total_harga'     => $p['total_harga'],
                'status'          => $p['status'],
                'kode_transaksi'  => 'PBL-' . strtoupper(Str::random(8)),
            ]);
        }

        // ── Pendaftaran Kegiatan ────────────────────────────────────────────
        $kegiatans = Kegiatan::take(3)->get();
        $statuses = ['Terdaftar', 'Hadir', 'Selesai'];

        foreach ($kegiatans as $index => $kegiatan) {
            PendaftaranKegiatan::create([
                'user_id'       => $user->id,
                'kegiatan_id'   => $kegiatan->id,
                'nama_lengkap'  => $user->name,
                'no_hp'         => $user->phone ?? '08123456789',
                'alamat'        => 'Jl. Contoh No. ' . ($index + 1),
                'status'        => $statuses[$index] ?? 'Terdaftar',
            ]);
        }

        $this->command->info('RiwayatSeeder: Data dummy berhasil dibuat untuk user "' . $user->name . '".');
    }
}
