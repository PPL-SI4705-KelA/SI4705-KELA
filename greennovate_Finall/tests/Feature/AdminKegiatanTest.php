<?php

namespace Tests\Feature;

use App\Models\Kegiatan;
use App\Models\User;
use App\Models\LokasiLahan;
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
        $lokasi = LokasiLahan::first() ?? LokasiLahan::create([
            'nama' => 'Lahan Induk',
            'alamat' => 'Jl. Hijau',
            'latitude' => 0.0,
            'longitude' => 0.0,
        ]);

        $petugas = User::where('role', 'petugas')->first() ?? User::factory()->create([
            'role' => 'petugas',
        ]);

        return Kegiatan::create(array_merge([
            'nama'            => 'Tanam Pohon Samarinda',
            'lokasi_lahan_id' => $lokasi->id,
            'petugas_id'      => $petugas->id,
            'tanggal'         => now()->addDays(7)->toDateString(),
            'deskripsi'       => 'Deskripsi test',
            'target_pohon'    => 100,
            'quota'           => 30,
            'status'          => 'Berlangsung',
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
        $lokasi = LokasiLahan::create([
            'nama' => 'Lahan Test',
            'alamat' => 'Alamat Test',
            'latitude' => 0.0,
            'longitude' => 0.0,
        ]);
        $petugas = User::factory()->create(['role' => 'petugas']);

        $response = $this->actingAs($admin)->post(route('admin.kegiatan.store'), [
            'nama'            => 'Tanam Pohon Samarinda',
            'lokasi_lahan_id' => $lokasi->id,
            'petugas_id'      => $petugas->id,
            'tanggal'         => now()->addDays(5)->toDateString(),
            'deskripsi'       => 'Kegiatan penghijauan kota',
            'target_pohon'    => 50,
            'quota'           => 30,
            'status'          => 'Berlangsung',
        ]);

        $response->assertRedirect(route('admin.kegiatan.index'));
        $this->assertDatabaseHas('kegiatan', [
            'nama'            => 'Tanam Pohon Samarinda',
            'lokasi_lahan_id' => $lokasi->id,
            'quota'           => 30,
        ]);
    }

    /** Unit: validasi kuota >= 1 */
    public function test_store_fails_with_negative_kuota(): void
    {
        $response = $this->actingAs($this->admin())->post(route('admin.kegiatan.store'), [
            'nama'    => 'Test',
            'quota'   => -1,
        ]);

        $response->assertSessionHasErrors('quota');
    }

    /** Unit: validasi target >= 0 */
    public function test_store_fails_with_negative_target(): void
    {
        $response = $this->actingAs($this->admin())->post(route('admin.kegiatan.store'), [
            'nama'         => 'Test',
            'target_pohon' => -5,
        ]);

        $response->assertSessionHasErrors('target_pohon');
    }

    /** Data tidak lengkap → error validasi */
    public function test_store_fails_when_required_fields_missing(): void
    {
        $response = $this->actingAs($this->admin())->post(route('admin.kegiatan.store'), []);

        $response->assertSessionHasErrors(['nama', 'lokasi_lahan_id', 'petugas_id', 'tanggal', 'target_pohon', 'quota', 'status']);
    }

    // ── Update ────────────────────────────────────────────────────────────────

    public function test_admin_can_update_kegiatan(): void
    {
        $kegiatan = $this->kegiatan();
        $lokasi2 = LokasiLahan::create([
            'nama' => 'Lahan Test 2',
            'alamat' => 'Alamat Test 2',
            'latitude' => 0.0,
            'longitude' => 0.0,
        ]);
        $petugas2 = User::factory()->create(['role' => 'petugas']);

        $response = $this->actingAs($this->admin())->put(route('admin.kegiatan.update', $kegiatan), [
            'nama'            => 'Nama Baru',
            'lokasi_lahan_id' => $lokasi2->id,
            'petugas_id'      => $petugas2->id,
            'tanggal'         => now()->addDays(10)->toDateString(),
            'target_pohon'    => 200,
            'quota'           => 50,
            'status'          => 'Dibatalkan',
        ]);

        $response->assertRedirect(route('admin.kegiatan.index'));
        $this->assertDatabaseHas('kegiatan', [
            'id'     => $kegiatan->id,
            'nama'   => 'Nama Baru',
            'status' => 'Dibatalkan',
        ]);
    }

    // ── Destroy ───────────────────────────────────────────────────────────────

    /** Admin hapus kegiatan tanpa pendaftar → soft deleted */
    public function test_admin_can_soft_delete_kegiatan_without_pendaftar(): void
    {
        $kegiatan = $this->kegiatan(['status' => 'Persiapan']);

        $response = $this->actingAs($this->admin())
            ->delete(route('admin.kegiatan.destroy', $kegiatan));

        $response->assertRedirect(route('admin.kegiatan.index'));
        $this->assertSoftDeleted('kegiatan', ['id' => $kegiatan->id]);
    }

    /** Non-admin tidak bisa hapus */
    public function test_user_cannot_delete_kegiatan(): void
    {
        $kegiatan = $this->kegiatan(['status' => 'Persiapan']);

        $response = $this->actingAs($this->user())
            ->delete(route('admin.kegiatan.destroy', $kegiatan));

        $response->assertStatus(403);
    }
}
