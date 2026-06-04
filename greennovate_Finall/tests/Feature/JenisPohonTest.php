<?php

namespace Tests\Feature;

use App\Models\JenisPohon;
use App\Models\KategoriPohon;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature test: Admin Manajemen Jenis Pohon (PB-14)
 * Test HTTP ini jauh lebih cepat dan tidak memerlukan browser (Dusk).
 */
class JenisPohonTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin', 'is_active' => true]);
    }

    private function user(): User
    {
        return User::factory()->create(['role' => 'user', 'is_active' => true]);
    }

    private function kategori(): KategoriPohon
    {
        return KategoriPohon::create([
            'nama' => 'Kayu Keras',
            'deskripsi' => 'Pohon berkayu keras',
        ]);
    }

    // ── Akses (Role Authorization) ────────────────────────────────────────────

    public function test_admin_bisa_mengakses_halaman_jenis_pohon(): void
    {
        $response = $this->actingAs($this->admin())->get(route('admin.jenis-pohon.index'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.jenis-pohon.index');
    }

    public function test_user_biasa_ditolak_mengakses_halaman(): void
    {
        $response = $this->actingAs($this->user())->get(route('admin.jenis-pohon.index'));
        $response->assertStatus(403);
    }

    // ── Create (Store) ────────────────────────────────────────────────────────

    public function test_admin_bisa_menambahkan_jenis_pohon_baru(): void
    {
        $admin = $this->admin();
        $kategori = $this->kategori();

        $response = $this->actingAs($admin)->post(route('admin.jenis-pohon.store'), [
            'nama' => 'Mahoni',
            'nama_latin' => 'Swietenia macrophylla',
            'kategori_pohon_id' => $kategori->id,
            'harga' => 150000,
            'status' => 'active',
        ]);

        $response->assertRedirect(route('admin.jenis-pohon.index'));
        $this->assertDatabaseHas('jenis_pohons', [
            'nama' => 'Mahoni',
            'harga' => 150000.00,
            'created_by' => $admin->id,
        ]);
    }

    public function test_validasi_nama_terlalu_pendek(): void
    {
        $response = $this->actingAs($this->admin())->post(route('admin.jenis-pohon.store'), [
            'nama' => 'Ma', // Minimal 3
            'kategori_pohon_id' => $this->kategori()->id,
            'harga' => 50000,
        ]);

        $response->assertSessionHasErrors('nama');
    }

    public function test_validasi_harga_harus_lebih_dari_nol(): void
    {
        $response = $this->actingAs($this->admin())->post(route('admin.jenis-pohon.store'), [
            'nama' => 'Akasia',
            'kategori_pohon_id' => $this->kategori()->id,
            'harga' => -1000, // Tidak boleh minus
        ]);

        $response->assertSessionHasErrors('harga');
    }

    // ── Update ────────────────────────────────────────────────────────────────

    public function test_admin_bisa_mengubah_jenis_pohon(): void
    {
        $kategori = $this->kategori();
        $pohon = JenisPohon::create([
            'nama' => 'Jati Lama',
            'kategori_pohon_id' => $kategori->id,
            'harga' => 100000,
            'status' => 'active',
            'version' => 1,
        ]);

        $response = $this->actingAs($this->admin())->put(route('admin.jenis-pohon.update', $pohon), [
            'nama' => 'Jati Baru',
            'kategori_pohon_id' => $kategori->id,
            'harga' => 250000,
            'status' => 'active',
            'version' => 1, // Optimistic locking
        ]);

        $response->assertRedirect(route('admin.jenis-pohon.index'));
        $this->assertDatabaseHas('jenis_pohons', [
            'id' => $pohon->id,
            'nama' => 'Jati Baru',
            'harga' => 250000.00,
            'version' => 2,
        ]);
    }

    // ── Delete ────────────────────────────────────────────────────────────────

    public function test_admin_bisa_menghapus_jenis_pohon(): void
    {
        $pohon = JenisPohon::create([
            'nama' => 'Sengon',
            'kategori_pohon_id' => $this->kategori()->id,
            'harga' => 50000,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin())->delete(route('admin.jenis-pohon.destroy', $pohon));

        $response->assertRedirect(route('admin.jenis-pohon.index'));
        $this->assertSoftDeleted('jenis_pohons', [
            'id' => $pohon->id,
        ]);
    }
}
