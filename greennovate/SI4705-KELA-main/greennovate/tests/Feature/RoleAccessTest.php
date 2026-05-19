<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature test: Role-based access control (RBAC)
 *
 * Skenario yang diuji:
 * 1. User biasa mengakses /admin/dashboard → expect 403
 * 2. Admin mengakses /admin/dashboard → expect 200 (redirect ke admin.dashboard)
 * 3. Petugas mengakses /admin/dashboard → expect 403
 * 4. User biasa mengakses /petugas/dashboard → expect 403
 * 5. Tamu (belum login) mengakses /admin/dashboard → expect redirect ke login
 */
class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    // ── Helper factories ───────────────────────────────────────────────────────

    private function createAdmin(): User
    {
        return User::factory()->create([
            'role'      => User::ROLE_ADMIN,
            'is_active' => true,
        ]);
    }

    private function createPetugas(): User
    {
        return User::factory()->create([
            'role'      => User::ROLE_PETUGAS,
            'is_active' => true,
        ]);
    }

    private function createUser(): User
    {
        return User::factory()->create([
            'role'      => User::ROLE_USER,
            'is_active' => true,
        ]);
    }

    // ── Admin dashboard ────────────────────────────────────────────────────────

    /** Integration: user biasa coba akses /admin/dashboard → 403 */
    public function test_user_cannot_access_admin_dashboard(): void
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)->get('/admin/dashboard');

        $response->assertStatus(403);
    }

    /** Integration: petugas coba akses /admin/dashboard → 403 */
    public function test_petugas_cannot_access_admin_dashboard(): void
    {
        $petugas = $this->createPetugas();

        $response = $this->actingAs($petugas)->get('/admin/dashboard');

        $response->assertStatus(403);
    }

    /** Integration: admin akses /admin/dashboard → 200 */
    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
    }

    // ── Petugas dashboard ──────────────────────────────────────────────────────

    /** Integration: user biasa coba akses /petugas/dashboard → 403 */
    public function test_user_cannot_access_petugas_dashboard(): void
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)->get('/petugas/dashboard');

        $response->assertStatus(403);
    }

    /** Integration: admin coba akses /petugas/dashboard → 403 */
    public function test_admin_cannot_access_petugas_dashboard(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get('/petugas/dashboard');

        $response->assertStatus(403);
    }

    /** Integration: petugas akses /petugas/dashboard → 200 */
    public function test_petugas_can_access_petugas_dashboard(): void
    {
        $petugas = $this->createPetugas();

        $response = $this->actingAs($petugas)->get('/petugas/dashboard');

        $response->assertStatus(200);
    }

    // ── Tamu ───────────────────────────────────────────────────────────────────

    /** Tamu (belum login) akses /admin/dashboard → redirect ke login */
    public function test_guest_is_redirected_to_login_when_accessing_admin(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/login');
    }

    // ── Dashboard dispatcher ───────────────────────────────────────────────────

    /** Admin login → /dashboard → redirect ke /admin/dashboard */
    public function test_admin_dashboard_redirect_goes_to_admin_route(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get('/dashboard');

        $response->assertRedirect(route('admin.dashboard'));
    }

    /** Petugas login → /dashboard → redirect ke /petugas/dashboard */
    public function test_petugas_dashboard_redirect_goes_to_petugas_route(): void
    {
        $petugas = $this->createPetugas();

        $response = $this->actingAs($petugas)->get('/dashboard');

        $response->assertRedirect(route('petugas.dashboard'));
    }

    /** User login → /dashboard → view user.dashboard (200) */
    public function test_user_dashboard_shows_user_view(): void
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('user.dashboard');
    }
}
