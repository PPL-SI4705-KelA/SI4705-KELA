<?php

namespace Tests\Feature;

use App\Models\Donasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BuktiPembayaranFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed initial data if necessary
    }

    public function test_halaman_instruksi_dapat_diakses_oleh_pemilik_transaksi()
    {
        $user = User::factory()->create(['is_active' => true]);
        $donasi = Donasi::create([
            'user_id' => $user->id,
            'nama_donasi' => 'Donasi Pohon',
            'jumlah' => 150000,
            'kode_transaksi' => 'TRX-1234',
            'status' => 'Pending'
        ]);

        $response = $this->actingAs($user)->get("/pembayaran/donasi/{$donasi->id}");

        $response->assertStatus(200);
        $response->assertSee('Donasi Pohon');
        $response->assertSee('150.000');
    }

    public function test_halaman_instruksi_tidak_dapat_diakses_oleh_user_lain()
    {
        $user1 = User::factory()->create(['is_active' => true]);
        $user2 = User::factory()->create(['is_active' => true]);
        $donasi = Donasi::create([
            'user_id' => $user1->id,
            'nama_donasi' => 'Donasi Pohon',
            'jumlah' => 150000,
            'kode_transaksi' => 'TRX-1234',
            'status' => 'Pending'
        ]);

        $response = $this->actingAs($user2)->get("/pembayaran/donasi/{$donasi->id}");

        $response->assertStatus(403);
    }

    public function test_upload_bukti_pembayaran_berhasil()
    {
        Storage::fake('public');

        $user = User::factory()->create(['is_active' => true]);
        $donasi = Donasi::create([
            'user_id' => $user->id,
            'nama_donasi' => 'Donasi Pohon',
            'jumlah' => 150000,
            'kode_transaksi' => 'TRX-1234',
            'status' => 'Pending'
        ]);

        $file = UploadedFile::fake()->image('bukti.jpg');

        $response = $this->actingAs($user)->post("/pembayaran/donasi/{$donasi->id}/upload", [
            'bukti_transfer' => $file
        ]);

        $response->assertSessionHas('success');
        
        $donasi->refresh();
        $this->assertEquals('Menunggu Konfirmasi', $donasi->status);
        $this->assertNotNull($donasi->bukti_dokumentasi);
        Storage::disk('public')->assertExists($donasi->bukti_dokumentasi);
    }

    public function test_validasi_upload_file_selain_gambar()
    {
        Storage::fake('public');

        $user = User::factory()->create(['is_active' => true]);
        $donasi = Donasi::create([
            'user_id' => $user->id,
            'nama_donasi' => 'Donasi Pohon',
            'jumlah' => 150000,
            'kode_transaksi' => 'TRX-1234',
            'status' => 'Pending'
        ]);

        $file = UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf');

        $response = $this->actingAs($user)->post("/pembayaran/donasi/{$donasi->id}/upload", [
            'bukti_transfer' => $file
        ]);

        $response->assertSessionHasErrors('bukti_transfer');
        
        $donasi->refresh();
        $this->assertEquals('Pending', $donasi->status);
        $this->assertNull($donasi->bukti_dokumentasi);
    }
}
