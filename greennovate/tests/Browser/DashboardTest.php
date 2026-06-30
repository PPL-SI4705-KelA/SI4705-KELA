<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Kegiatan;
use App\Models\DokumentasiKegiatan;
use Illuminate\Support\Facades\Storage;

class DashboardTest extends DuskTestCase
{
    private $userEmail    = 'user@greennovate.test';
    private $adminEmail   = 'pardede281204@gmail.com';

    private function loginAs(Browser $browser, string $email): Browser
    {
        $user = User::where('email', $email)->first();
        return $browser->loginAs($user);
    }

    // TC-PB4-001: User di-redirect ke /dashboard setelah login dan Dashboard User tampil.
    
    public function testRedirectUserKeDashboard()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAs($browser, $this->userEmail)
                 ->visit('/dashboard')
                 ->assertPathIs('/dashboard')
                 ->assertSee('Dashboard')
                 ->assertSee('Pencapaian O2 Anda')
                 ->assertSee('Hubungi Admin')
                 ->assertSee('Riwayat Partisipasi')
                 ->pause(3000)
                 ->logout();
        });
    }

    // TC-PB4-002: Admin di-redirect ke /admin/dashboard setelah login dan Dashboard Admin tampil.

    public function testRedirectAdminKeDashboard()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAs($browser, $this->adminEmail)
                 ->visit('/dashboard')
                 ->assertPathIs('/admin/dashboard')
                 ->assertSee('Dashboard')
                 ->assertSee('TUGAS VALIDASI & KEUANGAN')
                 ->assertSee('STATISTIK SISTEM')
                 ->pause(3000)
                 ->logout();
        });
    }
    
    // TC-PB4-003: User akses /admin/dashboard → 403 Forbidden.
    
    public function testUserAksesAdminDashboardForbidden()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAs($browser, $this->userEmail)
                 ->visit('/admin/dashboard')
                 ->assertSee('Anda tidak memiliki akses ke halaman ini')
                 ->pause(3000)
                 ->logout();
        });
    }

    // TC-PB4-004: Guest akses /dashboard → Redirect Login.
    
    public function testGuestAksesDashboardRedirectLogin()
    {
        $this->browse(function (Browser $browser) {
            $browser->logout()
                    ->visit('/dashboard')
                    ->assertPathIs('/login')
                    ->pause(3000);
        });
    }

    // TC-PB4-005: Guest akses /admin/dashboard → Redirect Login.

    public function testGuestAksesAdminDashboardRedirectLogin()
    {
        $this->browse(function (Browser $browser) {
            $browser->logout()
                    ->visit('/admin/dashboard')
                    ->assertPathIs('/login')
                    ->pause(3000);
        });
    }
}