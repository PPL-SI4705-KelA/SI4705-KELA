<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Kegiatan;
use App\Models\LokasiLahan;
use App\Models\PendaftaranKegiatan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PendaftaranFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_filter_daftar_peserta_only_returns_selected_kegiatan_id(): void
    {
        // Create location
        $lokasi = LokasiLahan::create([
            'nama' => 'Lahan Induk',
            'alamat' => 'Jl. Hijau',
            'latitude' => 0.0,
            'longitude' => 0.0,
        ]);

        // Create petugas
        $petugas = User::factory()->create([
            'role' => 'petugas',
        ]);

        // 1. Create two Kegiatan
        $kegiatan1 = Kegiatan::create([
            'nama' => 'Kegiatan 1',
            'lokasi_lahan_id' => $lokasi->id,
            'petugas_id' => $petugas->id,
            'tanggal' => now()->addDays(7),
            'target_pohon' => 100,
            'quota' => 50,
            'status' => 'Berlangsung',
        ]);

        $kegiatan2 = Kegiatan::create([
            'nama' => 'Kegiatan 2',
            'lokasi_lahan_id' => $lokasi->id,
            'petugas_id' => $petugas->id,
            'tanggal' => now()->addDays(14),
            'target_pohon' => 100,
            'quota' => 50,
            'status' => 'Berlangsung',
        ]);

        // 2. Create users
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // 3. Create pendaftarans
        $pendaftaran1 = PendaftaranKegiatan::create([
            'user_id' => $user1->id,
            'kegiatan_id' => $kegiatan1->id,
            'nama_lengkap' => 'User 1',
            'no_hp' => '081234567890',
            'alamat' => 'Alamat 1',
            'status' => 'Terdaftar',
        ]);

        $pendaftaran2 = PendaftaranKegiatan::create([
            'user_id' => $user2->id,
            'kegiatan_id' => $kegiatan2->id,
            'nama_lengkap' => 'User 2',
            'no_hp' => '081234567891',
            'alamat' => 'Alamat 2',
            'status' => 'Terdaftar',
        ]);

        // 4. Query participants for kegiatan1
        $results = PendaftaranKegiatan::where('kegiatan_id', $kegiatan1->id)->get();

        // Assert only pendaftaran1 is returned
        $this->assertCount(1, $results);
        $this->assertEquals($pendaftaran1->id, $results->first()->id);
        $this->assertEquals($kegiatan1->id, $results->first()->kegiatan_id);
    }
}
