<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LandingPageTest extends DuskTestCase
{
    /**
     * TC-01: Menampilkan landing page dengan info dasar dan CTA
     */
    public function testMenampilkanLandingPageDanInfoDasar()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('Greennovate')
                    ->assertSee('Daftar Gratis');
        });
    }

    /**
     * TC-02: Klik CTA mengarah ke halaman registrasi
     */
    public function testKlikCTAMengarahKeRegister()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->click('#hero-register-btn') // ID CTA di welcome.blade.php
                    ->assertPathIs('/register');
        });
    }

    /**
     * TC-03: Aset gambar gagal load menampilkan placeholder
     */
    public function testAsetGambarGagalLoadMenampilkanPlaceholder()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->script("
                        // Trigger error secara manual pada gambar hero
                        document.getElementById('hero-img').dispatchEvent(new Event('error'));
                    ");

            // Tunggu dan pastikan placeholder foto muncul (display:flex / display:block)
            $browser->pause(500)
                    ->assertSee('Foto Lahan Penghijauan');
        });
    }
}
