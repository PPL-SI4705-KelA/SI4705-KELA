<?php

namespace Database\Seeders;

use App\Models\Kegiatan;
use App\Models\LokasiLahan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KegiatanSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Seed data kegiatan awal ─────────────────────────────────────────
        $petugas = User::where('role', 'petugas')->first();

        $lokasi1 = LokasiLahan::firstOrCreate(
            ['nama' => 'Hutan Lindung Berau'],
            ['alamat' => 'Berau, Kalimantan Timur', 'deskripsi' => 'Area reklamasi ex-tambang']
        );

        $lokasi2 = LokasiLahan::firstOrCreate(
            ['nama' => 'Morowali Green Zone'],
            ['alamat' => 'Morowali, Sulawesi Tengah', 'deskripsi' => 'Area tanam kembali pinggir pantai']
        );

        if ($petugas) {
            Kegiatan::firstOrCreate(
                ['nama' => 'Aksi Tanam Pohon Berau'],
                [
                    'lokasi_lahan_id' => $lokasi1->id,
                    'petugas_id'      => $petugas->id,
                    'tanggal'         => '2026-03-10',
                    'target_pohon'    => 3000,
                    'realisasi_pohon' => 2140,
                    'status'          => 'Berlangsung',
                ]
            );

            Kegiatan::firstOrCreate(
                ['nama' => 'Gerakan Seribu Pohon Morowali'],
                [
                    'lokasi_lahan_id' => $lokasi2->id,
                    'petugas_id'      => $petugas->id,
                    'tanggal'         => '2026-03-18',
                    'target_pohon'    => 1200,
                    'realisasi_pohon' => 780,
                    'status'          => 'Berlangsung',
                ]
            );
        }

        // ── 2. Isi slug untuk semua kegiatan yang belum punya slug ─────────────
        $kegiatanTanpaSlug = Kegiatan::withTrashed()
            ->where(function ($query) {
                $query->whereNull('slug')->orWhere('slug', '');
            })
            ->get();

        foreach ($kegiatanTanpaSlug as $item) {
            $item->slug = Str::slug($item->nama) . '-' . $item->id;
            $item->saveQuietly();
        }

        $this->command->info('Slug berhasil diisi untuk ' . $kegiatanTanpaSlug->count() . ' kegiatan.');
    }
}