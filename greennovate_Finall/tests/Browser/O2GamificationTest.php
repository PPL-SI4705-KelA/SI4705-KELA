<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class O2GamificationTest extends DuskTestCase
{
    /**
     * A Dusk test to verify O2 gamification tab and delay 10s.
     */
    public function testGamificationTab()
    {
        $this->browse(function (Browser $browser) {
            $user = User::where('role', 'user')->first();
            
            if (!$user) {
                $user = User::factory()->create([
                    'role' => 'user',
                    'password' => bcrypt('password')
                ]);
            }

            $browser->loginAs($user)
                    ->visit('/profile')
                    ->pause(2000)
                    ->click('#tab-btn-achievements')
                    ->pause(1000)
                    ->assertSee('Pencapaian O2 Anda')
                    ->assertSee('TOTAL O2') // CSS class 'uppercase'
                    ->assertSee('TOTAL POHON') // CSS class 'uppercase'
                    ->assertSee('Koleksi Badge Anda')
                    ->pause(10000); // DELAY 10 DETIK AS REQUESTED
        });
    }
}
