<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Kegiatan;
use App\Models\Donasi;
use App\Models\Achievement;
use App\Models\UserAchievement;
use App\Models\LokasiLahan;

class O2AchievementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed achievements
        \Database\Seeders\AchievementSeeder::class;
        $this->seed(\Database\Seeders\AchievementSeeder::class);
    }

    private function createKegiatan($overrides = [])
    {
        $lokasi = LokasiLahan::firstOrCreate(
            ['nama' => 'Test Lokasi'],
            ['alamat' => 'Test', 'luas' => 100, 'kapasitas_pohon' => 100, 'status' => 'Tersedia', 'deskripsi' => 'Test', 'foto' => 'test.jpg']
        );
        $petugas = User::firstOrCreate(
            ['email' => 'petugas@test.com'], 
            ['name' => 'Petugas', 'password' => bcrypt('password'), 'role' => 'petugas', 'phone' => '08111']
        );
        
        return Kegiatan::create(array_merge([
            'nama' => 'Test Kegiatan',
            'lokasi_lahan_id' => $lokasi->id,
            'petugas_id' => $petugas->id,
            'tanggal' => now(),
            'target_pohon' => 10,
            'status' => 'Berlangsung',
            'deskripsi' => 'Test',
            'tipe_kegiatan' => 'tanam_pohon',
            'estimasi_pohon' => 10,
            'target_dana' => 1000000,
        ], $overrides));
    }

    private function createDonasi($user_id, $kegiatan_id, $overrides = [])
    {
        return Donasi::create(array_merge([
            'user_id' => $user_id,
            'kegiatan_id' => $kegiatan_id,
            'nama_donasi' => 'Test',
            'nama_donatur' => 'Test',
            'nomor_hp' => '08123',
            'jumlah' => 100000,
            'metode_pembayaran' => 'QRIS',
            'status' => 'Pending',
            'kode_transaksi' => 'TRX-' . rand(1000, 9999),
        ], $overrides));
    }

    /** @test */
    public function unit_test_kalkulasi_proporsi_pohon_dan_o2()
    {
        $user = User::factory()->create(['role' => 'user']);
        $kegiatan = $this->createKegiatan(['tipe_kegiatan' => 'tanam_pohon', 'target_dana' => 1000000, 'estimasi_pohon' => 10]);
        $donasi = $this->createDonasi($user->id, $kegiatan->id, ['jumlah' => 100000, 'status' => 'Pending']);

        // Simulasikan observer berjalan dengan merubah status ke sukses
        $donasi->status = 'Sukses';
        $donasi->save();

        $this->assertDatabaseHas('user_o2_stats', [
            'user_id' => $user->id,
            'total_pohon' => 1.0, 
            'total_o2_kg_per_bulan' => 8.3,
        ]);
        
        $this->assertDatabaseHas('user_achievements', [
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function unit_test_kegiatan_lainnya_tidak_menambah_o2()
    {
        $user = User::factory()->create(['role' => 'user']);
        $kegiatan = $this->createKegiatan(['tipe_kegiatan' => 'lainnya', 'target_dana' => 1000000, 'estimasi_pohon' => 10]);
        $donasi = $this->createDonasi($user->id, $kegiatan->id, ['jumlah' => 100000, 'status' => 'Pending']);

        $donasi->status = 'Sukses';
        $donasi->save();

        $this->assertDatabaseMissing('user_o2_stats', [
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function unit_test_tidak_ada_duplikasi_badge()
    {
        $user = User::factory()->create(['role' => 'user']);
        $kegiatan = $this->createKegiatan(['tipe_kegiatan' => 'tanam_pohon', 'target_dana' => 1000000, 'estimasi_pohon' => 10]);

        $donasi1 = $this->createDonasi($user->id, $kegiatan->id, ['jumlah' => 100000, 'status' => 'Sukses']);
        $donasi1->status = 'Pending';
        $donasi1->saveQuietly();
        $donasi1->status = 'Sukses';
        $donasi1->save(); 

        $donasi2 = $this->createDonasi($user->id, $kegiatan->id, ['jumlah' => 100000, 'status' => 'Pending']);
        $donasi2->status = 'Sukses';
        $donasi2->save(); 
        
        $achievement = Achievement::where('threshold_o2', 8.3)->first();
        $count = UserAchievement::where('user_id', $user->id)
            ->where('achievement_id', $achievement->id)
            ->count();
            
        $this->assertEquals(1, $count);
    }
    
    /** @test */
    public function unit_test_desimal_pohon()
    {
        $user = User::factory()->create(['role' => 'user']);
        $kegiatan = $this->createKegiatan(['tipe_kegiatan' => 'tanam_pohon', 'target_dana' => 1000000, 'estimasi_pohon' => 10]);
        $donasi = $this->createDonasi($user->id, $kegiatan->id, ['jumlah' => 50000, 'status' => 'Pending']);

        $donasi->status = 'Sukses';
        $donasi->save();

        $this->assertDatabaseHas('user_o2_stats', [
            'user_id' => $user->id,
            'total_pohon' => 0.5, 
            'total_o2_kg_per_bulan' => 4.15, 
        ]);
        
        $this->assertDatabaseMissing('user_achievements', [
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function integration_test_o2_stats_endpoint()
    {
        $user = User::factory()->create(['role' => 'user', 'is_active' => true]);
        $this->actingAs($user);
        
        $kegiatan = $this->createKegiatan(['tipe_kegiatan' => 'tanam_pohon', 'target_dana' => 1000000, 'estimasi_pohon' => 10]);
        $donasi = $this->createDonasi($user->id, $kegiatan->id, ['jumlah' => 100000, 'status' => 'Pending']);

        $donasi->status = 'Sukses';
        $donasi->save();

        $response = $this->getJson('/o2/stats');

        $response->assertStatus(200)
                 ->assertJson([
                     'total_pohon' => 1.0,
                     'total_o2_kg_per_bulan' => 8.3,
                 ]);
    }

    /** @test */
    public function integration_test_achievement_progress_endpoint()
    {
        $user = User::factory()->create(['role' => 'user', 'is_active' => true]);
        $this->actingAs($user);
        
        $kegiatan = $this->createKegiatan(['tipe_kegiatan' => 'tanam_pohon', 'target_dana' => 1000000, 'estimasi_pohon' => 10]);
        $donasi = $this->createDonasi($user->id, $kegiatan->id, ['jumlah' => 100000, 'status' => 'Pending']);

        $donasi->status = 'Sukses';
        $donasi->save();

        $response = $this->getJson('/achievement/progress');

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'current_o2' => 8.3,
                 ]);
                 
        $this->assertArrayHasKey('next_badge', $response->json());
    }
}
