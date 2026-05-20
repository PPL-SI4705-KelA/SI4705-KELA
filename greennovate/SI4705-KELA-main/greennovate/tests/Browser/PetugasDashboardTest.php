<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PetugasDashboardTest extends DuskTestCase
{
    /**
     * Note: User sudah ada di database dengan password:
     * - petugas@greennovate.test : petugas123
     * - user@greennovate.test : user12345
     */

    /**
     * Test: Petugas berhasil login dan melihat dashboard-nya
     * (Sesuai alur PB-21 yang sudah ada di kode)
     */
    public function test_petugas_can_access_dashboard_and_see_assigned_activities()
    {
        $this->browse(function (Browser $browser) {
            // 1. Login sebagai Petugas
            $browser->visit('/login')
                    ->type('login', 'petugas@greennovate.test')
                    ->type('password', 'petugas123')
                    ->press('Masuk')
                    ->pause(3000);

            // 2. Pastikan redirect ke dashboard petugas
            $browser->assertPathIs('/petugas/dashboard');

            // 3. Cek elemen utama dashboard
            $browser->assertSee('Kegiatan Saya')
                    ->assertSee('Kegiatan Aktif');
        });
    }

    /**
     * Test: Dashboard petugas menampilkan kegiatan aktif
     */
    public function test_petugas_dashboard_shows_active_activities()
    {
        // Jeda 3 detik sebelum test kedua dimulai
        sleep(3);

        $this->browse(function (Browser $browser) {
            // Akses dashboard petugas
            $browser->visit('/petugas/dashboard')
                    ->pause(2000);

            // Verifikasi ada elemen kegiatan aktif
            $browser->assertSee('Kegiatan Aktif')
                    ->assertSee('Catat Realisasi');
        });
    }
}
