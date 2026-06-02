<?php

namespace Tests\Feature;

use App\Models\Kegiatan;
use App\Models\User;
<<<<<<< HEAD:greennovate/tests/Feature/AdminKegiatanTest.php
use App\Models\LokasiLahan;
=======
>>>>>>> main:greennovate/SI4705-KELA-main/greennovate/tests/Feature/AdminKegiatanTest.php
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
<<<<<<< HEAD:greennovate/tests/Feature/AdminKegiatanTest.php
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
=======
        return Kegiatan::create(array_merge([
            'nama'      => 'Tanam Pohon Samarinda',
            'lokasi'    => 'Samarinda',
            'tanggal'   => now()->addDays(7)->toDateString(),
            'deskripsi' => 'Deskripsi test',
            'target'    => 100,
            'kuota'     => 30,
            'status'    => 'aktif',
>>>>>>> main:greennovate/SI4705-KELA-main/greennovate/tests/Feature/AdminKegiatanTest.php
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
<<<<<<< HEAD:greennovate/tests/Feature/AdminKegiatanTest.php
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
=======

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
>>>>>>> main:greennovate/SI4705-KELA-main/greennovate/tests/Feature/AdminKegiatanTest.php
    public function test_store_fails_with_negative_kuota(): void
    {
        $response = $this->actingAs($this->admin())->post(route('admin.kegiatan.store'), [
            'nama'    => 'Test',
<<<<<<< HEAD:greennovate/tests/Feature/AdminKegiatanTest.php
            'quota'   => -1,
        ]);

        $response->assertSessionHasErrors('quota');
=======
            'lokasi'  => 'Test',
            'tanggal' => now()->addDay()->toDateString(),
            'target'  => 10,
            'kuota'   => -1,
            'status'  => 'aktif',
        ]);

        $response->assertSessionHasErrors('kuota');
>>>>>>> main:greennovate/SI4705-KELA-main/greennovate/tests/Feature/AdminKegiatanTest.php
    }

    /** Unit: validasi target >= 0 */
    public function test_store_fails_with_negative_target(): void
    {
        $response = $this->actingAs($this->admin())->post(route('admin.kegiatan.store'), [
<<<<<<< HEAD:greennovate/tests/Feature/AdminKegiatanTest.php
            'nama'         => 'Test',
            'target_pohon' => -5,
        ]);

        $response->assertSessionHasErrors('target_pohon');
=======
            'nama'    => 'Test',
            'lokasi'  => 'Test',
            'tanggal' => now()->addDay()->toDateString(),
            'target'  => -5,
            'kuota'   => 10,
            'status'  => 'aktif',
        ]);

        $response->assertSessionHasErrors('target');
>>>>>>> main:greennovate/SI4705-KELA-main/greennovate/tests/Feature/AdminKegiatanTest.php
    }

    /** Data tidak lengkap → error validasi */
    public function test_store_fails_when_required_fields_missing(): void
    {
        $response = $this->actingAs($this->admin())->post(route('admin.kegiatan.store'), []);

<<<<<<< HEAD:greennovate/tests/Feature/AdminKegiatanTest.php
        $response->assertSessionHasErrors(['nama', 'lokasi_lahan_id', 'petugas_id', 'tanggal', 'target_pohon', 'quota', 'status']);
=======
        $response->assertSessionHasErrors(['nama', 'lokasi', 'tanggal', 'target', 'kuota', 'status']);
>>>>>>> main:greennovate/SI4705-KELA-main/greennovate/tests/Feature/AdminKegiatanTest.php
    }

    // ── Update ────────────────────────────────────────────────────────────────

    public function test_admin_can_update_kegiatan(): void
    {
        $kegiatan = $this->kegiatan();
<<<<<<< HEAD:greennovate/tests/Feature/AdminKegiatanTest.php
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
=======

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
>>>>>>> main:greennovate/SI4705-KELA-main/greennovate/tests/Feature/AdminKegiatanTest.php
        ]);
    }

    // ── Destroy ───────────────────────────────────────────────────────────────

    /** Admin hapus kegiatan tanpa pendaftar → soft deleted */
    public function test_admin_can_soft_delete_kegiatan_without_pendaftar(): void
    {
<<<<<<< HEAD:greennovate/tests/Feature/AdminKegiatanTest.php
        $kegiatan = $this->kegiatan(['status' => 'Persiapan']);
=======
        $kegiatan = $this->kegiatan();
>>>>>>> main:greennovate/SI4705-KELA-main/greennovate/tests/Feature/AdminKegiatanTest.php

        $response = $this->actingAs($this->admin())
            ->delete(route('admin.kegiatan.destroy', $kegiatan));

        $response->assertRedirect(route('admin.kegiatan.index'));
<<<<<<< HEAD:greennovate/tests/Feature/AdminKegiatanTest.php
        $this->assertSoftDeleted('kegiatan', ['id' => $kegiatan->id]);
=======
        $this->assertSoftDeleted('kegiatans', ['id' => $kegiatan->id]);
>>>>>>> main:greennovate/SI4705-KELA-main/greennovate/tests/Feature/AdminKegiatanTest.php
    }

    /** Non-admin tidak bisa hapus */
    public function test_user_cannot_delete_kegiatan(): void
    {
<<<<<<< HEAD:greennovate/tests/Feature/AdminKegiatanTest.php
        $kegiatan = $this->kegiatan(['status' => 'Persiapan']);
=======
        $kegiatan = $this->kegiatan();
>>>>>>> main:greennovate/SI4705-KELA-main/greennovate/tests/Feature/AdminKegiatanTest.php

        $response = $this->actingAs($this->user())
            ->delete(route('admin.kegiatan.destroy', $kegiatan));

        $response->assertStatus(403);
    }
}
