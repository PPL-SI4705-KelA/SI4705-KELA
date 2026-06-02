<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PembelianTest extends DuskTestCase
{
    /**
     * TC-01
     */
    public function test_user_bisa_membuka_halaman_pembelian()
    {
        $user = User::first();

        $this->browse(function (Browser $browser) use ($user) {

            $browser->loginAs($user)
                    ->visit('/pembelian')
                    ->assertPathIs('/pembelian')
                    ->assertSee('Form Kontribusi Penanaman Pohon');
        });
    }

    /**
     * TC-02
     */
    public function test_komponen_form_pembelian_tampil()
    {
        $user = User::first();

        $this->browse(function (Browser $browser) use ($user) {

            $browser->loginAs($user)
                    ->visit('/pembelian')

                    ->assertPresent('@lokasi')
                    ->assertPresent('@jenis-pohon')

                    ->assertPresent('textarea[name="catatan"]')

                    ->assertSee('Harga Bibit Pohon')
                    ->assertSee('Total Pembayaran')
                    ->assertSee('Buat Pesanan & Invoice');
        });
    }

    /**
     * TC-03
     */
    public function test_user_bisa_melakukan_checkout_pembelian()
    {
        $user = User::first();

        $this->browse(function (Browser $browser) use ($user) {

            $browser->loginAs($user)

                    ->visit('/pembelian')

                    ->select('@lokasi')
                    ->select('@jenis-pohon')

                    ->type(
                        'catatan',
                        'Testing checkout menggunakan Laravel Dusk'
                    )

                    ->press('@btn-checkout')

                    ->waitForText('Informasi Pembayaran')

                    ->assertSee('Transfer Pembayaran')
                    ->assertSee('Upload Bukti Transfer');
        });
    }


    /**
     * TC-04
     */
    public function test_file_pdf_ditolak()
    {
        $user = User::first();

        $pembelianId = DB::table('pembelians')
            ->latest('id')
            ->value('id');

        $this->browse(function (Browser $browser) use ($user, $pembelianId) {

            $browser->loginAs($user)

                    ->visit("/pembelian/invoice/$pembelianId")

                    ->attach(
                        '@bukti-transfer',
                        base_path('tests/Browser/files/contoh.pdf')
                    )

                    ->pause(1000)

                    ->assertVisible('#error-file');
        });
    }
}