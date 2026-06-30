<?php

namespace Tests\Feature;

use App\Models\QrCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QrCodeTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_qrcode_page()
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);

        $response = $this->actingAs($admin)->get('/admin/qrcode');

        $response->assertStatus(200);
        $response->assertViewIs('admin.qrcode.index');
    }

    public function test_user_cannot_view_admin_qrcode_page()
    {
        $user = User::factory()->create(['role' => 'user', 'is_active' => true]);

        $response = $this->actingAs($user)->get('/admin/qrcode');

        $response->assertStatus(403);
    }

    public function test_admin_can_store_qrcode()
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);

        $response = $this->actingAs($admin)->post('/admin/qrcode', [
            'judul' => 'Test QR',
            'link' => 'https://example.com'
        ]);

        $response->assertRedirect('/admin/qrcode');
        $this->assertDatabaseHas('qr_codes', [
            'judul' => 'Test QR',
            'link' => 'https://example.com'
        ]);
    }

    public function test_admin_can_delete_qrcode()
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        $qr = QrCode::create(['judul' => 'To Delete', 'link' => 'https://delete.com']);

        $response = $this->actingAs($admin)->delete("/admin/qrcode/{$qr->id}");

        $response->assertRedirect('/admin/qrcode');
        $this->assertDatabaseMissing('qr_codes', ['id' => $qr->id]);
    }

    public function test_user_can_view_scan_page()
    {
        $user = User::factory()->create(['role' => 'user', 'is_active' => true]);

        $response = $this->actingAs($user)->get('/qr-scan');

        $response->assertStatus(200);
        $response->assertViewIs('qr-scan.index');
    }
}
