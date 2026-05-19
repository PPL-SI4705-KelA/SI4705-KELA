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

        // Bersihkan data test dari run sebelumnya karena kita tidak pakai DatabaseMigrations
        \App\Models\JenisPohon::withTrashed()
            ->whereIn('nama', ['Trembesi Dusk', 'Jati Lama2', 'Sengon Hapus'])
            ->forceDelete();
    }

    /**
     * Siapkan data yang dibutuhkan sebelum test dijalankan.
     */
    private function prepareData()
    {
        // 1. Buat atau ambil user admin
        $admin = User::firstOrCreate(
            ['email' => 'admin_dusk@greennovate.com'],
            [
                'name' => 'Admin Dusk',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'admin',
                'is_active' => 'true',
            ]
        );

        // 2. Buat atau ambil kategori pohon
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

            $browser->loginAs($admin) // Login langsung tanpa lewat UI supaya cepat
                ->visit('/admin/jenis-pohon')
                ->assertSee('Manajemen Jenis Pohon')
                ->click('#btn-tambah-jenis-pohon')

                // Isi Form
                ->type('nama', 'Trembesi Dusk')
                ->type('nama_latin', 'Samanea saman')
                ->select('kategori_pohon_id', $kategori->id)
                ->type('harga', '75000')
                ->select('status', 'active')
                ->press('Simpan')

                // Assert sukses
                ->assertPathIs('/admin/jenis-pohon')
                ->assertSee('Jenis pohon berhasil ditambahkan.')
                ->assertSee('Trembesi Dusk');
        });
    }

    /**
     * Test 2: Edit Jenis Pohon
     */
    public function testEditJenisPohon(): void
    {
        $this->browse(function (Browser $browser) {
            [$admin, $kategori] = $this->prepareData();

            // Tambah data awal via Eloquent agar cepat
            $pohon = \App\Models\JenisPohon::firstOrCreate(
                ['nama' => 'Jati Lama2'],
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

                // Ubah harga
                ->type('harga', '150000')
                ->press('Simpan')

                // Assert sukses
                ->assertPathIs('/admin/jenis-pohon')
                ->assertSee('Jenis pohon berhasil diperbarui.');
        });
    }

    /**
     * Test 3: Hapus Jenis Pohon
     */
    public function testHapusJenisPohon(): void
    {
        $this->browse(function (Browser $browser) {
            [$admin, $kategori] = $this->prepareData();

            $pohon = \App\Models\JenisPohon::firstOrCreate(
                ['nama' => 'Sengon Hapus'],
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
                ->acceptDialog()

                // Assert sukses
                ->assertPathIs('/admin/jenis-pohon')
                ->assertSee('Jenis pohon berhasil dihapus.')
                ->assertDontSee('Sengon Hapus'); // Pastikan sudah tidak ada di tabel
        });
    }
}
