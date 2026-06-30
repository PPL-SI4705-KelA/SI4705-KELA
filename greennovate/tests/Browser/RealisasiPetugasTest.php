<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Kegiatan;
use App\Models\JenisPohon;
use App\Models\Pembelian;
use App\Models\Realisasi;

class RealisasiPetugasTest extends DuskTestCase
{
    private $userEmail = 'user@greennovate.test';
    private $petugasEmail = 'petugas@greennovate.test';
    private $adminEmail = 'pardede281204@gmail.com';

    protected function setUp(): void
    {
        parent::setUp();
        
        // Clean up previous test data if any
        Realisasi::truncate();
    }

    private function prepareDummyData()
    {
        $lokasiLahan = \App\Models\LokasiLahan::firstOrCreate(
            ['nama' => 'Lokasi Dummy'],
            [
                'alamat' => 'Dummy Address',
                'deskripsi' => 'Dummy Description'
            ]
        );

        // Buat Kegiatan Dummy
        $kegiatan = Kegiatan::updateOrCreate(
            ['slug' => 'kegiatan-dummy-realisasi'],
            [
                'nama' => 'Kegiatan Dummy Realisasi',
                'deskripsi' => 'Dummy',
                'lokasi' => 'Dummy',
                'lokasi_lahan_id' => $lokasiLahan->id,
                'petugas_id' => User::where('email', $this->petugasEmail)->first()->id ?? 1,
                'tanggal' => now(),
                'target_donasi' => 100000,
                'target_pohon' => 10,
                'status' => 'Berlangsung',
                'jenis_pohon_id' => null, // Optional
            ]
        );

        // Buat Jenis Pohon A (Memiliki transaksi sukses)
        $pohonA = JenisPohon::updateOrCreate(
            ['nama' => 'Pohon Dummy Sukses'],
            [
                'nama_latin' => 'Dummy sukses latin',
                'deskripsi' => 'Dummy',
                'harga' => 50000,
                'kategori_pohon_id' => \App\Models\KategoriPohon::first()->id ?? 1,
                'is_active' => true,
            ]
        );

        // Buat Transaksi Sukses untuk Pohon A
        Pembelian::updateOrCreate(
            ['kode_transaksi' => 'TX-DUMMY-REALISASI-SUKSES'],
            [
                'user_id' => User::where('email', $this->userEmail)->first()->id ?? 1,
                'nama_item' => 'Pembelian Pohon Dummy Sukses',
                'jumlah_pohon' => 5,
                'total_harga' => 250000,
                'status' => 'Sukses',
            ]
        );

        // Buat Jenis Pohon B (Tidak memiliki transaksi sukses)
        $pohonB = JenisPohon::updateOrCreate(
            ['nama' => 'Pohon Dummy Pending'],
            [
                'nama_latin' => 'Dummy pending latin',
                'deskripsi' => 'Dummy',
                'harga' => 50000,
                'kategori_pohon_id' => \App\Models\KategoriPohon::first()->id ?? 1,
                'is_active' => true,
            ]
        );

        return [
            'kegiatan' => $kegiatan,
            'pohonA' => $pohonA,
            'pohonB' => $pohonB
        ];
    }

    private function loginAsUser(Browser $browser, $email)
    {
        $user = User::where('email', $email)->first();
        return $browser->loginAs($user);
    }

    /**
     * TC-04: Petugas mengisi angka negatif
     */
    public function testPetugasInputAngkaNegatif()
    {
        $data = $this->prepareDummyData();

        $this->browse(function (Browser $browser) use ($data) {
            $this->loginAsUser($browser, $this->petugasEmail)
                 ->visit('/petugas/realisasi')
                 ->select('kegiatan_id', (string)$data['kegiatan']->id)
                 ->select('jenis_pohon_id', (string)$data['pohonA']->id)
                 // Eksekusi JS untuk bypass HTML5 min="0" attributes supaya mencapai backend
                 ->script([
                     "document.getElementById('jumlah_tertanam').value = '-5';",
                     "document.getElementById('jumlah_tertanam').removeAttribute('min');"
                 ]);

            $browser->press('Simpan Realisasi')
                 ->pause(1000)
                 ->assertSee('Jumlah tidak boleh bernilai negatif')
                 ->pause(15000)
                 ->logout();
        });
    }

    /**
     * TC-06: Transaksi belum sukses / tidak ada
     */
    public function testPetugasInputTransaksiBelumSukses()
    {
        $data = $this->prepareDummyData();

        $this->browse(function (Browser $browser) use ($data) {
            $this->loginAsUser($browser, $this->petugasEmail)
                 ->visit('/petugas/realisasi')
                 ->select('kegiatan_id', (string)$data['kegiatan']->id)
                 ->select('jenis_pohon_id', (string)$data['pohonB']->id) // Pohon B tidak punya transaksi Sukses
                 ->script("document.getElementById('jumlah_tertanam').value = '2';")
                 ;
            $browser->press('Simpan Realisasi')
                 ->pause(1000)
                 ->assertSee('Realisasi tidak dapat diinput, transaksi belum diverifikasi')
                 ->pause(15000)
                 ->logout();
        });
    }
}
