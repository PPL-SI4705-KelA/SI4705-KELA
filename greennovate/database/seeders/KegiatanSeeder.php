<?php

namespace Database\Seeders;

use App\Models\Kegiatan;
use Illuminate\Database\Seeder;

class KegiatanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nama'        => 'Tanam Pohon Mangrove Pantai Barat',
                'lokasi'      => 'Pantai Barat, Bandung',
                'tanggal'     => '2026-05-10',
                'target_pohon'=> 500,
                'kuota_total' => 50,
                'kuota_terisi'=> 12,
                'deskripsi'   => 'Kegiatan penanaman mangrove untuk mencegah abrasi pantai.',
                'status'      => 'aktif',
            ],
            [
                'nama'        => 'Penghijauan Kawasan Hutan Kota',
                'lokasi'      => 'Taman Hutan Raya, Bandung',
                'tanggal'     => '2026-05-15',
                'target_pohon'=> 200,
                'kuota_total' => 30,
                'kuota_terisi'=> 30,
                'deskripsi'   => 'Restorasi kawasan hutan kota yang terdampak pembangunan.',
                'status'      => 'penuh',
            ],
            [
                'nama'        => 'Tanam Pohon Buah Bersama Warga',
                'lokasi'      => 'Desa Cisarua, Sumedang',
                'tanggal'     => '2026-05-20',
                'target_pohon'=> 300,
                'kuota_total' => 40,
                'kuota_terisi'=> 8,
                'deskripsi'   => 'Program penanaman pohon buah untuk ketahanan pangan lokal.',
                'status'      => 'aktif',
            ],
            [
                'nama'        => 'Gerakan 1000 Pohon Jatinangor',
                'lokasi'      => 'Jatinangor, Sumedang',
                'tanggal'     => '2026-06-01',
                'target_pohon'=> 1000,
                'kuota_total' => 100,
                'kuota_terisi'=> 45,
                'deskripsi'   => 'Kolaborasi mahasiswa dan warga dalam penghijauan kampus.',
                'status'      => 'aktif',
            ],
            [
                'nama'        => 'Penghijauan Tepian Sungai Citarum',
                'lokasi'      => 'Sungai Citarum, Karawang',
                'tanggal'     => '2026-06-08',
                'target_pohon'=> 400,
                'kuota_total' => 60,
                'kuota_terisi'=> 60,
                'deskripsi'   => 'Penanaman pohon bambu dan vetiver untuk menstabilkan tepi sungai.',
                'status'      => 'penuh',
            ],
            [
                'nama'        => 'Tanam Pohon Produktif Dataran Tinggi',
                'lokasi'      => 'Lembang, Bandung Barat',
                'tanggal'     => '2026-06-15',
                'target_pohon'=> 250,
                'kuota_total' => 35,
                'kuota_terisi'=> 0,
                'deskripsi'   => 'Penanaman kopi dan teh untuk penghijauan sekaligus ekonomi warga.',
                'status'      => 'aktif',
            ],
            [
                'nama'        => 'Restorasi Lahan Kritis Cianjur',
                'lokasi'      => 'Cianjur Selatan, Cianjur',
                'tanggal'     => '2026-04-01',
                'target_pohon'=> 600,
                'kuota_total' => 80,
                'kuota_terisi'=> 80,
                'deskripsi'   => 'Program pemulihan lahan pasca bencana.',
                'status'      => 'selesai',
            ],
            [
                'nama'        => 'Tanam Pohon Pelindung Jalan',
                'lokasi'      => 'Jalan Raya Padalarang, Bandung Barat',
                'tanggal'     => '2026-06-22',
                'target_pohon'=> 150,
                'kuota_total' => 20,
                'kuota_terisi'=> 5,
                'deskripsi'   => 'Penghijauan jalur hijau sepanjang jalan raya.',
                'status'      => 'aktif',
            ],
            [
                'nama'        => 'Penghijauan Sekolah Hijau Cimahi',
                'lokasi'      => 'Cimahi Utara, Cimahi',
                'tanggal'     => '2026-07-05',
                'target_pohon'=> 100,
                'kuota_total' => 25,
                'kuota_terisi'=> 3,
                'deskripsi'   => 'Penanaman pohon di 10 sekolah dasar dalam rangka edukasi lingkungan.',
                'status'      => 'aktif',
            ],
        ];

        foreach ($data as $item) {
            Kegiatan::create($item);
        }
    }
}
