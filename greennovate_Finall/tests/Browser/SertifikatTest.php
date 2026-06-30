<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Pembelian;

class SertifikatTest extends DuskTestCase
{
    private $userEmail = 'user@greennovate.test';

    private function loginAs(Browser $browser, $email)
    {
        $user = User::where('email', $email)->first();
        return $browser->loginAs($user);
    }

    /**
     * TC-01: Generate Sertifikat Pembelian — Happy Path
     */
    public function testSertifikatTersediaUntukPembelianSukses()
    {
        $user = User::where('email', $this->userEmail)->first();
        
        // Buat data dummy pembelian Sukses
        $pembelian = Pembelian::create([
            'user_id' => $user->id,
            'nama_item' => 'Penanaman Pohon Mahoni - Lahan: Kampus A',
            'jumlah_item' => 5,
            'total_harga' => 500000,
            'status' => 'Sukses',
            'kode_transaksi' => 'TRX-' . time() . rand(100,999),
        ]);

        $this->browse(function (Browser $browser) use ($pembelian) {
            $this->loginAs($browser, $this->userEmail)
                 ->visit('/riwayat')
                 ->waitFor('#riwayat-item-pembelian-' . $pembelian->id, 10)
                 ->click('#riwayat-item-pembelian-' . $pembelian->id)
                 ->waitForText('Unduh Sertifikat', 10)
                 ->assertSourceHas('href="/pembelian/' . $pembelian->id . '/sertifikat"')
                 ->pause(15000)
                 ->logout();
        });

        // Hapus data dummy
        $pembelian->delete();
    }

    /**
     * TC-02: Sertifikat Tidak Tersedia — Pembelian Belum Sukses
     */
    public function testSertifikatTidakTersediaJikaBelumSukses()
    {
        $user = User::where('email', $this->userEmail)->first();
        
        // Buat data dummy pembelian Pending
        $pembelian = Pembelian::create([
            'user_id' => $user->id,
            'nama_item' => 'Penanaman Pohon Jati',
            'jumlah_item' => 2,
            'total_harga' => 200000,
            'status' => 'Pending',
            'kode_transaksi' => 'TRX-' . time() . rand(100,999),
        ]);

        $this->browse(function (Browser $browser) use ($pembelian) {
            $this->loginAs($browser, $this->userEmail)
                 ->visit('/riwayat')
                 ->waitFor('#riwayat-item-pembelian-' . $pembelian->id, 10)
                 ->click('#riwayat-item-pembelian-' . $pembelian->id)
                 ->waitForText('Unduh Sertifikat', 10)
                 ->assertSourceHas('<button disabled')
                 ->pause(15000)
                 ->logout();
        });

        // Hapus data dummy
        $pembelian->delete();
    }

    /**
     * TC-05: Akses Tanpa Login
     */
    public function testAksesTanpaLoginRedirectKeLogin()
    {
        $this->browse(function (Browser $browser) {
            $browser->logout()
                    ->visit('/pembelian/999/sertifikat')
                    ->assertPathIs('/login')
                    ->pause(15000);
        });
    }
}
