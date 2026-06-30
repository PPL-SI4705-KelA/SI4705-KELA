<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Donasi;

class RiwayatValidasiDataTest extends DuskTestCase
{
    private $userEmail = 'user@greennovate.test';

    private function loginAs(Browser $browser, $email)
    {
        $user = User::where('email', $email)->first();
        return $browser->loginAs($user);
    }

    /**
     * TC Validasi Data Riwayat Sesuai Database - dengan klik detail
     */
    public function testDataRiwayatSesuaiDenganDatabase()
    {
        $user = User::where('email', $this->userEmail)->first();

        // Buat data dummy donasi langsung ke database
        $donasi = Donasi::create([
            'user_id' => $user->id,
            'kegiatan_id' => 1,
            'nama_donasi' => 'Donasi Validasi Dusk Test',
            'jumlah' => 333000,
            'status' => 'sukses',
            'kode_transaksi' => 'TRX-VALIDASI-' . time() . rand(100, 999),
        ]);

        $this->browse(function (Browser $browser) use ($donasi) {
            $this->loginAs($browser, $this->userEmail)
            ->visit('/riwayat?tipe=donasi')
            ->waitFor('#riwayat-item-donasi-' . $donasi->id, 10)
            ->assertSourceHas($donasi->nama_donasi)
            ->click('#riwayat-item-donasi-' . $donasi->id)
            ->waitFor('#detailModal.show', 10)
            // tunggu sampai teks nama donasi BENAR-BENAR muncul, bukan pause waktu tetap
            ->waitForTextIn('#modalBody', $donasi->nama_donasi, 10)
            ->assertSeeIn('#modalBody', $donasi->nama_donasi)
            ->assertSeeIn('#modalBody', $donasi->kode_transaksi)
            ->assertSeeIn('#modalBody', number_format($donasi->jumlah, 0, ',', '.'))
            ->assertSeeIn('#modalBody', 'sukses')
            ->pause(15000)
            ->logout();
        });
        

    }
}