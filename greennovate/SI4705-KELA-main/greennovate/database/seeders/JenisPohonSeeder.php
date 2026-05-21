<?php

namespace Database\Seeders;

use App\Models\JenisPohon;
use App\Models\KategoriPohon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

class JenisPohonSeeder extends Seeder
{
    /**
     * Seed kategori dan jenis pohon untuk PB-14.
     */
    public function run(): void
    {
        // ── Kategori Pohon ─────────────────────────────────────────────────────
        $categories = [
            ['nama' => 'Pohon Besar',   'deskripsi' => 'Pohon dengan tinggi dewasa > 20 meter'],
            ['nama' => 'Pohon Sedang',  'deskripsi' => 'Pohon dengan tinggi dewasa 10-20 meter'],
            ['nama' => 'Pohon Kecil',   'deskripsi' => 'Pohon dengan tinggi dewasa < 10 meter'],
            ['nama' => 'Pohon Buah',    'deskripsi' => 'Pohon yang menghasilkan buah konsumsi'],
            ['nama' => 'Pohon Hias',    'deskripsi' => 'Pohon untuk tujuan estetika dan penghijauan kota'],
        ];

        foreach ($categories as $cat) {
            KategoriPohon::firstOrCreate(['nama' => $cat['nama']], $cat);
        }

        // ── Jenis Pohon ───────────────────────────────────────────────────────
        $besar  = KategoriPohon::where('nama', 'Pohon Besar')->first();
        $sedang = KategoriPohon::where('nama', 'Pohon Sedang')->first();
        $kecil  = KategoriPohon::where('nama', 'Pohon Kecil')->first();
        $buah   = KategoriPohon::where('nama', 'Pohon Buah')->first();
        $hias   = KategoriPohon::where('nama', 'Pohon Hias')->first();

        $trees = [
            // Pohon Besar
            ['nama' => 'Mahoni',    'nama_latin' => 'Swietenia macrophylla',    'kategori_pohon_id' => $besar->id,  'harga' => 50000,  'status' => 'active'],
            ['nama' => 'Jati',      'nama_latin' => 'Tectona grandis',          'kategori_pohon_id' => $besar->id,  'harga' => 75000,  'status' => 'active'],
            ['nama' => 'Trembesi',  'nama_latin' => 'Samanea saman',            'kategori_pohon_id' => $besar->id,  'harga' => 60000,  'status' => 'active'],
            ['nama' => 'Beringin',  'nama_latin' => 'Ficus benjamina',          'kategori_pohon_id' => $besar->id,  'harga' => 80000,  'status' => 'active'],

            // Pohon Sedang
            ['nama' => 'Akasia',    'nama_latin' => 'Acacia mangium',           'kategori_pohon_id' => $sedang->id, 'harga' => 35000,  'status' => 'active'],
            ['nama' => 'Sengon',    'nama_latin' => 'Falcataria moluccana',     'kategori_pohon_id' => $sedang->id, 'harga' => 25000,  'status' => 'active'],
            ['nama' => 'Ketapang', 'nama_latin' => 'Terminalia catappa',        'kategori_pohon_id' => $sedang->id, 'harga' => 40000,  'status' => 'active'],

            // Pohon Kecil
            ['nama' => 'Bambu Kuning',  'nama_latin' => 'Bambusa vulgaris',     'kategori_pohon_id' => $kecil->id,  'harga' => 15000,  'status' => 'active'],
            ['nama' => 'Cemara Udang',  'nama_latin' => 'Casuarina equisetifolia', 'kategori_pohon_id' => $kecil->id, 'harga' => 20000, 'status' => 'active'],

            // Pohon Buah
            ['nama' => 'Mangga',    'nama_latin' => 'Mangifera indica',         'kategori_pohon_id' => $buah->id,   'harga' => 55000,  'status' => 'active'],
            ['nama' => 'Durian',    'nama_latin' => 'Durio zibethinus',         'kategori_pohon_id' => $buah->id,   'harga' => 90000,  'status' => 'active'],
            ['nama' => 'Rambutan',  'nama_latin' => 'Nephelium lappaceum',      'kategori_pohon_id' => $buah->id,   'harga' => 45000,  'status' => 'active'],

            // Pohon Hias
            ['nama' => 'Flamboyan', 'nama_latin' => 'Delonix regia',            'kategori_pohon_id' => $hias->id,   'harga' => 65000,  'status' => 'active'],
            ['nama' => 'Tabebuya',  'nama_latin' => 'Tabebuia rosea',           'kategori_pohon_id' => $hias->id,   'harga' => 70000,  'status' => 'active'],
        ];

        foreach ($trees as $tree) {
            JenisPohon::firstOrCreate(['nama' => $tree['nama']], $tree);
        }
    }
}
