<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\LokasiLahan;
use App\Models\JenisPohon;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\Hash;

class TreePurchaseTest extends DuskTestCase
{
    protected $user;
    protected $fieldOfficer;
    protected $lokasi;
    protected $jenisPohon;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::where('role', 'user')->first();

        if (!$this->user) {
            $this->user = User::create([
                'name' => 'Test User',
                'email' => 'testuser@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]);
        }

        $this->fieldOfficer = User::where('role', 'petugas')->first();

        if (!$this->fieldOfficer) {
            $this->fieldOfficer = User::create([
                'name' => 'Petugas Test',
                'email' => 'petugas@example.com',
                'password' => Hash::make('password'),
                'role' => 'petugas',
            ]);
        }

        $this->lokasi = LokasiLahan::first();
        $this->jenisPohon = JenisPohon::first();
    }

    /**
     * TC-01: Validasi Kalkulasi Biaya
     */
       public function test_validasi_kalkulasi_biaya(): void
{
    if (!$this->lokasi || !$this->jenisPohon) {
        $this->markTestSkipped(
            'Data LokasiLahan atau JenisPohon kosong. Pastikan seeder sudah dijalankan.'
        );
    }

    $this->browse(function (Browser $browser) {

        $browser->loginAs($this->user)
                ->visit('/dashboard')
                ->waitForLink('Mulai Kontribusi')
                ->clickLink('Mulai Kontribusi')
                ->pause(2000)
                ->assertPathIs('/pembelian')
                ->select('lokasi_lahan_id', (string) $this->lokasi->id)
                ->select('jenis_pohon_id', (string) $this->jenisPohon->id)
                ->pause(1000)
                ->assertSee('Total Pembayaran')
                ->assertSee(
                    number_format(
                        $this->jenisPohon->harga + 25000,
                        0,
                        ',',
                        '.'
                    )
                )
                ->pause(5000);
    });
}

   /**
 * TC-02: Verifikasi Pembayaran + Upload Bukti Transfer
 */
public function test_verifikasi_pembayaran_berhasil(): void
{
    if (!$this->lokasi || !$this->jenisPohon) {
        $this->markTestSkipped(
            'Data LokasiLahan atau JenisPohon kosong.'
        );
    }

    $this->browse(function (Browser $browser) {

        $browser->loginAs($this->user)
                ->visit('/dashboard')

                ->waitForLink('Mulai Kontribusi')
                ->clickLink('Mulai Kontribusi')
                ->pause(2000)

                ->assertPathIs('/pembelian')

                ->select('lokasi_lahan_id', (string) $this->lokasi->id)
                ->pause(500)

                ->select('jenis_pohon_id', (string) $this->jenisPohon->id)
                ->pause(1000)

                ->type(
                    'catatan',
                    'Testing upload bukti pembayaran menggunakan Laravel Dusk'
                )
                ->pause(1000);

        $browser->script(
            "window.scrollTo(0, document.body.scrollHeight);"
        );

        $browser->pause(1000)

                ->press('Buat Pesanan & Invoice')
                ->pause(3000)

                ->assertSee('Informasi Pembayaran')
                ->assertSee('Upload Bukti Transfer')

                ->attach(
                    'bukti_transfer',
                    base_path('tests/Browser/fixtures/dummy_foto.png')
                )
                ->pause(2000)

                ->press('Kirim Bukti Pembayaran')
                ->pause(5000);
    });
}

}