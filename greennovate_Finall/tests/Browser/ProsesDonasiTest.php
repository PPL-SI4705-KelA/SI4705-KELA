<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Kegiatan;
use App\Models\Donasi;
use Illuminate\Support\Facades\File;

class ProsesDonasiTest extends DuskTestCase
{
    private $userEmail = 'user@greennovate.test';
    private $userPassword = 'user12345';
    private $dummyPng;
    private $dummyPdf;

    protected function setUp(): void
    {
        parent::setUp();

        // Gunakan file spesifik yang diminta pengguna
        $this->dummyPng = 'C:\PPL-SI4705-KelA\WIN_20260428_15_45_51_Pro.jpg';
        
        $this->dummyPdf = storage_path('app/public/test_donasi.pdf');
        if (!File::exists($this->dummyPdf)) {
            File::put($this->dummyPdf, 'Dummy PDF content');
        }
        
        // Buat dummy image fallback jika file spesifik tidak ada
        if (!File::exists($this->dummyPng)) {
            $this->dummyPng = storage_path('app/public/test_donasi.png');
            if (!File::exists($this->dummyPng)) {
                File::put($this->dummyPng, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII='));
            }
        }
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



    /**
     * TC-01: Membuka halaman donasi
     */
    public function testMembukaHalamanDonasi()
    {
        $this->browse(function (Browser $browser) {
            $this->login($browser)
                 ->visit('/donations')
                 ->assertSee('Donasi Penghijauan')
                 ->assertSee('-- Pilih Kegiatan Penghijauan --')
                 ->pause(15000)
                 ->logout();
        });
    }

    /**
     * TC-03: Validasi field wajib kosong
     */
    public function testValidasiFieldWajibKosong()
    {
        $this->browse(function (Browser $browser) {
            $this->login($browser)
                 ->visit('/donations')
                 ->script("Array.from(document.querySelectorAll('button')).find(el => el.textContent.includes('Lanjutkan Konfirmasi')).click();");
            
            $browser->pause(1000)
                    ->assertPathIs('/donations') // Tidak berpindah halaman
                    ->pause(15000)
                    ->logout();
        });
    }

    /**
     * TC-02 & TC-04: Flow membuat transaksi Donasi dari input hingga ke halaman Pembayaran
     */
    public function testAlurPembuatanTransaksiDonasi()
    {
        $kegiatan = Kegiatan::where('status', 'Berlangsung')->first();
        if (!$kegiatan) {
            $this->markTestSkipped('Butuh minimal 1 kegiatan berlangsung di database');
        }

        $this->browse(function (Browser $browser) use ($kegiatan) {
            $this->login($browser)
                 ->visit('/donations')
                 ->select('kegiatan_id', $kegiatan->id)
                 ->type('nama_donatur', 'Test Automasi Dusk')
                 ->type('nomor_hp', '08123456789')
                 ->type('jumlah', '150000')
                 ->type('catatan', 'Doa terbaik untuk alam')
                 ->script("Array.from(document.querySelectorAll('button')).find(el => el.textContent.includes('Lanjutkan Konfirmasi')).click();");
            
            $browser->pause(1000)
                 // Berhasil redirect ke halaman konfirmasi
                 ->assertPathIs('/donations/confirmation')
                 ->assertSee('Ringkasan Transaksi')
                 ->assertSee('150.000')
                 
                 // Lanjut klik Lanjut Pembayaran (Membuat transaksi di DB)
                 ->script("Array.from(document.querySelectorAll('button')).find(el => el.textContent.includes('Lanjut Pembayaran')).click();");
            
            $browser->pause(1000)
                 // Redirect ke detail pembayaran (url: /donations/payment/{donasi})
                 ->assertPathBeginsWith('/donations/payment/')
                 ->assertSee('Invoice Pembayaran')
                 ->pause(15000)
                 ->logout();
        });
    }


}
