<?php

namespace Tests\Feature;

use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature Test: Notifikasi – Tandai Sudah Dibaca (PB-24)
 *
 * Coverage:
 *   - Unit: tombol tandai satu → is_read=true, read_at terisi
 *   - Unit: badge counter berkurang 1
 *   - Unit: tandai semua → semua is_read=true
 *   - Integration: PATCH /notifikasi/{id}/baca → 200, is_read=true
 *   - Integration: PATCH /notifikasi/baca-semua → semua dibaca
 *   - Integration: PATCH notifikasi user lain → 404 (ownership check)
 *   - Access: user belum login → redirect ke /login
 *   - Access: tab Sudah Dibaca → notifikasi terbaca tampil
 *   - Access: empty state → 'Tidak ada notifikasi baru'
 */
class NotifikasiFeatureTest extends TestCase
{
    use RefreshDatabase;

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function createUser(string $email = 'user@greennovate.test'): User
    {
        return User::factory()->create([
            'email'     => $email,
            'role'      => User::ROLE_USER,
            'is_active' => true,
        ]);
    }

    private function createAdmin(): User
    {
        return User::factory()->create([
            'email'     => 'admin@greennovate.test',
            'role'      => User::ROLE_ADMIN,
            'is_active' => true,
        ]);
    }

    private function buatNotifikasi(User $user, array $override = []): Notifikasi
    {
        return Notifikasi::create(array_merge([
            'user_id' => $user->id,
            'judul'   => 'Donasi Berhasil',
            'pesan'   => 'Terima kasih atas donasi Anda sebesar Rp 100.000.',
            'tipe'    => 'donasi',
            'is_read' => false,
            'read_at' => null,
        ], $override));
    }

    // ── Test Case 1: User dapat mengakses halaman notifikasi ─────────────────

    public function test_user_dapat_akses_halaman_notifikasi()
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)->get('/notifikasi');

        $response->assertStatus(200);
        $response->assertViewIs('notifikasi.index');
    }

    // ── Test Case 2: Halaman notifikasi menampilkan daftar belum dibaca ──────

    public function test_halaman_notifikasi_tampilkan_daftar_belum_dibaca()
    {
        $user  = $this->createUser();
        $notif = $this->buatNotifikasi($user);

        $response = $this->actingAs($user)->get('/notifikasi');

        $response->assertStatus(200);
        $response->assertSee($notif->judul);
        $response->assertSee('Tandai sudah dibaca');
    }

    // ── Test Case 3: Tandai satu notifikasi sebagai sudah dibaca ─────────────

    public function test_tandai_satu_notifikasi_dibaca()
    {
        $user  = $this->createUser();
        $notif = $this->buatNotifikasi($user);

        $response = $this->actingAs($user)
            ->patchJson("/notifikasi/{$notif->id}/baca");

        $response->assertStatus(200);
        $response->assertJson(['unread_count' => 0]);

        // Verifikasi di database
        $this->assertDatabaseHas('notifikasis', [
            'id'      => $notif->id,
            'is_read' => true,
        ]);

        // read_at harus terisi
        $notif->refresh();
        $this->assertNotNull($notif->read_at);
    }

    // ── Test Case 4: Badge counter berkurang 1 setelah tandai satu ───────────

    public function test_badge_counter_berkurang_setelah_tandai_satu()
    {
        $user   = $this->createUser();
        $notif1 = $this->buatNotifikasi($user, ['judul' => 'Notif 1']);
        $notif2 = $this->buatNotifikasi($user, ['judul' => 'Notif 2']);

        // Sebelum: 2 belum dibaca
        $this->assertEquals(2, Notifikasi::where('user_id', $user->id)->where('is_read', false)->count());

        $response = $this->actingAs($user)
            ->patchJson("/notifikasi/{$notif1->id}/baca");

        $response->assertStatus(200);
        $response->assertJson(['unread_count' => 1]);

        // Setelah: 1 belum dibaca
        $this->assertEquals(1, Notifikasi::where('user_id', $user->id)->where('is_read', false)->count());
    }

    // ── Test Case 5: Tandai semua notifikasi dibaca ───────────────────────────

    public function test_tandai_semua_notifikasi_dibaca()
    {
        $user = $this->createUser();
        $this->buatNotifikasi($user, ['judul' => 'Notif A']);
        $this->buatNotifikasi($user, ['judul' => 'Notif B']);
        $this->buatNotifikasi($user, ['judul' => 'Notif C']);

        $response = $this->actingAs($user)
            ->patchJson('/notifikasi/baca-semua');

        $response->assertStatus(200);
        $response->assertJson(['unread_count' => 0]);

        // Semua notifikasi user harus is_read=true
        $belumDibaca = Notifikasi::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        $this->assertEquals(0, $belumDibaca);
    }

    // ── Test Case 6: Tidak ada notifikasi belum dibaca → empty state ──────────

    public function test_empty_state_saat_tidak_ada_notifikasi_belum_dibaca()
    {
        $user = $this->createUser();
        // Tidak ada notifikasi sama sekali

        $response = $this->actingAs($user)->get('/notifikasi');

        $response->assertStatus(200);
        $response->assertSee('Tidak ada notifikasi baru');
    }

    // ── Test Case 7: User refresh halaman → status tetap tersimpan ───────────

    public function test_status_terbaca_tersimpan_permanen_setelah_refresh()
    {
        $user  = $this->createUser();
        $notif = $this->buatNotifikasi($user);

        // Tandai dibaca
        $this->actingAs($user)->patchJson("/notifikasi/{$notif->id}/baca");

        // Refresh halaman → verifikasi database masih is_read=true
        $notif->refresh();
        $this->assertTrue($notif->is_read);
        $this->assertNotNull($notif->read_at);

        // Akses halaman lagi → notif tidak muncul di belum dibaca
        $response = $this->actingAs($user)->get('/notifikasi');
        $response->assertDontSee($notif->judul);
    }

    // ── Test Case 8: Akses notifikasi milik user lain → 404 (ownership) ──────

    public function test_user_tidak_bisa_tandai_notifikasi_milik_user_lain()
    {
        $userA = $this->createUser('a@test.com');
        $userB = $this->createUser('b@test.com');

        $notifA = $this->buatNotifikasi($userA);

        // UserB coba tandai notifikasi milik UserA
        $response = $this->actingAs($userB)
            ->patchJson("/notifikasi/{$notifA->id}/baca");

        $response->assertStatus(404);

        // Notifikasi UserA harus tetap belum dibaca
        $this->assertDatabaseHas('notifikasis', [
            'id'      => $notifA->id,
            'is_read' => false,
        ]);
    }

    // ── Test Case 9: Tandai semua hanya mempengaruhi notifikasi user login ────

    public function test_baca_semua_hanya_mempengaruhi_notifikasi_user_sendiri()
    {
        $userA = $this->createUser('a@test.com');
        $userB = $this->createUser('b@test.com');

        $notifA = $this->buatNotifikasi($userA, ['judul' => 'Notif UserA']);
        $notifB = $this->buatNotifikasi($userB, ['judul' => 'Notif UserB']);

        // UserA tandai semua
        $this->actingAs($userA)->patchJson('/notifikasi/baca-semua');

        // Notifikasi UserA → dibaca
        $this->assertDatabaseHas('notifikasis', [
            'id'      => $notifA->id,
            'is_read' => true,
        ]);

        // Notifikasi UserB → TETAP belum dibaca
        $this->assertDatabaseHas('notifikasis', [
            'id'      => $notifB->id,
            'is_read' => false,
        ]);
    }

    // ── Test Case 10: User belum login → redirect ke /login ──────────────────

    public function test_user_belum_login_diarahkan_ke_login()
    {
        $response = $this->get('/notifikasi');
        $response->assertRedirect('/login');
    }

    public function test_patch_baca_tanpa_login_diarahkan_ke_login()
    {
        $user  = $this->createUser();
        $notif = $this->buatNotifikasi($user);

        $response = $this->patch("/notifikasi/{$notif->id}/baca");
        $response->assertRedirect('/login');
    }

    // ── Test Case 11: Tab Sudah Dibaca menampilkan notifikasi terbaca ─────────

    public function test_tab_sudah_dibaca_tampilkan_notifikasi_terbaca()
    {
        $user  = $this->createUser();
        $notif = $this->buatNotifikasi($user, [
            'is_read' => true,
            'read_at' => now(),
            'judul'   => 'Notifikasi Sudah Dibaca',
        ]);

        $response = $this->actingAs($user)->get('/notifikasi?tab=sudah_dibaca');

        $response->assertStatus(200);
        $response->assertSee($notif->judul);
        $response->assertSee('Dibaca');
    }

    // ── Test Case 12: Admin dapat akses log notifikasi ────────────────────────

    public function test_admin_dapat_akses_log_notifikasi()
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get('/admin/notifikasi');

        $response->assertStatus(200);
        $response->assertViewIs('admin.notifikasi.index');
    }

    // ── Test Case 13: Notifikasi tidak ditemukan → 404 ───────────────────────

    public function test_notifikasi_tidak_ditemukan_return_404()
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)
            ->patchJson('/notifikasi/999999/baca');

        $response->assertStatus(404);
    }
}
