<?php

namespace Tests\Browser;

use App\Models\JenisPohon;
use App\Models\KategoriPohon;
use App\Models\Kegiatan;
use App\Models\LokasiLahan;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PetugasDashboardTest extends DuskTestCase
{

    private User $petugas;
    private LokasiLahan $lokasi;
    private KategoriPohon $kategori;
    private JenisPohon $jenisPohon;
    private Kegiatan $kegiatan;

    protected function setUp(): void
    {
        parent::setUp();

        $unique = uniqid();

        $this->petugas = User::factory()->create([
            'role' => 'petugas', 
            'is_active' => \Illuminate\Support\Facades\DB::raw('true'), 
            'name' => 'Budi Petugas Dusk',
            'email' => "budi.dusk.{$unique}@example.com"
        ]);
        
        $this->lokasi = LokasiLahan::firstOrCreate(
            ['nama' => 'Lahan Dusk'],
            ['alamat' => 'Alamat Dusk', 'deskripsi' => 'Lahan A']
        );
        
        $this->kategori = KategoriPohon::firstOrCreate(
            ['nama' => 'Pohon Kayu'],
            ['deskripsi' => 'Kayu keras']
        );
        
        $this->jenisPohon = JenisPohon::firstOrCreate(
            ['nama' => "Mahoni Dusk {$unique}"],
            [
                'kategori_pohon_id' => $this->kategori->id,
                'nama_latin' => 'Swietenia macrophylla',
                'harga' => 20000,
                'status' => 'active'
            ]
        );

        $this->kegiatan = Kegiatan::create([
            'nama' => "Aksi Tanam Dusk {$unique}",
            'petugas_id' => $this->petugas->id,
            'lokasi_lahan_id' => $this->lokasi->id,
            'status' => 'Berlangsung',
            'tanggal' => now(),
            'target_pohon' => 100,
            'realisasi_pohon' => 0,
        ]);
    }

    /**
     * @test
     */
    public function testDashboardLoadAndQuickAction()
    {
        $this->browse(function (Browser $browser) {
            // Login as petugas and visit dashboard
            $browser->loginAs($this->petugas)
                    ->visit('/petugas/dashboard')
                    ->assertSee('Selamat')
                    ->assertSee($this->kegiatan->nama);

            // Pause for 10 seconds to visually inspect dashboard
            $browser->pause(10000);
            
            // Navigate to "Semua Kegiatan"
            $browser->visit('/petugas/semua-kegiatan')
                    ->assertPathIs('/petugas/semua-kegiatan');

            // Pause for 10 seconds to visually inspect page
            $browser->pause(10000);
            
            // Back to dashboard
            $browser->visit('/petugas/dashboard');
            
            // Final Pause
            $browser->pause(10000);
        });
    }

    protected function tearDown(): void
    {
        if (isset($this->kegiatan)) {
            $this->kegiatan->forceDelete();
        }
        if (isset($this->jenisPohon)) {
            $this->jenisPohon->forceDelete();
        }
        if (isset($this->petugas)) {
            $this->petugas->forceDelete();
        }

        parent::tearDown();
    }
}
