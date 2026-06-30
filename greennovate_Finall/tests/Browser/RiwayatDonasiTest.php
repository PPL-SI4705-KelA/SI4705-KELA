<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RiwayatDonasiTest extends DuskTestCase
{
    // Menggunakan akun asli yang sudah ada di database sesuai permintaan
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
                ->press('Masuk') // Sesuai teks di tombol auth/login.blade.php
                ->pause(1000); // Tunggu proses login selesai
        return $browser;
    }

    /**
     * TC-01: Lihat Riwayat Donasi (Happy Path)
     * Hanya memastikan halaman dapat diakses dan menampilkan komponen utama.
     */
    public function testLihatRiwayatDonasi()
    {
        $this->browse(function (Browser $browser) {
            $this->login($browser)
                 ->visit('/riwayat')
                 ->assertSee('Riwayat Partisipasi') // Memastikan halaman sukses dimuat
                 ->assertPresent('.filter-btn') // Memastikan filter ada
                 ->pause(15000)
                 ->logout();
        });
    }

    /**
     * TC-02 & TC-05: Mapping Status Pembayaran / Filter
     * Memastikan filter jalan tanpa error.
     */
    public function testFilterDonasi()
    {
        $this->browse(function (Browser $browser) {
            $this->login($browser)
                 ->visit('/riwayat')
                 ->clickLink('💝 Donasi')
                 ->pause(1000)
                 ->assertPathIs('/riwayat')
                 ->assertQueryStringHas('tipe', 'donasi')
                 ->pause(15000)
                 ->logout();
        });
    }

    /**
     * TC-03 & TC-04: Akses Endpoint Detail Donasi
     * Hanya memastikan endpoint API tidak error 500.
     */
    public function testEndpointDetailAman()
    {
        $this->browse(function (Browser $browser) {
            $this->login($browser)
                 ->visit('/riwayat/donasi/999999/detail') // ID acak
                 ->assertDontSee('Exception')
                 ->assertDontSee('Error')
                 ->pause(15000)
                 ->logout();
        });
    }

    /**
     * TC-06: Akses Tanpa Login
     */
    public function testAksesTanpaLogin()
    {
        $this->browse(function (Browser $browser) {
            $browser->logout() // Pastikan belum login
                    ->visit('/riwayat')
                    // Pastikan redirect ke login
                    ->assertPathIs('/login')
                    ->pause(15000);
        });
    }
}
