<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Donasi;
use App\Models\Kegiatan;
use Illuminate\Support\Facades\File;

class BuktiPembayaranTest extends DuskTestCase
{
    private $userEmail = 'user@greennovate.test';
    private $userPassword = 'user12345';
    private $dummyPng;
    private $dummyPdf;
    private $dummyLarge;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat file dummy untuk testing upload
        $this->dummyPng = storage_path('app/public/test_dummy.png');
        $this->dummyPdf = storage_path('app/public/test_dummy.pdf');
        
        if (!File::exists($this->dummyPng)) {
            // 1x1 pixel PNG
            File::put($this->dummyPng, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII='));
        }
        if (!File::exists($this->dummyPdf)) {
            File::put($this->dummyPdf, 'Dummy PDF content');
        }
    }

    protected function tearDown(): void
    {
        // Jangan hapus dummy image di sini jika upload memindahkannya.
        // Biarkan saja, atau cleanup database donasi testing.
        parent::tearDown();
    }

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

    private function getTestDonasi($status = 'Pending')
    {
        $user = User::where([['email', '=', $this->userEmail]])->first();
        $kegiatan = Kegiatan::first(); // Gunakan kegiatan yang sudah ada agar terhindar constraint DB

        if (!$user || !$kegiatan) {
            return null;
        }

        return Donasi::updateOrCreate(
            ['kode_transaksi' => 'TEST-UPLOAD-' . strtoupper($status)],
            [
                'user_id' => $user->id,
                'kegiatan_id' => $kegiatan->id,
                'nama_donasi' => 'Test Donasi Upload ' . $status,
                'nama_donatur' => 'User Test',
                'nomor_hp' => '08123456789',
                'jumlah' => 150000,
                'metode_pembayaran' => 'Transfer Bank',
                'status' => $status,
            ]
        );
    }

    private function getOtherUserDonasi()
    {
        $otherUser = User::where([['email', '!=', $this->userEmail]])->first();
        $kegiatan = Kegiatan::first();

        if (!$otherUser || !$kegiatan) {
            return null;
        }

        return Donasi::updateOrCreate(
            ['kode_transaksi' => 'TEST-UPLOAD-OTHER'],
            [
                'user_id' => $otherUser->id,
                'kegiatan_id' => $kegiatan->id,
                'nama_donasi' => 'Donasi Orang Lain',
                'nama_donatur' => 'Other User',
                'nomor_hp' => '08123456789',
                'jumlah' => 50000,
                'metode_pembayaran' => 'Transfer Bank',
                'status' => 'Pending',
            ]
        );
    }

    /**
     * TC-11: Akses tanpa login
     */
    public function testAksesTanpaLogin()
    {
        $this->browse(function (Browser $browser) {
            $browser->logout()
                    ->visit('/pembayaran/donasi/1')
                    ->assertPathIs('/login');
        });
    }

    /**
     * TC-01 & TC-12: Halaman instruksi tampil sesuai database
     */
    public function testHalamanInstruksiTampil()
    {
        $donasi = $this->getTestDonasi('Pending');
        if (!$donasi) {
            $this->markTestSkipped('Data kegiatan/user tidak ditemukan.');
        }

        $this->browse(function (Browser $browser) use ($donasi) {
            $this->login($browser)
                 ->visit('/pembayaran/donasi/' . $donasi->id)
                 ->assertSee('Instruksi Pembayaran')
                 ->assertSee('Test Donasi Upload Pending')
                 ->assertSee('150.000') // Format disesuaikan dengan view
                 ->logout();
        });
    }

    /**
     * TC-04: Upload tanpa file
     */
    public function testUploadTanpaFile()
    {
        $donasi = $this->getTestDonasi('Pending');
        if (!$donasi) $this->markTestSkipped();

        $this->browse(function (Browser $browser) use ($donasi) {
            $this->login($browser)
                 ->visit('/pembayaran/donasi/' . $donasi->id)
                 ->press('Kirim') // Tombol kirim form
                 ->pause(500)
                 ->assertSee('wajib') // "Bukti transfer wajib diunggah"
                 ->logout();
        });
    }

    /**
     * TC-05: Upload format tidak valid
     */
    public function testUploadFormatSalah()
    {
        $donasi = $this->getTestDonasi('Pending');
        if (!$donasi) $this->markTestSkipped();

        $this->browse(function (Browser $browser) use ($donasi) {
            $this->login($browser)
                 ->visit('/pembayaran/donasi/' . $donasi->id)
                 ->attach('bukti_dokumentasi', $this->dummyPdf) // Atribut name file input
                 ->press('Kirim')
                 ->pause(500)
                 ->assertSee('tidak valid') // "Format file tidak valid..."
                 ->logout();
        });
    }

    /**
     * TC-07: Form hilang saat Menunggu Konfirmasi
     */
    public function testFormHilangSaatMenungguKonfirmasi()
    {
        $donasi = $this->getTestDonasi('Menunggu Konfirmasi');
        if (!$donasi) $this->markTestSkipped();

        $this->browse(function (Browser $browser) use ($donasi) {
            $this->login($browser)
                 ->visit('/pembayaran/donasi/' . $donasi->id)
                 ->assertSee('sedang diverifikasi') // "Bukti pembayaran Anda sedang diverifikasi oleh Admin"
                 ->assertDontSee('Kirim') // Tombol form hilang
                 ->logout();
        });
    }

    /**
     * TC-08: Form aktif saat Ditolak
     */
    public function testFormAktifSaatDitolak()
    {
        $donasi = $this->getTestDonasi('Ditolak');
        if (!$donasi) $this->markTestSkipped();

        $this->browse(function (Browser $browser) use ($donasi) {
            $this->login($browser)
                 ->visit('/pembayaran/donasi/' . $donasi->id)
                 ->assertSee('ditolak') // Pesan penolakan
                 ->assertSee('Kirim') // Form kembali muncul
                 ->logout();
        });
    }

    /**
     * TC-10: Akses milik user lain (403)
     */
    public function testAksesMilikUserLain()
    {
        $otherDonasi = $this->getOtherUserDonasi();
        if (!$otherDonasi) $this->markTestSkipped();

        $this->browse(function (Browser $browser) use ($otherDonasi) {
            $this->login($browser)
                 ->visit('/pembayaran/donasi/' . $otherDonasi->id)
                 ->assertSee('403') // Atau pesan error permission
                 ->logout();
        });
    }
}
