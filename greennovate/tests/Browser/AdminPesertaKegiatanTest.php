<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\LokasiLahan;
use App\Models\Kegiatan;
use App\Models\PendaftaranKegiatan;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\Hash;

class AdminPesertaKegiatanTest extends DuskTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Membersihkan data tes sebelumnya
        Kegiatan::where('nama', 'LIKE', 'Dusk%')->forceDelete();
        PendaftaranKegiatan::where('nama_lengkap', 'LIKE', 'Dusk%')->forceDelete();
        User::where('email', 'LIKE', '%dusk%')->forceDelete();
    }

    private function prepareData()
    {
        // 1. Buat admin
        $admin = User::firstOrCreate(
            ['email' => 'admin_peserta_dusk@greennovate.test'],
            [
                'name' => 'Admin Dusk Peserta',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => 'true',
            ]
        );

        // 2. Buat user biasa
        $user = User::firstOrCreate(
            ['email' => 'user_peserta_dusk@greennovate.test'],
            [
                'name' => 'User Dusk',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_active' => 'true',
            ]
        );

        // 3. Buat lokasi lahan
        $lokasi = LokasiLahan::firstOrCreate(
            ['nama' => 'Lahan Dusk'],
            [
                'alamat' => 'Alamat Lahan Dusk',
                'deskripsi' => 'Deskripsi Lahan Dusk',
            ]
        );

        // 4. Buat 2 Kegiatan
        $kegiatan1 = Kegiatan::create([
            'nama' => 'Dusk Aksi Tanam Pohon Berau',
            'lokasi_lahan_id' => $lokasi->id,
            'petugas_id' => $admin->id,
            'tanggal' => now()->addDays(5),
            'target_pohon' => 100,
            'quota' => 50,
            'status' => 'Berlangsung',
        ]);

        $kegiatan2 = Kegiatan::create([
            'nama' => 'Dusk Gerakan Seribu Pohon',
            'lokasi_lahan_id' => $lokasi->id,
            'petugas_id' => $admin->id,
            'tanggal' => now()->addDays(10),
            'target_pohon' => 1000,
            'quota' => 200,
            'status' => 'Berlangsung',
        ]);

        // 5. Buat peserta untuk kegiatan 1 (Status: Terdaftar)
        PendaftaranKegiatan::create([
            'user_id' => $user->id,
            'kegiatan_id' => $kegiatan1->id,
            'nama_lengkap' => 'Dusk Peserta Satu',
            'no_hp' => '081234567890',
            'alamat' => 'Alamat Peserta Satu',
            'status' => 'Terdaftar',
        ]);

        // 6. Buat peserta untuk kegiatan 2 (Status: Hadir)
        PendaftaranKegiatan::create([
            'user_id' => $user->id,
            'kegiatan_id' => $kegiatan2->id,
            'nama_lengkap' => 'Dusk Peserta Dua',
            'no_hp' => '081234567890',
            'alamat' => 'Alamat Peserta Dua',
            'status' => 'Hadir',
        ]);

        return [$admin, $kegiatan1, $kegiatan2];
    }

    /**
     * E2E test: Admin memfilter data peserta berdasarkan dropdown kegiatan dan dropdown status
     */
    public function testAdminCanFilterPesertaByDropdowns()
    {
        $this->browse(function (Browser $browser) {
            [$admin, $kegiatan1, $kegiatan2] = $this->prepareData();

            // Login sebagai admin dan kunjungi halaman peserta
            $browser->loginAs($admin)
                ->visit('/admin/peserta')
                ->assertSee('Peserta Kegiatan');
                
            // --- SKENARIO 1: Memilih kegiatan dari Dropdown 'Pilih Kegiatan' ---
            // Saat option dipilih, javascript akan me-redirect (onchange window.location.href)
            $browser->select('#kegiatan-select', $kegiatan1->id)
                ->pause(1500) // Tunggu sebentar sampai halaman terreload akibat onchange
                
                // Pastikan berada di URL yang benar
                ->assertPathIs('/admin/kegiatan/' . $kegiatan1->id . '/peserta')
                // Pastikan UI menampilkan kegiatan yang terpilih
                ->assertSee('Peserta untuk: Dusk Aksi Tanam Pohon Berau')
                // Pastikan data peserta satu ada
                ->assertSee('Dusk Peserta Satu');

            // --- SKENARIO 2: Memilih status dari Dropdown 'Status Pendaftaran' ---
            // Saat option dipilih, javascript akan men-submit form (onchange this.form.submit())
            $browser->select('#status-filter', 'terdaftar')
                ->pause(1500) // Tunggu sampai halaman terreload akibat submit form
                
                // Pastikan URL memiliki query string ?status=terdaftar
                ->assertQueryStringHas('status', 'terdaftar')
                // Pastikan data peserta satu (terdaftar) tetap muncul
                ->assertSee('Dusk Peserta Satu');
                
            // --- SKENARIO 3: Memfilter status yang tidak dimiliki oleh peserta di kegiatan 1 ---
            $browser->select('#status-filter', 'hadir')
                ->pause(1500)
                ->assertQueryStringHas('status', 'hadir')
                // Karena 'Dusk Peserta Satu' berstatus 'Terdaftar', maka list harus kosong
                ->assertDontSee('Dusk Peserta Satu')
                ->assertSee('Belum ada peserta terdaftar');

            // --- SKENARIO 4: Pindah ke kegiatan lain menggunakan dropdown 'Pilih Kegiatan' ---
            $browser->select('#kegiatan-select', $kegiatan2->id)
                ->pause(1500)
                ->assertPathIs('/admin/kegiatan/' . $kegiatan2->id . '/peserta')
                ->assertSee('Peserta untuk: Dusk Gerakan Seribu Pohon')
                // Pastikan data peserta dua muncul
                ->assertSee('Dusk Peserta Dua');
        });
    }
}
