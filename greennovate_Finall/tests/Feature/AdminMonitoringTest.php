<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Kegiatan;
use App\Models\LokasiLahan;
use App\Models\PendaftaranKegiatan;
use App\Models\Donasi;
use App\Models\Pembelian;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminMonitoringTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
    {
        return User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'is_active' => true,
        ]);
    }

    private function createPetugas(): User
    {
        return User::factory()->create([
            'role' => User::ROLE_PETUGAS,
            'is_active' => true,
        ]);
    }

    private function createUser(): User
    {
        return User::factory()->create([
            'role' => User::ROLE_USER,
            'is_active' => true,
        ]);
    }

    /**
     * Test Case 7: Access control check for /admin/*
     */
    public function test_user_role_cannot_access_admin_monitoring(): void
    {
        $user = $this->createUser();

        $this->actingAs($user)->get('/admin/peserta')->assertStatus(403)->assertSee('Anda tidak memiliki akses ke halaman ini');
        $this->actingAs($user)->get('/admin/kegiatan/1/peserta')->assertStatus(403)->assertSee('Anda tidak memiliki akses ke halaman ini');
        $this->actingAs($user)->get('/admin/donasi')->assertStatus(403)->assertSee('Anda tidak memiliki akses ke halaman ini');
        $this->actingAs($user)->get('/admin/pembelian')->assertStatus(403)->assertSee('Anda tidak memiliki akses ke halaman ini');
        $this->actingAs($user)->get('/admin/pengguna')->assertStatus(403)->assertSee('Anda tidak memiliki akses ke halaman ini');
        $this->actingAs($user)->get('/admin/reports/donasi.csv')->assertStatus(403)->assertSee('Anda tidak memiliki akses ke halaman ini');
    }

    public function test_petugas_role_cannot_access_admin_monitoring(): void
    {
        $petugas = $this->createPetugas();

        $this->actingAs($petugas)->get('/admin/peserta')->assertStatus(403)->assertSee('Anda tidak memiliki akses ke halaman ini');
        $this->actingAs($petugas)->get('/admin/kegiatan/1/peserta')->assertStatus(403)->assertSee('Anda tidak memiliki akses ke halaman ini');
        $this->actingAs($petugas)->get('/admin/donasi')->assertStatus(403)->assertSee('Anda tidak memiliki akses ke halaman ini');
    }

    public function test_unauthenticated_is_redirected_to_login(): void
    {
        $this->get('/admin/peserta')->assertRedirect('/login');
        $this->get('/admin/donasi')->assertRedirect('/login');
    }

    /**
     * Test Case 1: GET /admin/kegiatan/{id}/peserta returns participants matching seed
     */
    public function test_admin_can_view_participants_of_specific_kegiatan(): void
    {
        $admin = $this->createAdmin();

        $lokasi = LokasiLahan::create([
            'nama' => 'Lahan Induk',
            'alamat' => 'Jl. Hijau',
            'latitude' => 0.0,
            'longitude' => 0.0,
        ]);

        $petugas = User::factory()->create([
            'role' => 'petugas',
        ]);

        $kegiatan = Kegiatan::create([
            'nama' => 'Kegiatan Bersih Pantai',
            'lokasi_lahan_id' => $lokasi->id,
            'petugas_id' => $petugas->id,
            'tanggal' => now()->addDays(7),
            'target_pohon' => 100,
            'quota' => 50,
            'status' => 'Berlangsung',
        ]);
        
        $user = User::factory()->create(['name' => 'Peserta Satu']);
        
        PendaftaranKegiatan::create([
            'user_id' => $user->id,
            'kegiatan_id' => $kegiatan->id,
            'nama_lengkap' => 'Peserta Satu',
            'no_hp' => '081234567890',
            'alamat' => 'Denpasar',
            'status' => 'Terdaftar',
        ]);

        $response = $this->actingAs($admin)->get("/admin/kegiatan/{$kegiatan->id}/peserta");

        $response->assertStatus(200);
        $response->assertSee('Peserta Satu');
        $response->assertSee('Kegiatan Bersih Pantai');
    }

    /**
     * Test Case 2: Empty state for participants
     */
    public function test_empty_participants_displays_empty_state_message(): void
    {
        $admin = $this->createAdmin();
        
        $lokasi = LokasiLahan::create([
            'nama' => 'Lahan Induk',
            'alamat' => 'Jl. Hijau',
            'latitude' => 0.0,
            'longitude' => 0.0,
        ]);

        $petugas = User::factory()->create([
            'role' => 'petugas',
        ]);

        $kegiatan = Kegiatan::create([
            'nama' => 'Kegiatan Kosong',
            'lokasi_lahan_id' => $lokasi->id,
            'petugas_id' => $petugas->id,
            'tanggal' => now()->addDays(7),
            'target_pohon' => 100,
            'quota' => 50,
            'status' => 'Berlangsung',
        ]);

        $response = $this->actingAs($admin)->get("/admin/kegiatan/{$kegiatan->id}/peserta");

        $response->assertStatus(200);
        $response->assertSee('Belum ada peserta terdaftar');
    }

    /**
     * Test Case 3, 8, 9, 10: Admin view donation list and status rendering
     */
    public function test_admin_can_view_donations_with_correct_status_labels(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create();

        Donasi::create([
            'user_id' => $user->id,
            'nama_donasi' => 'Donasi A',
            'jumlah' => 50000,
            'status' => 'Pending',
            'kode_transaksi' => 'TX-001',
        ]);

        Donasi::create([
            'user_id' => $user->id,
            'nama_donasi' => 'Donasi B',
            'jumlah' => 100000,
            'status' => 'Sukses',
            'kode_transaksi' => 'TX-002',
        ]);

        Donasi::create([
            'user_id' => $user->id,
            'nama_donasi' => 'Donasi C',
            'jumlah' => 150000,
            'status' => 'Expired',
            'kode_transaksi' => 'TX-003',
        ]);

        $response = $this->actingAs($admin)->get('/admin/donasi');

        $response->assertStatus(200);
        $response->assertSee('Menunggu'); // mapped from Pending
        $response->assertSee('Berhasil');  // mapped from Sukses
        $response->assertSee('Kadaluarsa'); // mapped from Expired
    }

    /**
     * Test Case 5: GET /admin/reports/donasi.csv returns valid CSV headers
     */
    public function test_export_donasi_csv_returns_valid_file_and_headers(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create();

        Donasi::create([
            'user_id' => $user->id,
            'nama_donasi' => 'Donasi A',
            'jumlah' => 50000,
            'status' => 'Sukses',
            'kode_transaksi' => 'TX-001',
        ]);

        $response = $this->actingAs($admin)->get('/admin/reports/donasi.csv');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        
        $content = $response->streamedContent();
        
        // Assert header is present
        $this->assertStringContainsString('ID Donasi', $content);
        $this->assertStringContainsString('Nama Pengguna', $content);
        $this->assertStringContainsString('Nominal', $content);
        $this->assertStringContainsString('Tanggal Donasi', $content);
        $this->assertStringContainsString('Status Pembayaran', $content);
    }

    /**
     * Test Case 6: CSV streaming handles large dataset simulation correctly
     */
    public function test_export_donasi_csv_handles_large_dataset_redirect(): void
    {
        $admin = $this->createAdmin();
        
        // Trigger simulation parameter
        $response = $this->actingAs($admin)->get('/admin/reports/donasi.csv?simulate_large=true');

        $response->assertRedirect(route('admin.donasi.index'));
        $response->assertSessionHas('error', 'Export sedang diproses, file akan tersedia sebentar lagi');
    }
}
