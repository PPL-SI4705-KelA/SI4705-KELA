<?php

namespace Tests\Feature;

use App\Models\Donasi;
use App\Models\Kegiatan;
use App\Models\LokasiLahan;
use App\Models\Pembelian;
use App\Models\PendaftaranKegiatan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * E2E Feature Test untuk Riwayat Partisipasi (PB-11).
 *
 * Skenario:
 * 1. User login.
 * 2. Membuka halaman riwayat.
 * 3. Klik salah satu item "Sukses".
 * 4. Memastikan Modal Detail terbuka dan menampilkan informasi yang akurat.
 */
class RiwayatTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name'      => 'Test User',
            'email'     => 'testuser@example.com',
            'password'  => bcrypt('password'),
            'role'      => 'user',
            'is_active' => true,
        ]);
    }

    /**
     * Test: User yang sudah login dapat mengakses halaman riwayat.
     */
    public function test_authenticated_user_can_access_riwayat_page(): void
    {
        $response = $this->actingAs($this->user)->get(route('riwayat.index'));
        $response->assertStatus(200);
        $response->assertSee('Riwayat Partisipasi');
    }

    /**
     * Test: User yang belum login diarahkan ke halaman login.
     */
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('riwayat.index'));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test: Halaman riwayat menampilkan data donasi milik user.
     */
    public function test_riwayat_page_shows_user_donasi(): void
    {
        Donasi::create([
            'user_id'         => $this->user->id,
            'nama_donasi'     => 'Donasi Bibit Pohon',
            'jumlah'          => 150000,
            'status'          => 'Sukses',
            'kode_transaksi'  => 'DON-TEST001',
        ]);

        $response = $this->actingAs($this->user)->get(route('riwayat.index'));
        $response->assertStatus(200);
        $response->assertSee('Donasi Bibit Pohon');
        $response->assertSee('Sukses');
    }

    /**
     * Test: Halaman riwayat menampilkan data pembelian milik user.
     */
    public function test_riwayat_page_shows_user_pembelian(): void
    {
        Pembelian::create([
            'user_id'         => $this->user->id,
            'nama_item'       => 'Bibit Jati',
            'jumlah_item'     => 3,
            'total_harga'     => 120000,
            'status'          => 'Sukses',
            'kode_transaksi'  => 'PBL-TEST001',
        ]);

        $response = $this->actingAs($this->user)->get(route('riwayat.index'));
        $response->assertStatus(200);
        $response->assertSee('Bibit Jati');
    }

    /**
     * Test: Detail donasi endpoint mengembalikan JSON yang benar.
     */
    public function test_detail_endpoint_returns_correct_json_for_donasi(): void
    {
        $donasi = Donasi::create([
            'user_id'         => $this->user->id,
            'nama_donasi'     => 'Donasi Detail Test',
            'jumlah'          => 200000,
            'status'          => 'Sukses',
            'kode_transaksi'  => 'DON-DETAIL01',
        ]);

        $response = $this->actingAs($this->user)
            ->getJson(route('riwayat.detail', ['tipe' => 'donasi', 'id' => $donasi->id]));

        $response->assertStatus(200);
        $response->assertJson([
            'tipe'         => 'donasi',
            'nama'         => 'Donasi Detail Test',
            'status_label' => 'Sukses',
        ]);
    }

    /**
     * Test: User tidak bisa mengakses detail milik user lain (Secure Download).
     */
    public function test_user_cannot_access_other_users_detail(): void
    {
        $otherUser = User::create([
            'name'      => 'Other User',
            'email'     => 'other@example.com',
            'password'  => bcrypt('password'),
            'role'      => 'user',
            'is_active' => true,
        ]);

        $donasi = Donasi::create([
            'user_id'         => $otherUser->id,
            'nama_donasi'     => 'Donasi Orang Lain',
            'jumlah'          => 100000,
            'status'          => 'Sukses',
            'kode_transaksi'  => 'DON-OTHER01',
        ]);

        $response = $this->actingAs($this->user)
            ->getJson(route('riwayat.detail', ['tipe' => 'donasi', 'id' => $donasi->id]));

        $response->assertStatus(404);
    }

    /**
     * Test: Filter tipe pada halaman riwayat.
     */
    public function test_riwayat_filter_by_tipe(): void
    {
        Donasi::create([
            'user_id'         => $this->user->id,
            'nama_donasi'     => 'Donasi Filter Test',
            'jumlah'          => 50000,
            'status'          => 'Pending',
            'kode_transaksi'  => 'DON-FILTER01',
        ]);

        Pembelian::create([
            'user_id'         => $this->user->id,
            'nama_item'       => 'Pembelian Filter Test',
            'jumlah_item'     => 1,
            'total_harga'     => 30000,
            'status'          => 'Sukses',
            'kode_transaksi'  => 'PBL-FILTER01',
        ]);

        // Filter donasi only
        $response = $this->actingAs($this->user)
            ->get(route('riwayat.index', ['tipe' => 'donasi']));
        $response->assertSee('Donasi Filter Test');
        $response->assertDontSee('Pembelian Filter Test');
    }

    /**
     * Test: Empty state ditampilkan ketika tidak ada riwayat.
     */
    public function test_empty_state_shown_when_no_riwayat(): void
    {
        $response = $this->actingAs($this->user)->get(route('riwayat.index'));
        $response->assertStatus(200);
        $response->assertSee('Belum Ada Riwayat');
    }

    /**
     * Test: API endpoint riwayat mengembalikan JSON dengan pagination.
     */
    public function test_api_riwayat_returns_paginated_json(): void
    {
        Donasi::create([
            'user_id'         => $this->user->id,
            'nama_donasi'     => 'API Donasi Test',
            'jumlah'          => 100000,
            'status'          => 'Sukses',
            'kode_transaksi'  => 'DON-API001',
        ]);

        $response = $this->actingAs($this->user)
            ->getJson(route('api.riwayat.index'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
            'current_page',
            'total',
        ]);
    }
}
