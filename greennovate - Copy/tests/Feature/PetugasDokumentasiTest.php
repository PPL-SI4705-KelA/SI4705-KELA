<?php

namespace Tests\Feature;

use App\Models\Kegiatan;
use App\Models\LokasiLahan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PetugasDokumentasiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat user role petugas
        $this->petugas = User::factory()->create([
            'role' => 'petugas',
        ]);

        // Buat kegiatan dengan petugas terkait
        $this->lokasi = LokasiLahan::create([
            'nama' => 'Lokasi Test',
            'alamat' => 'Alamat Test',
            'latitude' => -6.200000,
            'longitude' => 106.816666,
        ]);

        $this->kegiatan = Kegiatan::create([
            'nama' => 'Kegiatan Test',
            'lokasi_lahan_id' => $this->lokasi->id,
            'petugas_id' => $this->petugas->id,
            'tanggal' => now()->addDays(5),
            'target_pohon' => 100,
            'status' => 'Berlangsung'
        ]);
    }

    public function test_petugas_can_upload_dokumentasi_gambar()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('dokumentasi.jpg');

        $response = $this->actingAs($this->petugas)
            ->postJson(route('petugas.api.store-dokumentasi', $this->kegiatan->id), [
                'foto' => $file,
            ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Dokumentasi berhasil diunggah!']);

        $this->assertDatabaseHas('dokumentasis', [
            'kegiatan_id' => $this->kegiatan->id,
            'petugas_id' => $this->petugas->id,
        ]);

        // Pastikan file tersimpan di disk public/dokumentasi
        $dokumentasi = \App\Models\Dokumentasi::first();
        Storage::disk('public')->assertExists($dokumentasi->file_path);
    }

    public function test_blocks_upload_for_non_image_files()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('document.pdf', 1000, 'application/pdf');

        $response = $this->actingAs($this->petugas)
            ->postJson(route('petugas.api.store-dokumentasi', $this->kegiatan->id), [
                'foto' => $file,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['foto']);

        $this->assertDatabaseEmpty('dokumentasis');
    }

    public function test_blocks_upload_if_kegiatan_not_assigned_to_petugas()
    {
        $petugasLain = User::factory()->create(['role' => 'petugas']);
        Storage::fake('public');

        $file = UploadedFile::fake()->image('dokumentasi.jpg');

        $response = $this->actingAs($petugasLain)
            ->postJson(route('petugas.api.store-dokumentasi', $this->kegiatan->id), [
                'foto' => $file,
            ]);

        $response->assertStatus(403)
            ->assertJsonFragment(['message' => 'Anda tidak memiliki akses ke kegiatan ini.']);
    }
}
