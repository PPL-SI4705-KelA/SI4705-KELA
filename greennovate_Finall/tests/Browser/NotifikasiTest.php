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
            Notifikasi::where('user_id', $user->id)->where('judul', 'LIKE', 'TEST DUSK%')->delete();
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
     * TC-11 & TC-01: Akses Notifikasi
     */
    public function testAksesHalamanNotifikasi()
    {
        $this->createDummyNotif(false);

        $this->browse(function (Browser $browser) {
            $this->login($browser)
                 ->visit('/notifikasi')
                 ->assertSee('TEST DUSK - Notifikasi')
                 ->pause(15000)
                 ->logout();
        });
    }

    /**
     * TC-04: Tidak ada notifikasi belum dibaca
     */
    public function testTidakAdaNotifikasiBaru()
    {
        // Pastikan tidak ada notifikasi yang belum dibaca sama sekali
        $user = User::where('email', $this->userEmail)->first();
        Notifikasi::where('user_id', $user->id)->update(['is_read' => DB::raw('true')]);

        $this->browse(function (Browser $browser) {
            $this->login($browser)
                 ->visit('/notifikasi')
                 ->assertSee('Tidak ada notifikasi baru')
                 ->pause(15000)
                 ->logout();
        });
    }

    /**
     * TC-02: Tandai sudah dibaca pada satu notifikasi
     */
    public function testTandaiSatuSudahDibaca()
    {
        $notif = $this->createDummyNotif(false);

        $this->browse(function (Browser $browser) use ($notif) {
            $this->login($browser)
                 ->visit('/notifikasi')
                 ->assertSee('TEST DUSK - Notifikasi')
                 ->press('Tandai sudah dibaca')
                 ->waitUntilMissingText('TEST DUSK - Notifikasi', 5) // Tunggu sampai animasi JS selesai dan elemen dihapus
                 ->pause(15000)
                 ->logout();
        });
    }

    /**
     * TC-03: Tandai semua sudah dibaca
     */
    public function testTandaiSemuaSudahDibaca()
    {
        $this->createDummyNotif(false);
        $this->createDummyNotif(false);

        $this->browse(function (Browser $browser) {
            $this->login($browser)
                 ->visit('/notifikasi')
                 ->press('Tandai semua sudah dibaca')
                 ->waitForText('Semua notifikasi telah ditandai', 5) // Tunggu alert success muncul dari JS
                 ->waitForText('Tidak ada notifikasi baru', 5) // Tunggu state empty dirender JS
                 ->pause(15000)
                 ->logout();
        });
    }

    /**
     * TC-07: Buka tab 'Sudah Dibaca'
     */
    public function testMembukaTabSudahDibaca()
    {
        $this->createDummyNotif(true);

        $this->browse(function (Browser $browser) {
            $this->login($browser)
                 ->visit('/notifikasi')
                 ->clickLink('Sudah Dibaca') // Pindah ke tab sudah dibaca
                 ->pause(500)
                 ->assertSee('TEST DUSK - Notifikasi') // Notifikasi yg is_read=true harus tampil
                 ->pause(15000)
                 ->logout();
        });
    }

    /**
     * TC-06: Akses Notifikasi Orang Lain (Negative)
     */
    public function testAksesNotifikasiOrangLain()
    {
        // Buat notif milik user admin/lainnya
        $otherUser = User::where('email', '!=', $this->userEmail)->first();
        $notifLain = $this->createDummyNotif(false, $otherUser->id);

        $this->browse(function (Browser $browser) use ($notifLain) {
            $this->login($browser)
                 ->visit('/notifikasi')
                 // Secara default URL update notifikasi biasanya lewat POST/PATCH
                 // Jika ada form dengan action /notifikasi/{id}/baca
                 // Kita coba paksa visit jika route nya GET (atau cek respon access)
                 // Untuk Dusk, test aksi menolak ini dengan memastikan notif tidak dirender
                 ->assertDontSee($notifLain->judul)
                 ->pause(15000)
                 ->logout();
        });
    }
}
