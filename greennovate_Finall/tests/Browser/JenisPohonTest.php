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
                ->type('#input-nama-pohon', $namaPohon)
                ->type('#input-nama-latin', 'Samanea saman')
                ->select('#select-kategori', $kategori->id)
                ->type('#input-harga', '75000')
                ->click('#btn-simpan')
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
                    'harga' => 50000
                ]
            );

            $browser->loginAs($admin)
                ->visit('/admin/jenis-pohon/create')
                ->type('#input-nama-pohon', $namaPohon)
                ->select('#select-kategori', $kategori->id)
                ->type('#input-harga', '75000')
                ->click('#btn-simpan')
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
                    'version' => 1
                ]
            );

            $browser->loginAs($admin)
                ->visit('/admin/jenis-pohon')
                ->click("#btn-edit-{$pohon->id}")
                ->clear('#input-harga')
                ->type('#input-harga', '150000')
                ->click('#btn-simpan-perubahan')
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
                ->type('#input-nama-pohon', 'Ma')
                ->select('#select-kategori', $kategori->id)
                ->type('#input-harga', '50000')
                ->click('#btn-simpan')
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
                ->type('#input-nama-pohon', '')
                ->select('#select-kategori', $kategori->id)
                ->type('#input-harga', '50000')
                ->click('#btn-simpan')
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
                ->type('#input-nama-pohon', 'Pohon Test')
                ->select('#select-kategori', $kategori->id);

            // Hilangkan atribut min agar browser mengizinkan form disubmit untuk memicu validasi server
            $browser->script("document.getElementById('input-harga').removeAttribute('min');");

            $browser->type('#input-harga', '999')
                ->click('#btn-simpan')
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
                ->type('#input-nama-pohon', 'Pohon Test Max')
                ->select('#select-kategori', $kategori->id);

            // Hilangkan atribut max agar browser mengizinkan form disubmit
            $browser->script("document.getElementById('input-harga').removeAttribute('max');");

            $browser->type('#input-harga', '10000001')
                ->click('#btn-simpan')
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
                ->type('#input-nama-pohon', 'Pohon Kategori')
                // Jangan pilih kategori
                ->type('#input-harga', '50000')
                ->click('#btn-simpan')
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
                ->type('#input-nama-pohon', 'Pohon Angka')
                ->select('#select-kategori', $kategori->id);
                
            // Jika dikosongkan, validasi akan kena di 'required'.
            // Chrome native number type akan mengosongkan text string non-angka.
            $browser->type('#input-harga', '') 
                ->click('#btn-simpan')
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

    /**
     * TC-PB14-012: Filter Jenis Pohon
     */
    public function testFilterJenisPohon(): void
    {
        $this->browse(function (Browser $browser) {
            [$admin, $kategori] = $this->prepareData();
            
            // Buat beberapa data unik
            $unique1 = uniqid();
            $namaPohon1 = "Pohon A {$unique1}";
            JenisPohon::firstOrCreate(
                ['nama' => $namaPohon1],
                [
                    'kategori_pohon_id' => $kategori->id,
                    'harga' => 50000
                ]
            );

            $unique2 = uniqid();
            $namaPohon2 = "Pohon B {$unique2}";
            JenisPohon::firstOrCreate(
                ['nama' => $namaPohon2],
                [
                    'kategori_pohon_id' => $kategori->id,
                    'harga' => 60000
                ]
            );

            $browser->loginAs($admin)
                ->visit('/admin/jenis-pohon')
                ->assertSee($namaPohon1)
                ->assertSee($namaPohon2)
                
                // Test Filter berdasarkan Nama
                ->type('#input-search-pohon', "Pohon A {$unique1}")
                ->click('#btn-filter')
                ->waitForText($namaPohon1, 10)
                ->assertSee($namaPohon1)
                ->assertDontSee($namaPohon2)
                
                // Test Reset
                ->click('#btn-reset-filter')
                ->waitForText($namaPohon1, 10)
                ->assertSee($namaPohon1)
                ->assertSee($namaPohon2);

            $browser->pause(15000); // Rest time 15 detik
        });
    }
}
