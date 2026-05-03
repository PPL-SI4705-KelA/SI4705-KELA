<?php

namespace Database\Seeders;

use App\Models\Kegiatan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KegiatanSeeder extends Seeder
{
    /**
     * Isi slug untuk kegiatan yang belum punya slug.
     * Tidak menghapus atau mengubah data lain.
     */
    public function run(): void
    {
        $kegiatanTanpaSlug = Kegiatan::withTrashed()
            ->whereNull('slug')
            ->orWhere('slug', '')
            ->get();

        foreach ($kegiatanTanpaSlug as $item) {
            $baseSlug = Str::slug($item->nama);

            // Hindari slug duplikat dengan tambah id di belakang
            $slug = $baseSlug . '-' . $item->id;

            $item->slug = $slug;
            $item->saveQuietly(); // saveQuietly agar tidak trigger event/observer
        }

        $this->command->info('Slug berhasil diisi untuk ' . $kegiatanTanpaSlug->count() . ' kegiatan.');
    }
}