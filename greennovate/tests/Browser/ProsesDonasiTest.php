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
        $this->dummyPng = 'C:\Users\LENOVO\Downloads\greennovate_Fin2all\greennovate_Finall\WIN_20260428_15_45_51_Pro.jpg';
        
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

    // TC-PB16-001: Membuka halaman donasi setelah login

    public function testMembukaHalamanDonasiSetelahLogin()
    {
        $this->browse(function (Browser $browser) {
            $this->login($browser)
                 ->visit('/donations')
                 ->assertPathIs('/donations')
                 ->assertSee('Donasi Penghijauan')
                 ->assertSee('-- Pilih Kegiatan Penghijauan --')
                 ->pause(3000)
                 ->logout();
        });
    }

    // TC-PB16-002: Berhasil mengisi form donasi dengan data valid

    public function testBerhasilMengisiFormDonasiDenganDataValid()
    {
        $kegiatan = Kegiatan::where('status', 'Berlangsung')->first();
        if (!$kegiatan) {
            $this->markTestSkipped('Butuh minimal 1 kegiatan berlangsung di database');
        }

        $this->browse(function (Browser $browser) use ($kegiatan) {
            $this->login($browser)
                 ->visit('/donations')
                 ->select('kegiatan_id', $kegiatan->id)
                 ->type('nama_donatur', 'Test Donatur Valid')
                 ->type('nomor_hp', '08123456789')
                 ->type('jumlah', '150000')
                 ->type('catatan', 'Ini catatan valid')
                 ->script("Array.from(document.querySelectorAll('button')).find(el => el.textContent.includes('Lanjutkan Konfirmasi')).click();");

            $browser->pause(1000)
                 ->assertPathIs('/donations/confirmation')
                 ->assertSee('Ringkasan Transaksi')
                 ->assertSee('Test Donatur Valid')
                 ->assertSee('150.000')
                 ->pause(3000)
                 ->logout();
        });
    }

    // TC-PB16-003: Validasi field wajib kosong

    public function testValidasiFieldWajibKosong()
    {
        $this->browse(function (Browser $browser) {
            $this->login($browser)
                 ->visit('/donations')
                 ->clear('nama_donatur')
                 ->script("Array.from(document.querySelectorAll('button')).find(el => el.textContent.includes('Lanjutkan Konfirmasi')).click();");
            
            $browser->pause(1000)
                 ->assertPathIs('/donations') // Tidak berpindah halaman
                 ->assertSee('Program atau kegiatan donasi wajib dipilih.')
                 ->assertSee('Nama donatur wajib diisi.')
                 ->assertSee('Nomor HP wajib diisi.')
                 ->assertSee('Nominal donasi wajib diisi.')
                 ->pause(3000)
                 ->logout();
        });
    }

    // TC-PB16-004: Validasi nomor HP tidak valid

    public function testValidasiNomorHpTidakValid()
    {
        $kegiatan = Kegiatan::where('status', 'Berlangsung')->first();
        if (!$kegiatan) {
            $this->markTestSkipped('Butuh minimal 1 kegiatan berlangsung di database');
        }

        $this->browse(function (Browser $browser) use ($kegiatan) {
            $this->login($browser)
                 ->visit('/donations')
                 ->select('kegiatan_id', $kegiatan->id)
                 ->type('nomor_hp', '123')
                 ->type('jumlah', '15000')
                 ->script("Array.from(document.querySelectorAll('button')).find(el => el.textContent.includes('Lanjutkan Konfirmasi')).click();");

            $browser->pause(1000)
                 ->assertPathIs('/donations')
                 ->assertSee('Nomor HP minimal terdiri dari 10 digit.')
                 ->pause(3000)
                 ->logout();
        });
    }

    // TC-PB16-005: Berhasil membuat transaksi donasi (status Menunggu Pembayaran)
    
    public function testBerhasilMembuatTransaksiDonasi()
    {
        $kegiatan = Kegiatan::where('status', 'Berlangsung')->first();
        if (!$kegiatan) {
            $this->markTestSkipped('Butuh minimal 1 kegiatan berlangsung di database');
        }

        $this->browse(function (Browser $browser) use ($kegiatan) {
            $this->login($browser)
                 ->visit('/donations')
                 ->select('kegiatan_id', $kegiatan->id)
                 ->type('nama_donatur', 'Test Donatur Transaksi')
                 ->type('nomor_hp', '08123456789')
                 ->type('jumlah', '150000')
                 ->script("Array.from(document.querySelectorAll('button')).find(el => el.textContent.includes('Lanjutkan Konfirmasi')).click();");

            $browser->pause(1000)
                 ->assertPathIs('/donations/confirmation')
                 ->script("Array.from(document.querySelectorAll('button')).find(el => el.textContent.includes('Lanjut Pembayaran')).click();");

            $browser->pause(1000)
                 ->assertPathBeginsWith('/donations/payment/')
                 ->assertSee('Invoice Pembayaran')
                 ->assertSee('pending')
                 ->pause(3000)
                 ->logout();
        });
    }

    // TC-PB16-006: Berhasil upload bukti pembayaran valid
    
    public function testBerhasilUploadBuktiPembayaranValid()
    {
        $kegiatan = Kegiatan::where('status', 'Berlangsung')->first();
        if (!$kegiatan) {
            $this->markTestSkipped('Butuh minimal 1 kegiatan berlangsung di database');
        }

        $this->browse(function (Browser $browser) use ($kegiatan) {
            $this->login($browser)
                 ->visit('/donations')
                 ->select('kegiatan_id', $kegiatan->id)
                 ->type('nama_donatur', 'Test Upload Valid')
                 ->type('nomor_hp', '08123456789')
                 ->type('jumlah', '150000')
                 ->script("Array.from(document.querySelectorAll('button')).find(el => el.textContent.includes('Lanjutkan Konfirmasi')).click();");

            $browser->pause(1000)
                 ->assertPathIs('/donations/confirmation')
                 ->script("Array.from(document.querySelectorAll('button')).find(el => el.textContent.includes('Lanjut Pembayaran')).click();");

            $browser->pause(1000)
                 ->assertPathBeginsWith('/donations/payment/')
                 ->attach('bukti_pembayaran', $this->dummyPng)
                 ->script("Array.from(document.querySelectorAll('button')).find(el => el.textContent.includes('Kirim Bukti Pembayaran')).click();");
            
            $browser->pause(2000)
                 ->assertSee('Bukti pembayaran berhasil dikirim.')
                 ->pause(3000)
                 ->logout();
        });
    }

    // TC-PB16-007: Gagal upload bukti pembayaran tidak valid

    public function testGagalUploadBuktiPembayaranTidakValid()
    {
        $kegiatan = Kegiatan::where('status', 'Berlangsung')->first();
        if (!$kegiatan) {
            $this->markTestSkipped('Butuh minimal 1 kegiatan berlangsung di database');
        }

        $this->browse(function (Browser $browser) use ($kegiatan) {
            $this->login($browser)
                 ->visit('/donations')
                 ->select('kegiatan_id', $kegiatan->id)
                 ->type('nama_donatur', 'Test Upload Invalid')
                 ->type('nomor_hp', '08123456789')
                 ->type('jumlah', '150000')
                 ->script("Array.from(document.querySelectorAll('button')).find(el => el.textContent.includes('Lanjutkan Konfirmasi')).click();");

           $browser->pause(1000)
                 ->assertPathIs('/donations/confirmation')
                 ->script("Array.from(document.querySelectorAll('button')).find(el => el.textContent.includes('Lanjut Pembayaran')).click();");

           $browser->pause(1000)
                 ->assertPathBeginsWith('/donations/payment/')
                 ->attach('bukti_pembayaran', $this->dummyPdf)
                 ->assertDialogOpened('File ditolak! Hanya JPG atau PNG yang diperbolehkan.')
                 ->acceptDialog()
                 ->pause(3000)
                 ->logout();
        });
    }

}