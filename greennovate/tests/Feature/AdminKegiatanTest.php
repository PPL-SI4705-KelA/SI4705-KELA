<?php

namespace Tests\Feature;

use App\Models\Kegiatan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature test: Admin CRUD Kegiatan (GN-12)
 */
class AdminKegiatanTest extends TestCase
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

    private function kegiatan(array $attrs = []): Kegiatan
    {
        return Kegiatan::create(array_merge([
            'nama'      => 'Tanam Pohon Samarinda',
            'lokasi'    => 'Samarinda',
            'tanggal'   => now()->addDays(7)->toDateString(),
            'deskripsi' => 'Deskripsi test',
            'target'    => 100,
            'kuota'     => 30,
            'status'    => 'aktif',
        ], $attrs));
    }

    // ── Index ─────────────────────────────────────────────────────────────────

    public function test_admin_can_view_kegiatan_index(): void
    {
        $response = $this->actingAs($this->admin())->get(route('admin.kegiatan.index'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.kegiatan.index');
    }

    public function test_user_cannot_view_kegiatan_index(): void
    {
        $response = $this->actingAs($this->user())->get(route('admin.kegiatan.index'));
        $response->assertStatus(403);
    }

    // ── Store ─────────────────────────────────────────────────────────────────

    /** Integration: POST /admin/kegiatan valid → 302 + row created */
    public function test_admin_can_store_valid_kegiatan(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->post(route('admin.kegiatan.store'), [
            'nama'      => 'Tanam Pohon Samarinda',
            'lokasi'    => 'Samarinda',
            'tanggal'   => now()->addDays(5)->toDateString(),
            'deskripsi' => 'Kegiatan penghijauan kota',
            'target'    => 50,
            'kuota'     => 30,
            'status'    => 'aktif',
        ]);

        $response->assertRedirect(route('admin.kegiatan.index'));
        $this->assertDatabaseHas('kegiatans', [
            'nama'   => 'Tanam Pohon Samarinda',
            'lokasi' => 'Samarinda',
            'kuota'  => 30,
        ]);
    }

    /** Unit: validasi kuota >= 0 */
    public function test_store_fails_with_negative_kuota(): void
    {
        $response = $this->actingAs($this->admin())->post(route('admin.kegiatan.store'), [
            'nama'    => 'Test',
            'lokasi'  => 'Test',
            'tanggal' => now()->addDay()->toDateString(),
            'target'  => 10,
            'kuota'   => -1,
            'status'  => 'aktif',
        ]);

        $response->assertSessionHasErrors('kuota');
    }

    /** Unit: validasi target >= 0 */
    public function test_store_fails_with_negative_target(): void
    {
        $response = $this->actingAs($this->admin())->post(route('admin.kegiatan.store'), [
            'nama'    => 'Test',
            'lokasi'  => 'Test',
            'tanggal' => now()->addDay()->toDateString(),
            'target'  => -5,
            'kuota'   => 10,
            'status'  => 'aktif',
        ]);

        $response->assertSessionHasErrors('target');
    }

    /** Data tidak lengkap → error validasi */
    public function test_store_fails_when_required_fields_missing(): void
    {
        $response = $this->actingAs($this->admin())->post(route('admin.kegiatan.store'), []);

        $response->assertSessionHasErrors(['nama', 'lokasi', 'tanggal', 'target', 'kuota', 'status']);
    }

    // ── Update ────────────────────────────────────────────────────────────────

    public function test_admin_can_update_kegiatan(): void
    {
        $kegiatan = $this->kegiatan();

        $response = $this->actingAs($this->admin())->put(route('admin.kegiatan.update', $kegiatan), [
            'nama'    => 'Nama Baru',
            'lokasi'  => 'Lokasi Baru',
            'tanggal' => now()->addDays(10)->toDateString(),
            'target'  => 200,
            'kuota'   => 50,
            'status'  => 'nonaktif',
        ]);

        $response->assertRedirect(route('admin.kegiatan.index'));
        $this->assertDatabaseHas('kegiatans', [
            'id'     => $kegiatan->id,
            'nama'   => 'Nama Baru',
            'status' => 'nonaktif',
        ]);
    }

    // ── Destroy ───────────────────────────────────────────────────────────────

    /** Admin hapus kegiatan tanpa pendaftar → soft deleted */
    public function test_admin_can_soft_delete_kegiatan_without_pendaftar(): void
    {
        $kegiatan = $this->kegiatan();

        $response = $this->actingAs($this->admin())
            ->delete(route('admin.kegiatan.destroy', $kegiatan));

        $response->assertRedirect(route('admin.kegiatan.index'));
        $this->assertSoftDeleted('kegiatans', ['id' => $kegiatan->id]);
    }

    /** Non-admin tidak bisa hapus */
    public function test_user_cannot_delete_kegiatan(): void
    {
        $kegiatan = $this->kegiatan();

        $response = $this->actingAs($this->user())
            ->delete(route('admin.kegiatan.destroy', $kegiatan));

        $response->assertStatus(403);
    }
}
