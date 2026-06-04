<?php

namespace Tests\Browser;

use App\Models\KategoriPohon;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class JenisPohonTest extends DuskTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Siapkan data yang dibutuhkan sebelum test dijalankan.
     */
    private function prepareData()
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin_dusk@greennovate.com'],
            [
                'name' => 'Admin Dusk',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'admin',
                'is_active' => \Illuminate\Support\Facades\DB::raw('true'),
            ]
        );

        $kategori = KategoriPohon::firstOrCreate(
            ['nama' => 'Kayu Keras'],
            ['deskripsi' => 'Kategori kayu keras']
        );

        return [$admin, $kategori];
    }

    /**
     * Test 1: Tambah Jenis Pohon
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
                ->pause(10000) // Delay 10 detik lihat halaman awal
                
                ->click('#btn-tambah-jenis-pohon') // Pastikan id ini benar di view
                // Isi Form
                ->type('nama', $namaPohon)
                ->type('nama_latin', 'Samanea saman')
                ->select('kategori_pohon_id', $kategori->id)
                ->type('harga', '75000')
                ->select('status', 'active')
                
                ->pause(10000) // Delay 10 detik setelah isi form
                ->press('Simpan')

                // Assert sukses
                ->waitForText('berhasil')
                ->assertPathIs('/admin/jenis-pohon')
                ->assertSee($namaPohon)
                ->pause(10000); // Delay 10 detik lihat hasil tambah
                
            // Cleanup
            \App\Models\JenisPohon::where('nama', $namaPohon)->forceDelete();
        });
    }

    /**
     * Test 2: Edit Jenis Pohon
     */
    public function testEditJenisPohon(): void
    {
        $this->browse(function (Browser $browser) {
            [$admin, $kategori] = $this->prepareData();
            $unique = uniqid();
            $namaPohon = "Jati Edit {$unique}";

            $pohon = \App\Models\JenisPohon::firstOrCreate(
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
                ->pause(10000) // Delay 10 detik sebelum edit
                ->click("#btn-edit-{$pohon->id}")
                
                // Ubah harga
                ->type('harga', '150000')
                ->pause(10000) // Delay 10 detik setelah ubah nilai
                ->press('Simpan')

                // Assert sukses
                ->waitForText('berhasil')
                ->assertPathIs('/admin/jenis-pohon')
                ->pause(10000); // Delay 10 detik setelah sukses edit
                
            // Cleanup
            $pohon->forceDelete();
        });
    }

    /**
     * Test 3: Hapus Jenis Pohon
     */
    public function testHapusJenisPohon(): void
    {
        $this->browse(function (Browser $browser) {
            [$admin, $kategori] = $this->prepareData();
            $unique = uniqid();
            $namaPohon = "Sengon Hapus {$unique}";

            $pohon = \App\Models\JenisPohon::firstOrCreate(
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
                ->pause(10000) // Delay 10 detik sebelum hapus
                ->click("#btn-delete-{$pohon->id}")
                ->waitForDialog()
                ->pause(2000) // Jeda sebentar sebelum dialog di-accept
                ->acceptDialog()

                // Assert sukses
                ->waitForText('berhasil')
                ->assertPathIs('/admin/jenis-pohon')
                ->assertDontSee($namaPohon)
                ->pause(10000); // Delay 10 detik setelah dihapus
        });
    }
}
