<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Kegiatan;
use App\Models\Realisasi;
use App\Models\JenisPohon;

class PetugasDashboardTest extends DuskTestCase
{
    private $petugasEmail = 'petugas@greennovate.test';

    /**
     * Helper to retrieve Petugas user
     */
    private function getPetugas()
    {
        return User::where([['email', '=', $this->petugasEmail]])->firstOrFail();
    }

    /**
     * TC-01: Dashboard Load - Happy Path
     */
    public function testDashboardLoadHappyPath()
    {
        $petugas = $this->getPetugas();

        $this->browse(function (Browser $browser) use ($petugas) {
            $browser->loginAs($petugas)
                    ->visit('/petugas/dashboard')
                    ->assertSee('Selamat')
                    ->assertSee($petugas->name)
                    ->assertSee('Kegiatan Aktif')
                    ->assertSee('Semua Kegiatan')
                    ->resize(375, 667)
                    ->assertSee('Kegiatan Aktif')
                    ->resize(1920, 1080);

            $browser->pause(15000); // Rest time 15 detik
        });
    }

    /**
     * TC-02 & TC-05: View Active Activities
     */
    public function testViewActiveActivities()
    {
        $petugas = $this->getPetugas();

        $this->browse(function (Browser $browser) use ($petugas) {
            $browser->loginAs($petugas)
                    ->visit('/petugas/dashboard')
                    ->waitForText('Kegiatan Aktif', 10)
                    ->assertSee('Kegiatan Aktif');

            if ($browser->elements('.grid-cols-1 > div')) {
                // Asumsikan setidaknya satu kegiatan dirender
                $browser->assertSee('Berlangsung')
                        ->assertSee('Pohon Ditanam')
                        ->assertSee('Catat Realisasi')
                        ->assertSee('Dokumentasi');
            }

            $browser->pause(15000); // Rest time 15 detik
        });
    }

    /**
     * TC-04: Quick Action - Catar Realisasi
     */
    public function testQuickActionCatarRealisasi()
    {
        $petugas = $this->getPetugas();

        $this->browse(function (Browser $browser) use ($petugas) {
            $browser->loginAs($petugas)
                    ->visit('/petugas/dashboard');

            // Cek apakah ada tautan Catat Realisasi
            $elements = $browser->elements('a[href*="/petugas/realisasi"]');
            if (count($elements) > 0) {
                // Navigate to it
                $browser->click('a[href*="/petugas/realisasi"]')
                        ->waitForText('Catat Realisasi Penanaman', 10)
                        ->assertSee('Catat Realisasi Penanaman')
                        ->assertVisible('#jenis_pohon_id')
                        ->assertVisible('#jumlah_tertanam');
            }

            $browser->pause(15000); // Rest time 15 detik
        });
    }

    /**
     * TC-06: Search & Filter - Activity List
     */
    public function testSearchAndFilterActivityList()
    {
        $petugas = $this->getPetugas();

        $this->browse(function (Browser $browser) use ($petugas) {
            $browser->loginAs($petugas)
                    // Pindah ke halaman semua-kegiatan terlebih dahulu
                    ->visit('/petugas/semua-kegiatan')
                    ->waitFor('#searchInput', 10)
                    ->assertVisible('#searchInput')
                    ->type('#searchInput', 'Pohon')
                    ->pause(1000) // tunggu debounce
                    ->assertVisible('table')
                    
                    ->select('status', 'Berlangsung') // name="status"
                    ->pause(1000)
                    ->assertVisible('table');

            $browser->pause(15000); // Rest time 15 detik
        });
    }

    /**
     * TC-08: Empty State - No Active Activities
     */
    public function testEmptyStateNoActiveActivities()
    {
        // Setup user dummy yang belum punya kegiatan
        $dummyUser = User::factory()->create([
            'role' => 'petugas',
        ]);

        $this->browse(function (Browser $browser) use ($dummyUser) {
            $browser->loginAs($dummyUser)
                    ->visit('/petugas/dashboard')
                    ->waitForText('Tidak ada kegiatan aktif saat ini', 10)
                    ->assertSee('Tidak ada kegiatan aktif saat ini')
                    ->assertSee('Lihat Semua Kegiatan');

            $browser->pause(15000); // Rest time 15 detik
        });

        $dummyUser->delete();
    }

    /**
     * TC-09: Mobile Responsive Layout
     */
    public function testMobileResponsiveLayout()
    {
        $petugas = $this->getPetugas();

        $this->browse(function (Browser $browser) use ($petugas) {
            // Mobile
            $browser->resize(375, 667)
                    ->loginAs($petugas)
                    ->visit('/petugas/dashboard')
                    ->pause(1000)
                    ->assertPresent('#hamburgerBtn') // Element exists
                    // Tablet
                    ->resize(768, 1024)
                    // Desktop
                    ->resize(1920, 1080)
                    ->pause(500)
                    ->assertPresent('.sidebar');

            $browser->pause(15000); // Rest time 15 detik
        });
    }

    /**
     * TC-10: Authorization & Access Control
     */
    public function testAuthorizationAndAccessControl()
    {
        $petugas = $this->getPetugas();
        
        $this->browse(function (Browser $browser) use ($petugas) {
            $browser->loginAs($petugas)
                    ->visit('/petugas/kegiatan/99999999-9999-9999-9999-999999999999')
                    ->assertSee('404')
                    ->logout();
            
            $browser->visit('/petugas/dashboard')
                    ->assertPathIs('/login');

            $browser->pause(15000); // Rest time 15 detik
        });
    }
}
