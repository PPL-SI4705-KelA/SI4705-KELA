<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;

class O2AchievementTest extends DuskTestCase
{
    private $userEmail = 'user@greennovate.test';

    private function loginAs(Browser $browser, $email)
    {
        $user = User::where('email', $email)->first();
        return $browser->loginAs($user);
    }

    /**
     * Test 1: User dapat membuka tab Pencapaian O2 di halaman profil
     */
    public function testUserMembukaTabPencapaianO2()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAs($browser, $this->userEmail)
                 ->visit('/profile')
                 ->waitForText('Pencapaian O2', 10)
                 ->script("document.getElementById('tab-btn-achievements').click();");
            $browser->pause(1000)
                 ->assertSee('Pencapaian O2 Anda')
                 ->assertSourceHas('Total O2')
                 ->assertSourceHas('Total Pohon')
                 ->pause(15000)
                 ->logout();
        });
    }

    /**
     * Test 2: Endpoint /o2/stats mengembalikan data statistik O2 yang valid
     */
    public function testEndpointO2Stats()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAs($browser, $this->userEmail)
                 ->visit('/o2/stats')
                 ->pause(1000)
                 // Karena berupa JSON response, assert konten teksnya
                 ->assertSee('total_pohon')
                 ->assertSee('total_o2_kg_per_bulan')
                 ->pause(15000)
                 ->logout();
        });
    }

    /**
     * Test 3: Endpoint /achievement/progress mengembalikan data progress badge
     */
    public function testEndpointAchievementProgress()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAs($browser, $this->userEmail)
                 ->visit('/achievement/progress')
                 ->pause(1000)
                 ->assertSee('current_o2')
                 ->assertSee('achieved')
                 ->pause(15000)
                 ->logout();
        });
    }
}
