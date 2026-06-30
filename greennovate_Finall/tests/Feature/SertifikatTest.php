<?php

namespace Tests\Feature;

use App\Models\Donasi;
use App\Models\Kegiatan;
use App\Models\LokasiLahan;
use App\Models\User;
use App\Models\UserO2Stat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Barryvdh\DomPDF\Facade\Pdf;

class SertifikatTest extends TestCase
{
    use RefreshDatabase;

    // Gunakan RefreshDatabase jika tidak mengganggu data lama
    // Tetapi karena constraint user = JANGAN HAPUS DATABASE LAMA, kita gunakan setup manual atau transaksi


    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\App\Http\Middleware\CheckActive::class);
        
        // Buat user dummy yang selalu dihapus di teardown
        $this->userA = User::factory()->create(['role' => 'user', 'is_active' => true]);
        $this->userB = User::factory()->create(['role' => 'user', 'is_active' => true]);
        
        $this->lokasi = LokasiLahan::create([
            'nama' => 'Hutan Lindung Test',
            'alamat' => 'Jl. Hutan Test No. 1',
            'kapasitas_pohon' => 1000,
            'status' => 'Tersedia',
            'latitude' => 0,
            'longitude' => 0,
            'luas_m2' => 1000
        ]);

        $this->petugas = User::factory()->create(['role' => 'petugas', 'is_active' => true]);

        $this->kegiatan = Kegiatan::create([
            'nama' => 'Penanaman Bersama Test',
            'lokasi_lahan_id' => $this->lokasi->id,
            'petugas_id' => $this->petugas->id,
            'tanggal' => now()->addDays(5),
            'target_dana' => 100000,
            'target_pohon' => 100,
            'estimasi_pohon' => 10,
            'tipe_kegiatan' => 'tanam_pohon',
            'status' => 'Persiapan',
        ]);
    }

    protected function tearDown(): void
    {
        Donasi::whereIn('user_id', [$this->userA->id, $this->userB->id])->delete();
        UserO2Stat::whereIn('user_id', [$this->userA->id, $this->userB->id])->delete();
        $this->kegiatan->delete();
        $this->lokasi->delete();
        $this->userA->delete();
        $this->userB->delete();
        
        parent::tearDown();
    }

    /** @test */
    public function generate_sertifikat_happy_path()
    {
        // Setup donasi sukses & berdokumentasi
        $donasi = Donasi::create([
            'user_id' => $this->userA->id,
            'kegiatan_id' => $this->kegiatan->id,
            'nama_donasi' => 'Donasi Hutan',
            'nomor_hp' => '0812345',
            'jumlah' => 100000,
            'status' => 'Sukses',
            'kode_transaksi' => 'TRX-12345',
            'bukti_dokumentasi' => 'dummy_dok.jpg'
        ]);
        
        // Mock storage untuk validasi hasDokumentasi()
        Storage::fake('public');
        Storage::disk('public')->put('dummy_dok.jpg', 'dummy');

        // Login dan akses
        $response = $this->actingAs($this->userA)->get("/donasi/{$donasi->id}/sertifikat");
        
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    /** @test */
    public function sertifikat_tidak_tersedia_jika_donasi_pending()
    {
        $donasi = Donasi::create([
            'user_id' => $this->userA->id,
            'kegiatan_id' => $this->kegiatan->id,
            'nama_donasi' => 'Donasi untuk Hutan',
            'nomor_hp' => '081234567890',
            'jumlah' => 100000,
            'status' => 'Pending',
            'kode_transaksi' => 'TRX-' . time(),
            'bukti_dokumentasi' => 'dummy_dok.jpg'
        ]);

        Storage::fake('public');
        Storage::disk('public')->put('dummy_dok.jpg', 'dummy');

        $response = $this->actingAs($this->userA)->get("/donasi/{$donasi->id}/sertifikat");
        
        // Redirect back dengan session error
        $response->assertStatus(302);
        $response->assertSessionHas('error', 'Sertifikat tersedia setelah donasi berhasil diverifikasi');
    }

    /** @test */
    public function akses_sertifikat_milik_user_lain_mengembalikan_403()
    {
        $donasi = Donasi::create([
            'user_id' => $this->userA->id, // Milik User A
            'kegiatan_id' => $this->kegiatan->id,
            'nama_donasi' => 'Donasi untuk Hutan',
            'nomor_hp' => '081234567890',
            'jumlah' => 100000,
            'status' => 'Sukses',
            'kode_transaksi' => 'TRX-' . time(),
            'bukti_dokumentasi' => 'dummy_dok.jpg'
        ]);

        Storage::fake('public');
        Storage::disk('public')->put('dummy_dok.jpg', 'dummy');

        // Login sebagai User B
        $response = $this->actingAs($this->userB)->get("/donasi/{$donasi->id}/sertifikat");
        
        $response->assertStatus(403);
    }

    /** @test */
    public function akses_tanpa_login_mengembalikan_redirect_ke_login()
    {
        $donasi = Donasi::create([
            'user_id' => $this->userA->id,
            'kegiatan_id' => $this->kegiatan->id,
            'nama_donasi' => 'Donasi Hutan',
            'nomor_hp' => '0812345',
            'jumlah' => 100000,
            'status' => 'Sukses',
            'kode_transaksi' => 'TRX-12346',
            'bukti_dokumentasi' => 'dummy_dok.jpg'
        ]);

        $response = $this->get("/donasi/{$donasi->id}/sertifikat");
        
        // Tidak authorized akan diredirect ke login (sesuai middleware auth default)
        // Di laravel 11, route redirect ke /login
        $response->assertRedirect('/login');
    }

    /** @test */
    public function sertifikat_gagal_jika_dokumentasi_kosong()
    {
        $donasi = Donasi::create([
            'user_id' => $this->userA->id,
            'kegiatan_id' => $this->kegiatan->id,
            'nama_donasi' => 'Donasi untuk Hutan',
            'nomor_hp' => '081234567890',
            'jumlah' => 100000,
            'status' => 'Sukses',
            'kode_transaksi' => 'TRX-' . time(),
            'bukti_dokumentasi' => null // Dokumentasi belum diupload
        ]);

        $response = $this->actingAs($this->userA)->get("/donasi/{$donasi->id}/sertifikat");
        
        $response->assertStatus(302);
        $response->assertSessionHas('error', 'Sertifikat akan tersedia setelah petugas mengupload dokumentasi penanaman');
    }
}
