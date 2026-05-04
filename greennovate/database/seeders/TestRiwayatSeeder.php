<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\LokasiLahan;
use App\Models\Kegiatan;
use App\Models\PendaftaranKegiatan;
use App\Models\Donasi;
use App\Models\Pembelian;

class TestRiwayatSeeder extends Seeder
{
    public function run()
    {
        $user = User::firstOrCreate(
            ['email' => 'test@greennovate.com'],
            [
                'name' => 'Budi Tester',
                'phone' => '081234567899',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_active' => true,
            ]
        );

        $admin = User::firstOrCreate(
            ['email' => 'admin_seed@test.com'],
            [
                'name' => 'Admin Seed',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        $lokasi = LokasiLahan::firstOrCreate(
            ['nama' => 'Taman Nasional Gunung Gede'],
            ['alamat' => 'Cianjur, Jawa Barat', 'deskripsi' => 'Area konservasi hutan hujan tropis.']
        );

        $kegiatan = Kegiatan::firstOrCreate(
            ['nama' => 'Penanaman 1000 Pohon Trembesi'],
            [
                'lokasi_lahan_id' => $lokasi->id,
                'petugas_id' => $admin->id,
                'tanggal' => now()->addDays(10),
                'target_pohon' => 1000,
                'realisasi_pohon' => 0,
                'quota' => 100,
                'registered_count' => 1,
                'status' => 'Berlangsung',
                'deskripsi' => 'Kegiatan penanaman pohon untuk penghijauan.',
                'slug' => 'penanaman-1000-pohon-trembesi-test'
            ]
        );

        PendaftaranKegiatan::firstOrCreate(
            ['user_id' => $user->id, 'kegiatan_id' => $kegiatan->id],
            [
                'nama_lengkap' => $user->name,
                'no_hp' => '081234567899',
                'alamat' => 'Jl. Kebon Jeruk',
                'status' => 'Dikonfirmasi'
            ]
        );

        Donasi::firstOrCreate(
            ['user_id' => $user->id, 'pesan' => 'Semoga pohonnya tumbuh subur'],
            [
                'jumlah' => 150000,
                'metode_bayar' => 'QRIS',
                'kode_transaksi' => 'DON-'.time(),
                'status' => 'Sukses'
            ]
        );

        Pembelian::firstOrCreate(
            ['user_id' => $user->id, 'nama_produk' => 'Bibit Pohon Mangga'],
            [
                'kategori' => 'Bibit',
                'jumlah_item' => 5,
                'harga_satuan' => 25000,
                'total_harga' => 125000,
                'metode_bayar' => 'Transfer Bank',
                'kode_transaksi' => 'BUY-'.time(),
                'status' => 'Dikirim'
            ]
        );
    }
}
