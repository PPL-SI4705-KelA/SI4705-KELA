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
    private $petugasEmail = 'petugas@greennovate.test';

    private function loginAs(Browser $browser, string $email): Browser
    {
        $user = User::where('email', $email)->first();
        return $browser->loginAs($user);
    }

    // ─────────────────────────────────────────────────────────────
    // TC-01: Redirect ke Dashboard setelah Login
    // ─────────────────────────────────────────────────────────────

    /**
     * TC-01a: User di-redirect ke /dashboard setelah login.
     */
    public function testRedirectUserKeDashboard()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAs($browser, $this->userEmail)
                 ->visit('/dashboard')
                 ->assertPathIs('/dashboard')
                 ->assertSee('Dashboard')
                 ->pause(15000)
                 ->logout();
        });
    }

    /**
     * TC-01b: Admin di-redirect ke /admin/dashboard setelah login.
     */
    public function testRedirectAdminKeDashboard()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAs($browser, $this->adminEmail)
                 ->visit('/dashboard')
                 ->assertPathIs('/admin/dashboard')
                 ->assertSee('Dashboard')
                 ->pause(15000)
                 ->logout();
        });
    }

    /**
     * TC-01c: Petugas di-redirect ke /petugas/dashboard setelah login.
     */
    public function testRedirectPetugasKeDashboard()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAs($browser, $this->petugasEmail)
                 ->visit('/dashboard')
                 ->assertPathIs('/petugas/dashboard')
                 ->pause(15000)
                 ->logout();
        });
    }

    // ─────────────────────────────────────────────────────────────
    // TC-10: Session Login Expired
    // ─────────────────────────────────────────────────────────────

    /**
     * TC-10b: Akses /admin/dashboard tanpa login di-redirect ke halaman login.
     */
    public function testSessionExpiredAdminDiarahkanKeLogin()
    {
        $this->browse(function (Browser $browser) {
            $browser->logout()
                    ->visit('/admin/dashboard')
                    ->assertPathIs('/login')
                    ->pause(15000);
        });
    }
}
