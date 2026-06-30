<?php

namespace Tests\Feature;

use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NotifikasiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Unit: tandaiDibaca mengubah is_read = true dan mengisi read_at.
     */
    public function test_tandai_dibaca_model_method()
    {
        $user = User::factory()->create();
        $notifikasi = Notifikasi::create([
            'user_id' => $user->id,
            'judul' => 'Test',
            'pesan' => 'Test pesan',
            'tipe' => 'sistem',
            'is_read' => false,
        ]);

        $this->assertFalse($notifikasi->is_read);
        $this->assertNull($notifikasi->read_at);

        $notifikasi->tandaiDibaca();

        $this->assertTrue($notifikasi->is_read);
        $this->assertNotNull($notifikasi->read_at);
    }

    /**
     * Test Access Control: Unauthenticated user redirected to login.
     */
    public function test_guest_cannot_access_notifikasi()
    {
        $response = $this->get('/notifikasi');
        $response->assertRedirect('/login');
    }

    /**
     * Test Access Control & Integration: User can view notifikasi.
     */
    public function test_user_can_view_notifikasi()
    {
        $user = User::factory()->create(['is_active' => true]);
        
        Notifikasi::create([
            'user_id' => $user->id,
            'judul' => 'Judul Notif',
            'pesan' => 'Pesan Notif',
            'tipe' => 'sistem',
            'is_read' => false,
        ]);

        $response = $this->actingAs($user)->get('/notifikasi');
        $response->assertStatus(200);
        $response->assertSee('Judul Notif');
        $response->assertSee('Pesan Notif');
    }

    /**
     * Test Integration: PATCH /notifikasi/{id}/baca.
     */
    public function test_user_can_mark_one_notifikasi_as_read()
    {
        $user = User::factory()->create(['is_active' => true]);
        
        $notifikasi = Notifikasi::create([
            'user_id' => $user->id,
            'judul' => 'Judul Notif',
            'pesan' => 'Pesan Notif',
            'tipe' => 'sistem',
            'is_read' => false,
        ]);

        // Expect JSON response for AJAX request
        $response = $this->actingAs($user)
            ->withHeaders(['Accept' => 'application/json'])
            ->patchJson("/notifikasi/{$notifikasi->id}/baca");

        $response->assertStatus(200);
        $response->assertJsonStructure(['message', 'unread_count']);
        
        $this->assertDatabaseHas('notifikasis', [
            'id' => $notifikasi->id,
            'is_read' => true,
        ]);
        
        $this->assertNotNull(Notifikasi::find($notifikasi->id)->read_at);
    }

    /**
     * Test Integration & Access Control: User cannot mark another user's notifikasi as read.
     */
    public function test_user_cannot_mark_other_users_notifikasi_as_read()
    {
        $user1 = User::factory()->create(['is_active' => true]);
        $user2 = User::factory()->create(['is_active' => true]);
        
        $notifikasiUser2 = Notifikasi::create([
            'user_id' => $user2->id,
            'judul' => 'Judul Notif User 2',
            'pesan' => 'Pesan Notif User 2',
            'tipe' => 'sistem',
            'is_read' => false,
        ]);

        $response = $this->actingAs($user1)
            ->withHeaders(['Accept' => 'application/json'])
            ->patchJson("/notifikasi/{$notifikasiUser2->id}/baca");

        // Controller returns 404 when not found by user_id
        $response->assertStatus(404);
        
        $this->assertDatabaseHas('notifikasis', [
            'id' => $notifikasiUser2->id,
            'is_read' => false,
        ]);
    }

    /**
     * Test Integration: PATCH /notifikasi/baca-semua.
     */
    public function test_user_can_mark_all_notifikasi_as_read()
    {
        $user = User::factory()->create(['is_active' => true]);
        
        Notifikasi::create(['user_id' => $user->id, 'judul' => 'N1', 'pesan' => 'P1', 'tipe' => 'sistem', 'is_read' => false]);
        Notifikasi::create(['user_id' => $user->id, 'judul' => 'N2', 'pesan' => 'P2', 'tipe' => 'sistem', 'is_read' => false]);
        
        $user2 = User::factory()->create(['is_active' => true]);
        Notifikasi::create(['user_id' => $user2->id, 'judul' => 'N3', 'pesan' => 'P3', 'tipe' => 'sistem', 'is_read' => false]);

        $response = $this->actingAs($user)
            ->withHeaders(['Accept' => 'application/json'])
            ->patchJson("/notifikasi/baca-semua");

        $response->assertStatus(200);
        $response->assertJson(['unread_count' => 0]);
        
        // Ensure user 1's notifications are read
        $this->assertEquals(0, Notifikasi::where('user_id', $user->id)->belumDibaca()->count());
        $this->assertEquals(2, Notifikasi::where('user_id', $user->id)->sudahDibaca()->count());
        
        // Ensure user 2's notifications are NOT read
        $this->assertEquals(1, Notifikasi::where('user_id', $user2->id)->belumDibaca()->count());
    }

    /**
     * Test Integration: Admin can view all notifications.
     */
    public function test_admin_can_view_all_notifikasi()
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        $user = User::factory()->create(['is_active' => true]);
        
        Notifikasi::create(['user_id' => $user->id, 'judul' => 'N1', 'pesan' => 'P1', 'tipe' => 'sistem', 'is_read' => false]);

        $response = $this->actingAs($admin)->get('/admin/notifikasi');
        $response->assertStatus(200);
        $response->assertSee('N1');
    }
}
