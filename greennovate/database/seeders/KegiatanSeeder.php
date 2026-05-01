<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KegiatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $petugas = \App\Models\User::where('role', 'petugas')->first();
        
        // If there's no LokasiLahan, just skip or create one, but we assume it exists from previous seeding
        $lokasi1 = \App\Models\LokasiLahan::firstOrCreate(['nama' => 'Hutan Lindung Berau'], ['alamat' => 'Berau, Kalimantan Timur', 'deskripsi' => 'Area reklamasi ex-tambang']);
        $lokasi2 = \App\Models\LokasiLahan::firstOrCreate(['nama' => 'Morowali Green Zone'], ['alamat' => 'Morowali, Sulawesi Tengah', 'deskripsi' => 'Area tanam kembali pinggir pantai']);

        if ($petugas) {
            \App\Models\Kegiatan::create([
                'nama' => 'Aksi Tanam Pohon Berau',
                'lokasi_lahan_id' => $lokasi1->id,
                'petugas_id' => $petugas->id,
                'tanggal' => '2026-03-10',
                'target_pohon' => 3000,
                'realisasi_pohon' => 2140,
                'status' => 'Berlangsung',
            ]);

            \App\Models\Kegiatan::create([
                'nama' => 'Gerakan Seribu Pohon Morowali',
                'lokasi_lahan_id' => $lokasi2->id,
                'petugas_id' => $petugas->id,
                'tanggal' => '2026-03-18',
                'target_pohon' => 1200,
                'realisasi_pohon' => 780,
                'status' => 'Berlangsung',
            ]);
        }
    }
}
