<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Kegiatan;
use App\Models\LokasiLahan;
use App\Models\JenisPohon;
use App\Models\Pembelian;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class PetugasRealisasiE2ETest extends DuskTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        Kegiatan::where('nama', 'LIKE', 'E2E%')->forceDelete();
        User::where('email', 'LIKE', 'e2e_%')->forceDelete();
    }

    private function prepareData()
    {
        // 1. Petugas
        $petugas = User::firstOrCreate(
            ['email' => 'e2e_petugas@greennovate.test'],
            [
                'name' => 'Petugas E2E',
                'password' => Hash::make('password'),
                'role' => 'petugas',
                'is_active' => 'true',
            ]
        );

        // 2. User biasa
        $user = User::firstOrCreate(
            ['email' => 'e2e_user@greennovate.test'],
            [
                'name' => 'User Biasa E2E',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_active' => 'true',
            ]
        );

        // 3. Data referensi (Jenis Pohon, Lokasi, Kegiatan, Pembelian)
        $kategori = \App\Models\KategoriPohon::firstOrCreate(['nama' => 'Kategori E2E'], ['deskripsi' => 'Desk E2E']);
        $lokasi = LokasiLahan::firstOrCreate(['nama' => 'Lahan E2E'], ['alamat' => 'Alamat E2E', 'deskripsi' => 'Desk']);
        $jenisPohon = JenisPohon::firstOrCreate(['nama' => 'Pohon E2E'], ['nama_latin' => 'E2E Latin', 'harga' => 10000, 'stok' => 100, 'kategori_pohon_id' => $kategori->id]);
        
        $kegiatan = Kegiatan::create([
            'nama' => 'E2E Penanaman Bersama',
            'lokasi_lahan_id' => $lokasi->id,
            'petugas_id' => $petugas->id,
            'status' => 'Berlangsung',
            'tanggal' => Carbon::now()->addDays(2),
            'target_pohon' => 1000,
            'realisasi_pohon' => 10, // Awalnya 10
            'quota' => 50,
        ]);

        Pembelian::create([
            'user_id' => $user->id,
            'kode_transaksi' => 'TX-E2E-123',
            'nama_item' => 'Donasi Pohon E2E',
            'jumlah_item' => 50,
            'total_harga' => 500000,
            'status' => 'Sukses',
        ]);

        return [$petugas, $user, $kegiatan];
    }

    public function test_petugas_can_input_realisasi_and_user_can_view_it()
    {
        $this->browse(function (Browser $browserPetugas, Browser $browserUser) {
            [$petugas, $user, $kegiatan] = $this->prepareData();

            // --- STEP 1: Petugas menginput realisasi ---
            $browserPetugas->loginAs($petugas)
                ->visit('/petugas/realisasi')
                ->assertSee('Catat Realisasi')
                ->select('kegiatan_id', (string) $kegiatan->id)
                ->select('jenis_pohon_id', (string) $jenisPohon->id)
                ->type('jumlah_tertanam', '50')
                ->type('catatan', 'Telah ditanam 50 bibit dengan sukses di lahan.')
                ->press('Simpan Realisasi')
                ->pause(1500)
                // Memastikan berhasil tersimpan dan dialihkan
                ->assertPathIs('/petugas/dashboard')
                ->assertSee('Realisasi penanaman berhasil disimpan');

            // --- STEP 2: User biasa melihat update progres ---
            $browserUser->loginAs($user)
                ->visit('/kegiatan/' . $kegiatan->slug) // Kunjungi detail kegiatan publik (atau riwayat, tergantung rute yg ada)
                ->pause(1000)
                // Awalnya 10, ditambah 50 = 60
                ->assertSee('60') 
                ->assertSee('Pohon Tertanam'); // Menyesuaikan dengan label yang ada di UI halaman detail
        });
    }
}
