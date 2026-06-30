<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Kegiatan;
use App\Models\DokumentasiKegiatan;
use Carbon\Carbon;

class UploadDokumentasiTest extends DuskTestCase
{
    private $petugasEmail = 'petugas@greennovate.test';
    private $validPhotoPath;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        // Resolusi dinamis path foto agar portable di copy folder manapun
        $this->validPhotoPath = base_path('WIN_20260428_15_45_51_Pro.jpg');
        if (!file_exists($this->validPhotoPath)) {
            $this->validPhotoPath = __DIR__ . '/fixtures/dummy_foto.png';
        }
    }

    private function prepareDummyData()
    {
        $petugas = User::where('email', $this->petugasEmail)->first();
        if (!$petugas) return null;

        // Buat Kegiatan untuk petugas
        $kegiatan = Kegiatan::updateOrCreate(
            ['slug' => 'kegiatan-dummy-dokumentasi'],
            [
                'nama' => 'Kegiatan Dummy Dokumentasi',
                'deskripsi' => 'Deskripsi dummy',
                'lokasi_lahan_id' => \App\Models\LokasiLahan::first()->id ?? 1,
                'petugas_id' => $petugas->id,
                'target_pohon' => 10,
                'realisasi_pohon' => 0,
                'status' => 'Berlangsung',
                'tanggal' => Carbon::now()->addDays(5),
            ]
        );
        
        // // Clean up previous test docs
        // if (class_exists('\App\Models\DokumentasiKegiatan')) {
        //     \App\Models\DokumentasiKegiatan::where('petugas_id', $petugas->id)->delete();
        // } elseif (class_exists('\App\Models\Dokumentasi')) {
        //     \App\Models\Dokumentasi::where('petugas_id', $petugas->id)->delete();
        // }

        return ['kegiatan' => $kegiatan];
    }

    private function loginAsUser(Browser $browser, $email)
    {
        $user = User::where('email', $email)->first();
        return $browser->loginAs($user);
    }

    // TC-PB23-001: Membuka halaman dokumentasi
    
    public function testMembukaHalamanDokumentasi()
    {
        $data = $this->prepareDummyData();

        $this->browse(function (Browser $browser) use ($data) {
            $this->loginAsUser($browser, $this->petugasEmail)
                 ->visit('/petugas/dashboard')
                 ->waitForText($data['kegiatan']->nama, 10)
                 ->press('Dokumentasi')
                 ->waitForText('Upload Dokumentasi', 5)
                 ->assertSee('Upload Dokumentasi')
                 ->pause(3000)
                 ->logout();
        });
    }

    // TC-PB23-002: Upload dokumentasi berhasil

    public function testUploadFotoValid()
    {
        $data = $this->prepareDummyData();

        $this->browse(function (Browser $browser) use ($data) {
            $this->loginAsUser($browser, $this->petugasEmail)
                 ->visit('/petugas/dashboard')
                 ->waitForText($data['kegiatan']->nama, 10)
                 ->press('Dokumentasi')
                 ->waitForText('Upload Dokumentasi', 5)
                 ->attach('#dok_modal_foto', $this->validPhotoPath)
                 ->pause(1000)
                 ->click('#btnSubmitDokumentasi')
                 ->waitForText('Dokumentasi berhasil diunggah', 20)
                 ->assertSee('Dokumentasi berhasil diunggah')
                 ->pause(3000)
                 ->logout();
        });
    }

    // TC-PB23-003: Validasi upload tanpa memilih file

    public function testValidasiFileKosong()
    {
        $data = $this->prepareDummyData();

        $this->browse(function (Browser $browser) use ($data) {
            $this->loginAsUser($browser, $this->petugasEmail)
                 ->visit('/petugas/dashboard')
                 ->waitForText($data['kegiatan']->nama, 10)
                 ->press('Dokumentasi')
                 ->waitForText('Upload Dokumentasi', 5)
                 // Bypass HTML5 required to test backend or js validation
                 ->script("document.getElementById('dok_modal_foto').removeAttribute('required');");
            
            $browser->click('#btnSubmitDokumentasi')
                 ->waitForText('Silakan pilih foto terlebih dahulu.', 5)
                 ->assertSee('Silakan pilih foto terlebih dahulu.')
                 ->pause(3000)
                 ->logout();
        });
    }

    // TC-PB23-004: Validasi format file tidak valid

    public function testValidasiFormatFile()
    {
        $data = $this->prepareDummyData();

        $this->browse(function (Browser $browser) use ($data) {
            $this->loginAsUser($browser, $this->petugasEmail)
                 ->visit('/petugas/dashboard')
                 ->waitForText($data['kegiatan']->nama, 10)
                 ->press('Dokumentasi')
                 ->waitForText('Upload Dokumentasi', 5)
                 ->attach('#dok_modal_foto', __DIR__ . '/fixtures/dummy_doc.pdf')
                 ->pause(1000)
                 ->click('#btnSubmitDokumentasi')
                 ->waitForText('File harus berupa gambar.', 20)
                 ->assertSee('File harus berupa gambar.')
                 ->pause(3000)
                 ->logout();
        });
    }

}
