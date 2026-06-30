<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\QrCode;

class GenerateQrCodePohonTest extends DuskTestCase
{
    private $adminEmail = 'pardede281204@gmail.com';
    private $userEmail = 'user@greennovate.test';

    protected function setUp(): void
    {
        parent::setUp();
        
        // Bersihkan data qrcode dummy jika ada sebelum test dimulai agar tidak menumpuk
        QrCode::where('judul', 'Pohon Dummy Untuk Test')->delete();
    }

    private function loginAs(Browser $browser, $email)
    {
        $user = User::where('email', $email)->first();
        return $browser->loginAs($user);
    }

    /**
     * Test 1: Admin dapat membuka halaman Generate QR Code
     */
    public function testAdminAksesHalamanQrCode()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAs($browser, $this->adminEmail)
                 ->visit('/admin/qrcode')
                 ->waitForText('Kelola QR Code', 10)
                 ->assertSee('Generate QR Code Baru')
                 ->assertPresent('input[name="judul"]')
                 ->assertPresent('input[name="link"]')
                 ->pause(15000)
                 ->logout();
        });
    }

    /**
     * Test 2: Admin berhasil melakukan Generate QR Code
     */
    public function testAdminGenerateQrCode()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAs($browser, $this->adminEmail)
                 ->visit('/admin/qrcode')
                 ->type('judul', 'Pohon Dummy Untuk Test')
                 ->type('link', 'https://drive.google.com/drive/folders/dummy-123')
                 ->press('Generate QR')
                 ->waitForText('QR Code berhasil dibuat!', 10)
                 ->assertSee('Pohon Dummy Untuk Test')
                 ->assertSee('https://drive.google.com/drive/folders/dummy-123')
                 ->pause(15000)
                 ->logout();
        });

        // Verifikasi database bahwa QR code berhasil disimpan
        $this->assertTrue(QrCode::where('judul', 'Pohon Dummy Untuk Test')
            ->where('link', 'https://drive.google.com/drive/folders/dummy-123')
            ->exists());
    }

    /**
     * Test 3: User biasa tidak dapat mengakses halaman Generate QR Code
     */
    public function testUserBiasaAksesDitolak()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAs($browser, $this->userEmail)
                 ->visit('/admin/qrcode')
                 // Harus mengembalikan 403 dan pesan tidak memiliki akses
                 ->assertSee('403')
                 ->assertSee('Anda tidak memiliki akses ke halaman ini')
                 ->pause(15000)
                 ->logout();
        });
    }
}
