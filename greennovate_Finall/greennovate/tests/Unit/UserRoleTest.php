<?php

namespace Tests\Unit;

use App\Models\User;
use PHPUnit\Framework\TestCase;

/**
 * Unit test: User role helper methods
 *
 * Test case ini memverifikasi bahwa method isAdmin(), isPetugas(), isUser()
 * mengembalikan nilai yang benar sesuai role masing-masing.
 * Tidak memerlukan database (extends TestCase bukan RefreshDatabase).
 */
class UserRoleTest extends TestCase
{
    /** Helper: buat instance User tanpa database. */
    private function makeUser(string $role): User
    {
        $user = new User();
        $user->role = $role;
        return $user;
    }

    // ── isAdmin() ──────────────────────────────────────────────────────────────

    public function test_isAdmin_returns_true_for_admin_role(): void
    {
        $user = $this->makeUser('admin');
        $this->assertTrue($user->isAdmin());
    }

    public function test_isAdmin_returns_false_for_user_role(): void
    {
        $user = $this->makeUser('user');
        $this->assertFalse($user->isAdmin());
    }

    public function test_isAdmin_returns_false_for_petugas_role(): void
    {
        $user = $this->makeUser('petugas');
        $this->assertFalse($user->isAdmin());
    }

    // ── isPetugas() ────────────────────────────────────────────────────────────

    public function test_isPetugas_returns_true_for_petugas_role(): void
    {
        $user = $this->makeUser('petugas');
        $this->assertTrue($user->isPetugas());
    }

    public function test_isPetugas_returns_false_for_admin_role(): void
    {
        $user = $this->makeUser('admin');
        $this->assertFalse($user->isPetugas());
    }

    public function test_isPetugas_returns_false_for_user_role(): void
    {
        $user = $this->makeUser('user');
        $this->assertFalse($user->isPetugas());
    }

    // ── isUser() ───────────────────────────────────────────────────────────────

    public function test_isUser_returns_true_for_user_role(): void
    {
        $user = $this->makeUser('user');
        $this->assertTrue($user->isUser());
    }

    public function test_isUser_returns_false_for_admin_role(): void
    {
        $user = $this->makeUser('admin');
        $this->assertFalse($user->isUser());
    }

    // ── hasRole() ──────────────────────────────────────────────────────────────

    public function test_hasRole_returns_true_when_role_in_array(): void
    {
        $user = $this->makeUser('admin');
        $this->assertTrue($user->hasRole(['admin', 'petugas']));
    }

    public function test_hasRole_returns_false_when_role_not_in_array(): void
    {
        $user = $this->makeUser('user');
        $this->assertFalse($user->hasRole(['admin', 'petugas']));
    }

    public function test_hasRole_accepts_string(): void
    {
        $user = $this->makeUser('admin');
        $this->assertTrue($user->hasRole('admin'));
    }
}
