<?php

namespace Tests\Browser;

use App\Models\Donasi;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RiwayatDonasiTest extends DuskTestCase
{
    private $userEmail = 'user@greennovate.test';
    private $userPassword = 'user12345';

    /**
     * Helper untuk melakukan login via form UI
     */
    private function login(Browser $browser)
    {
        $browser->logout()
                ->visit('/login')
                ->type('login', $this->userEmail) // Field email/phone namanya "login"
                ->type('password', $this->userPassword)
                ->press('Masuk')
                ->pause(1000); // Tunggu proses login selesai
        return $browser;
    }

    /**
     * TC-PB17-001: Lihat Riwayat Donasi - Happy Path (Positive)
     */
    public function testLihatRiwayatDonasi()
    {
        $this->browse(function (Browser $browser) {
            $this->login($browser)
                 ->visit('/riwayat')
                 ->assertSee('Riwayat Partisipasi') // Memastikan judul ada
                 ->assertPresent('.filter-btn') // Memastikan komponen filter tersedia
                 ->pause(2000)
                 ->logout();
        });
    }

    /**
     * TC-PB17-002: Filter Donasi & Mapping Status (Positive)
     */
    public function testFilterDonasi()
    {
        $this->browse(function (Browser $browser) {
            $this->login($browser)
                 ->visit('/riwayat')
                 ->clickLink('💝 Donasi')
                 ->pause(2000) // Tunggu halaman reload
                 ->assertQueryStringHas('tipe', 'donasi') // Verifikasi URL query string
                 ->pause(2000)
                 ->logout();
        });
    }

    /**
     * TC-PB17-003: Endpoint Detail Aman - ID Tidak Ada (Negative)
     */
    public function testEndpointDetailAman()
    {
        $this->browse(function (Browser $browser) {
            $this->login($browser)
                 ->visit('/riwayat/donasi/999999/detail') // ID yang tidak ada
                 ->assertDontSee('Exception') // Verifikasi tidak ada Exception
                 ->assertDontSee('Error') // Verifikasi tidak ada Error
                 // Memastikan halaman tidak ditemukan (404)
                 ->assertSee('404')
                 ->pause(2000)
                 ->logout();
        });
    }

    /**
     * TC-PB17-004: Akses Tanpa Login - Redirect ke Login (Negative)
     */
    public function testAksesTanpaLogin()
    {
        $this->browse(function (Browser $browser) {
            $browser->logout() // Pastikan tidak ada session aktif
                    ->visit('/riwayat') // Visit tanpa login
                    ->assertPathIs('/login') // Verifikasi redirect ke halaman login
                    ->pause(2000);
        });
    }

    /**
     * TC-PB17-005: Validasi Data Riwayat Sesuai Database
     */
    public function testValidasiDataRiwayatSesuaiDatabase()
    {
        // 1. Buat data donasi baru langsung ke database via Eloquent
        $user = User::where('email', $this->userEmail)->first();
        $this->assertNotNull($user, 'User testing tidak ditemukan di database.');

        $timestamp = now()->timestamp;
        $donasi = Donasi::create([
            'user_id' => $user->id,
            'nama_donasi' => 'Donasi Validasi Dusk Test',
            'jumlah' => 333000,
            'status' => 'Sukses',
            'kode_transaksi' => 'TRX-VALIDASI-' . $timestamp,
            'nama_donatur' => $user->name,
            'nomor_hp' => '08123456789',
            'metode_pembayaran' => 'Bank Transfer'
        ]);

        $this->browse(function (Browser $browser) use ($donasi) {
            // 2. Login sebagai user pemilik donasi
            $this->login($browser)
                 // 3. Visit /riwayat?tipe=donasi
                 ->visit('/riwayat?tipe=donasi')
                 ->pause(2000)
                 // Klik detail donasi sesuai ID
                 ->click("#riwayat-item-donasi-{$donasi->id}")
                 ->pause(2000)
                 // 4-6. Verifikasi nama, kode transaksi, dan jumlah (dengan assertSourceHas sesuai instruksi)
                 ->assertSourceHas('Donasi Validasi Dusk Test')
                 ->assertSourceHas($donasi->kode_transaksi);
                 
            // Untuk jumlah 333000, di UI kemungkinan besar sudah di-format menjadi Rp 333.000 atau 333.000
            // Jadi kita pastikan saja setidaknya memuat string "333.000" (format Rupiah standar)
            $formattedJumlah = number_format(333000, 0, ',', '.');
            $browser->assertSourceHas($formattedJumlah);
            
            $browser->logout();
        });

        // Cleanup data dummy yang baru saja di-seed
        $donasi->delete();
    }
}
