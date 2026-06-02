<?php

namespace Tests\Feature;

use App\Models\JenisPohon;
use App\Models\Kegiatan;
use App\Models\Pembelian;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PetugasRealisasiIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Pastikan role dan data dasar siap
        $kategori = \App\Models\KategoriPohon::firstOrCreate(
            ['nama' => 'Kategori Mangrove'],
            ['deskripsi' => 'Deskripsi Kategori']
        );
        JenisPohon::firstOrCreate(
            ['nama' => 'Pohon Mangrove'],
            ['nama_latin' => 'Rhizophora', 'harga' => 50000, 'stok' => 1000, 'kategori_pohon_id' => $kategori->id]
        );
    }

    private function createPetugas()
    {
        return User::factory()->create([
            'role' => 'petugas',
            'is_active' => 'true',
        ]);
    }

    private function createKegiatan($petugasId)
    {
        $lokasi = \App\Models\LokasiLahan::firstOrCreate(
            ['nama' => 'Lahan Integrasi'],
            ['alamat' => 'Alamat Int', 'deskripsi' => 'Deskripsi Int']
        );

        return Kegiatan::create([
            'nama' => 'Kegiatan Integrasi',
            'lokasi_lahan_id' => $lokasi->id,
            'petugas_id' => $petugasId,
            'status' => 'Berlangsung',
            'tanggal' => now()->addDays(2),
            'quota' => 50,
            'target_pohon' => 100,
            'realisasi_pohon' => 0,
        ]);
    }

    public function test_access_control_petugas_can_access()
    {
        $petugas = $this->createPetugas();
        
        $response = $this->actingAs($petugas)->get('/petugas/realisasi');
        
        $response->assertStatus(200);
        $response->assertViewIs('petugas.realisasi');
    }

    public function test_access_control_user_is_forbidden()
    {
        $user = User::factory()->create(['role' => 'user', 'is_active' => 'true']);
        
        $response = $this->actingAs($user)->get('/petugas/realisasi');
        
        // Middleware is.admin / is.petugas biasanya merespons 403 atau redirect, tergantung implementasi.
        // Berdasarkan skenario "User diarahkan ke halaman 403"
        $response->assertStatus(403);
    }

    public function test_access_control_guest_redirects_to_login()
    {
        $response = $this->get('/petugas/realisasi');
        
        $response->assertRedirect('/login');
    }

    public function test_post_realisasi_with_valid_data_and_success_transaction()
    {
        $petugas = $this->createPetugas();
        $kegiatan = $this->createKegiatan($petugas->id);
        $jenisPohon = JenisPohon::where('nama', 'Pohon Mangrove')->first();

        // Siapkan transaksi sukses
        Pembelian::create([
            'user_id' => User::factory()->create()->id,
            'kode_transaksi' => 'TX-12345',
            'nama_item' => 'Donasi Pohon Mangrove',
            'jumlah_item' => 10,
            'total_harga' => 500000,
            'status' => 'Sukses',
        ]);

        $payload = [
            'kegiatan_id' => $kegiatan->id,
            'jenis_pohon_id' => $jenisPohon->id,
            'jumlah_tertanam' => 20,
            'catatan' => 'Catatan lapangan hari ini',
        ];

        // Simulasi POST JSON
        $response = $this->actingAs($petugas)->postJson('/petugas/realisasi', $payload);

        $response->assertStatus(200);
        $response->assertJsonFragment(['message' => 'Realisasi penanaman berhasil disimpan']);

        // Assert data tersimpan di tabel realisasis
        $this->assertDatabaseHas('realisasis', [
            'kegiatan_id' => $kegiatan->id,
            'petugas_id' => $petugas->id,
            'jumlah' => 20,
            'catatan' => 'Catatan lapangan hari ini',
        ]);

        // Assert agregat progress bertambah
        $kegiatan->refresh();
        $this->assertEquals(20, $kegiatan->realisasi_pohon);
    }

    public function test_post_realisasi_fails_if_transaction_not_success()
    {
        $petugas = $this->createPetugas();
        $kegiatan = $this->createKegiatan($petugas->id);
        $jenisPohon = JenisPohon::where('nama', 'Pohon Mangrove')->first();

        // Transaksi berstatus Pending, bukan Sukses
        Pembelian::create([
            'user_id' => User::factory()->create()->id,
            'kode_transaksi' => 'TX-12345',
            'nama_item' => 'Donasi Pohon Mangrove',
            'jumlah_item' => 10,
            'total_harga' => 500000,
            'status' => 'Pending',
        ]);

        $payload = [
            'kegiatan_id' => $kegiatan->id,
            'jenis_pohon_id' => $jenisPohon->id,
            'jumlah_tertanam' => 20,
            'catatan' => 'Test gagal transaksi',
        ];

        $response = $this->actingAs($petugas)->postJson('/petugas/realisasi', $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['jumlah_tertanam']);
        $this->assertStringContainsString('transaksi belum diverifikasi', $response->json('errors.jumlah_tertanam.0'));
        
        // Assert tidak ada perubahan di DB
        $this->assertDatabaseMissing('realisasis', [
            'catatan' => 'Test gagal transaksi',
        ]);
        
        $kegiatan->refresh();
        $this->assertEquals(0, $kegiatan->realisasi_pohon);
    }
}
