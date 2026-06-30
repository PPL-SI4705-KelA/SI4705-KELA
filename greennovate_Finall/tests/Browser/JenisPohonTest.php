<?php

namespace Tests\Browser;

use App\Models\KategoriPohon;
use App\Models\JenisPohon;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class JenisPohonTest extends DuskTestCase
{
    /**
     * Helper untuk menyiapkan data admin dan kategori.
     */
    private function prepareData()
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin_dusk@greennovate.com'],
            [
                'name' => 'Admin Dusk',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => DB::raw('true'),
            ]
        );

        $kategori = KategoriPohon::firstOrCreate(
            ['nama' => 'Kayu Keras'],
            ['deskripsi' => 'Kategori kayu keras']
        );

        return [$admin, $kategori];
    }

    /**
     * TC-PB14-001: Create Jenis Pohon - Happy Path
     */
    public function testTambahJenisPohon(): void
    {
        $this->browse(function (Browser $browser) {
            [$admin, $kategori] = $this->prepareData();
            
            $unique = uniqid();
            $namaPohon = "Trembesi Dusk {$unique}";

            $browser->loginAs($admin)
                ->visit('/admin/jenis-pohon')
                ->assertSee('Manajemen Jenis Pohon')
                ->click('#btn-tambah-jenis-pohon')
                ->type('nama', $namaPohon)
                ->type('nama_latin', 'Samanea saman')
                ->select('kategori_pohon_id', $kategori->id)
                ->type('harga', '75000')
                ->select('status', 'active')
                ->press('Simpan')
                ->waitForText('berhasil', 10)
                ->assertPathIs('/admin/jenis-pohon')
                ->assertSee($namaPohon);
                
            $browser->pause(15000); // Rest time 15 detik
        });
    }

    /**
     * TC-PB14-002: Create Jenis Pohon - Nama Duplikat
     */
    public function testTambahJenisPohonNamaDuplikat(): void
    {
        $this->browse(function (Browser $browser) {
            [$admin, $kategori] = $this->prepareData();
            $unique = uniqid();
            $namaPohon = "Mahoni {$unique}";

            JenisPohon::firstOrCreate(
                ['nama' => $namaPohon],
                [
                    'kategori_pohon_id' => $kategori->id,
                    'harga' => 50000,
                    'status' => 'active'
                ]
            );

            $browser->loginAs($admin)
                ->visit('/admin/jenis-pohon/create')
                ->type('nama', $namaPohon)
                ->select('kategori_pohon_id', $kategori->id)
                ->type('harga', '75000')
                ->select('status', 'active')
                ->press('Simpan')
                ->waitForText('sudah', 10) // "sudah terdaftar"
                ->assertPathIs('/admin/jenis-pohon/create');

            $browser->pause(15000); // Rest time 15 detik
        });
    }

    /**
     * TC-PB14-003: Update Jenis Pohon - Ubah Harga
     */
    public function testEditJenisPohon(): void
    {
        $this->browse(function (Browser $browser) {
            [$admin, $kategori] = $this->prepareData();
            $unique = uniqid();
            $namaPohon = "Jati Lama {$unique}";

            $pohon = JenisPohon::firstOrCreate(
                ['nama' => $namaPohon],
                [
                    'kategori_pohon_id' => $kategori->id,
                    'harga' => 100000,
                    'status' => 'active',
                    'version' => 1
                ]
            );

            $browser->loginAs($admin)
                ->visit('/admin/jenis-pohon')
                ->click("#btn-edit-{$pohon->id}")
                ->clear('harga')
                ->type('harga', '150000')
                ->press('Simpan')
                ->waitForText('berhasil', 10)
                ->assertPathIs('/admin/jenis-pohon');
                
            $browser->pause(15000); // Rest time 15 detik
        });
    }

    /**
     * TC-PB14-004: Delete Jenis Pohon - Soft Delete
     */
    public function testHapusJenisPohon(): void
    {
        $this->browse(function (Browser $browser) {
            [$admin, $kategori] = $this->prepareData();
            $unique = uniqid();
            $namaPohon = "Sengon Hapus {$unique}";

            $pohon = JenisPohon::firstOrCreate(
                ['nama' => $namaPohon],
                [
                    'kategori_pohon_id' => $kategori->id,
                    'harga' => 50000,
                    'status' => 'active',
                    'version' => 1
                ]
            );

            $browser->loginAs($admin)
                ->visit('/admin/jenis-pohon')
                ->click("#btn-delete-{$pohon->id}")
                ->waitForDialog()
                ->pause(1000)
                ->acceptDialog()
                ->waitForText('berhasil', 10)
                ->assertPathIs('/admin/jenis-pohon')
                ->assertDontSee($namaPohon);
                
            $browser->pause(15000); // Rest time 15 detik
        });
    }

    /**
     * TC-PB14-005: Validasi - Nama Terlalu Pendek
     */
    public function testValidasiNamaTerlaluPendek(): void
    {
        $this->browse(function (Browser $browser) {
            [$admin, $kategori] = $this->prepareData();

            $browser->loginAs($admin)
                ->visit('/admin/jenis-pohon/create')
                ->type('nama', 'Ma')
                ->select('kategori_pohon_id', $kategori->id)
                ->type('harga', '50000')
                ->press('Simpan')
                ->waitForText('minimal', 10)
                ->assertPathIs('/admin/jenis-pohon/create');
                
            $browser->pause(15000); // Rest time 15 detik
        });
    }

    /**
     * TC-PB14-006: Validasi - Nama Wajib Diisi
     */
    public function testValidasiNamaWajibDiisi(): void
    {
        $this->browse(function (Browser $browser) {
            [$admin, $kategori] = $this->prepareData();

            $browser->loginAs($admin)
                ->visit('/admin/jenis-pohon/create')
                ->type('nama', '')
                ->select('kategori_pohon_id', $kategori->id)
                ->type('harga', '50000')
                ->press('Simpan')
                ->waitForText('wajib diisi', 10)
                ->assertPathIs('/admin/jenis-pohon/create');
                
            $browser->pause(15000); // Rest time 15 detik
        });
    }

    /**
     * TC-PB14-007: Validasi - Harga di Bawah Minimum
     */
    public function testValidasiHargaDiBawahMinimum(): void
    {
        $this->browse(function (Browser $browser) {
            [$admin, $kategori] = $this->prepareData();

            $browser->loginAs($admin)
                ->visit('/admin/jenis-pohon/create')
                ->type('nama', 'Pohon Test')
                ->select('kategori_pohon_id', $kategori->id);

            // Hilangkan atribut min agar browser mengizinkan form disubmit untuk memicu validasi server
            $browser->script("document.getElementById('input-harga').removeAttribute('min');");

            $browser->type('harga', '999')
                ->press('Simpan')
                ->waitForText('minimal', 10)
                ->assertPathIs('/admin/jenis-pohon/create');
                
            $browser->pause(15000); // Rest time 15 detik
        });
    }

    /**
     * TC-PB14-008: Validasi - Harga di Atas Maksimum
     */
    public function testValidasiHargaDiAtasMaksimum(): void
    {
        $this->browse(function (Browser $browser) {
            [$admin, $kategori] = $this->prepareData();

            $browser->loginAs($admin)
                ->visit('/admin/jenis-pohon/create')
                ->type('nama', 'Pohon Test Max')
                ->select('kategori_pohon_id', $kategori->id);

            // Hilangkan atribut max agar browser mengizinkan form disubmit
            $browser->script("document.getElementById('input-harga').removeAttribute('max');");

            $browser->type('harga', '10000001')
                ->press('Simpan')
                ->waitForText('maksimal', 10)
                ->assertPathIs('/admin/jenis-pohon/create');
                
            $browser->pause(15000); // Rest time 15 detik
        });
    }

    /**
     * TC-PB14-009: Validasi - Kategori Wajib Dipilih
     */
    public function testValidasiKategoriWajibDipilih(): void
    {
        $this->browse(function (Browser $browser) {
            [$admin, $kategori] = $this->prepareData();

            $browser->loginAs($admin)
                ->visit('/admin/jenis-pohon/create')
                ->type('nama', 'Pohon Kategori')
                // Jangan pilih kategori
                ->type('harga', '50000')
                ->press('Simpan')
                ->waitForText('wajib', 10) // "Kategori wajib dipilih"
                ->assertPathIs('/admin/jenis-pohon/create');
                
            $browser->pause(15000); // Rest time 15 detik
        });
    }

    /**
     * TC-PB14-010: Validasi - Harga Harus Berupa Angka
     */
    public function testValidasiHargaHarusAngka(): void
    {
        $this->browse(function (Browser $browser) {
            [$admin, $kategori] = $this->prepareData();

            $browser->loginAs($admin)
                ->visit('/admin/jenis-pohon/create')
                ->type('nama', 'Pohon Angka')
                ->select('kategori_pohon_id', $kategori->id);
                
            // Jika dikosongkan, validasi akan kena di 'required'.
            // Chrome native number type akan mengosongkan text string non-angka.
            $browser->type('harga', '') 
                ->press('Simpan')
                ->waitForText('wajib', 10)
                ->assertPathIs('/admin/jenis-pohon/create');

            $browser->pause(15000); // Rest time 15 detik
        });
    }

    /**
     * TC-PB14-011: Unauthorized Access - Bukan Admin
     */
    public function testUnauthorizedAccessBukanAdmin(): void
    {
        $this->browse(function (Browser $browser) {
            $petugas = User::firstOrCreate(
                ['email' => 'petugas_dusk_new@greennovate.com'],
                [
                    'name' => 'Petugas Dusk',
                    'password' => Hash::make('password'),
                    'role' => 'petugas',
                    'is_active' => DB::raw('true'),
                ]
            );

            $browser->loginAs($petugas)
                ->visit('/admin/jenis-pohon')
                ->assertSee('403'); // Middleware IsAdmin menggunakan abort(403)
                
            $browser->pause(15000); // Rest time 15 detik
        });
    }
}
