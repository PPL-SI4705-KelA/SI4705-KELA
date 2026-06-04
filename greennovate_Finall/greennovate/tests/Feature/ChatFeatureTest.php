<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatFeatureTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
    {
        return User::factory()->create([
            'email' => 'admin@greennovate.test', // Custom admin email
            'role' => User::ROLE_ADMIN ?? 'admin',
            'is_active' => true,
        ]);
    }

    private function createUser(): User
    {
        return User::factory()->create([
            'email' => 'user@greennovate.test', // Custom user email
            'role' => User::ROLE_USER ?? 'user',
            'is_active' => true,
        ]);
    }

    public function test_user_can_access_chat_page()
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)->get('/chat');

        $response->assertStatus(200);
        $response->assertViewIs('chat.index');
    }

    public function test_user_can_send_message()
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)->post('/chat/send', [
            'body' => 'Halo Admin, saya butuh bantuan.',
        ]);

        $response->assertStatus(302); // Redirect back

        $this->assertDatabaseHas('messages', [
            'sender_id' => $user->id,
            'body' => 'Halo Admin, saya butuh bantuan.',
        ]);

        $this->assertDatabaseHas('conversations', [
            'user_id' => $user->id,
            'status' => 'open',
        ]);
    }

    public function test_admin_can_access_admin_chat_list()
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get('/admin/chat');

        $response->assertStatus(200);
        // Assuming there is an index view for admin chats
    }

    public function test_admin_can_reply_to_user_message()
    {
        $admin = $this->createAdmin();
        $user = $this->createUser();

        // Create initial conversation and message from user
        $conversation = Conversation::create([
            'user_id' => $user->id,
            'status' => 'open'
        ]);

        $conversation->messages()->create([
            'sender_id' => $user->id,
            'body' => 'Tolong bantu saya.',
        ]);

        // Admin replies
        $response = $this->actingAs($admin)->post("/admin/chat/{$conversation->id}/reply", [
            'body' => 'Baik, ada yang bisa dibantu?',
        ]);

        $response->assertStatus(302); // Redirect back

        $this->assertDatabaseHas('messages', [
            'sender_id' => $admin->id,
            'conversation_id' => $conversation->id,
            'body' => 'Baik, ada yang bisa dibantu?',
        ]);
    }
}
