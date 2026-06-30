<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\DB;

class NotifikasiTest extends DuskTestCase
{
    private $userEmail = 'user@greennovate.test';
    private $userPassword = 'user12345';

    protected function setUp(): void
    {
        parent::setUp();

        // Bersihkan data dummy dari test sebelumnya
        $user = User::where('email', $this->userEmail)->first();

        if ($user) {
            Notifikasi::where('user_id', $user->id)
                ->where('judul', 'LIKE', 'TEST DUSK%')
                ->delete();
        }
    }

    /**
     * Helper untuk login
     */
    private function login(Browser $browser)
    {
        $browser->logout()
                ->visit('/login')
                ->type('login', $this->userEmail)
                ->type('password', $this->userPassword)
                ->press('Masuk')
                ->pause(1000);

        return $browser;
    }

    /**
     * Membuat dummy notifikasi
     */
    private function createDummyNotif($isRead = false, $userId = null)
    {
        if (!$userId) {
            $user = User::where('email', $this->userEmail)->first();
            $userId = $user->id;
        }

        return Notifikasi::create([
            'user_id' => $userId,
            'judul' => 'TEST DUSK - Notifikasi',
            'pesan' => 'Ini adalah pesan notifikasi testing.',
            'tipe' => 'sistem',
            'is_read' => DB::raw($isRead ? 'true' : 'false'),
            'read_at' => $isRead ? now() : null,
        ]);
    }

    /**
     * TC-01: Akses Halaman Notifikasi
     */
    public function testAksesHalamanNotifikasi()
    {
        $this->createDummyNotif(false);

        $this->browse(function (Browser $browser) {
            $this->login($browser)
                 ->visit('/notifikasi')
                 ->assertSee('TEST DUSK - Notifikasi')
                 ->pause(2000)
                 ->logout();
        });
    }

    /**
     * TC-02: Tandai Satu Notifikasi Sudah Dibaca
     */
    public function testTandaiSatuSudahDibaca()
    {
        $this->createDummyNotif(false);

        $this->browse(function (Browser $browser) {
            $this->login($browser)
                 ->visit('/notifikasi')
                 ->assertSee('TEST DUSK - Notifikasi')
                 ->press('Tandai sudah dibaca')
                 ->waitUntilMissingText('TEST DUSK - Notifikasi', 5)
                 ->pause(2000)
                 ->logout();
        });
    }

    /**
     * TC-03: Tandai Semua Notifikasi Sudah Dibaca
     */
    public function testTandaiSemuaSudahDibaca()
    {
        $this->createDummyNotif(false);
        $this->createDummyNotif(false);

        $this->browse(function (Browser $browser) {
            $this->login($browser)
                 ->visit('/notifikasi')
                 ->press('Tandai semua sudah dibaca')
                 ->waitForText('Semua notifikasi telah ditandai', 5)
                 ->waitForText('Tidak ada notifikasi baru', 5)
                 ->pause(2000)
                 ->logout();
        });
    }

    /**
     * TC-04: Tidak Ada Notifikasi Baru
     */
    public function testTidakAdaNotifikasiBaru()
    {
        $user = User::where('email', $this->userEmail)->first();

        Notifikasi::where('user_id', $user->id)
            ->update([
                'is_read' => DB::raw('true')
            ]);

        $this->browse(function (Browser $browser) {
            $this->login($browser)
                 ->visit('/notifikasi')
                 ->assertSee('Tidak ada notifikasi baru')
                 ->pause(2000)
                 ->logout();
        });
    }

    /**
     * TC-05: Membuka Tab Sudah Dibaca
     */
    public function testMembukaTabSudahDibaca()
    {
        $this->createDummyNotif(true);

        $this->browse(function (Browser $browser) {
            $this->login($browser)
                 ->visit('/notifikasi')
                 ->clickLink('Sudah Dibaca')
                 ->pause(500)
                 ->assertSee('TEST DUSK - Notifikasi')
                 ->pause(2000)
                 ->logout();
        });
    }
}