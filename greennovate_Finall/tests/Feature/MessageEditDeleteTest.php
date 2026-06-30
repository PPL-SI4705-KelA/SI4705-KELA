<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MessageEditDeleteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // This makes sure driver is setup properly for tests
        // Since we are not using sqlite natively and avoiding DROP constraints,
        // we might not need special setup, but RefreshDatabase handles resetting the DB.
    }

    public function test_user_can_edit_own_message()
    {
        $user = User::factory()->create(['role' => 'user', 'is_active' => true]);
        $conversation = Conversation::create(['user_id' => $user->id, 'status' => 'open']);
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'body' => 'Pesan asli',
        ]);

        $response = $this->actingAs($user)->patchJson(route('message.update', $message->id), [
            'body' => 'Pesan diedit',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('messages', [
            'id' => $message->id,
            'body' => 'Pesan diedit',
            'is_edited' => 1,
        ]);
    }

    public function test_admin_can_edit_own_message()
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        $user = User::factory()->create(['role' => 'user', 'is_active' => true]);
        $conversation = Conversation::create(['user_id' => $user->id, 'status' => 'open']);
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $admin->id,
            'body' => 'Pesan admin asli',
        ]);

        $response = $this->actingAs($admin)->patchJson(route('message.update', $message->id), [
            'body' => 'Pesan admin diedit',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('messages', [
            'id' => $message->id,
            'body' => 'Pesan admin diedit',
            'is_edited' => 1,
        ]);
    }

    public function test_user_cannot_edit_other_persons_message()
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        $user = User::factory()->create(['role' => 'user', 'is_active' => true]);
        $conversation = Conversation::create(['user_id' => $user->id, 'status' => 'open']);

        $messageAdmin = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $admin->id,
            'body' => 'Pesan admin',
        ]);

        $response = $this->actingAs($user)->patchJson(route('message.update', $messageAdmin->id), [
            'body' => 'User mencoba ngedit pesan admin',
        ]);

        $response->assertStatus(403);
    }

    public function test_edit_validation_empty_body()
    {
        $user = User::factory()->create(['role' => 'user', 'is_active' => true]);
        $conversation = Conversation::create(['user_id' => $user->id, 'status' => 'open']);
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'body' => 'Pesan asli',
        ]);

        $response = $this->actingAs($user)->patchJson(route('message.update', $message->id), [
            'body' => '',
        ]);

        $response->assertStatus(422);
    }

    public function test_user_can_delete_own_message_and_attachment_is_removed()
    {
        Storage::fake('public');

        $user = User::factory()->create(['role' => 'user', 'is_active' => true]);
        $conversation = Conversation::create(['user_id' => $user->id, 'status' => 'open']);

        $file = UploadedFile::fake()->create('bukti.pdf', 100, 'application/pdf');
        $path = $file->store('chat_attachments', 'public');

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'body' => 'Pesan dengan lampiran',
            'attachment_path' => $path,
            'attachment_type' => 'document'
        ]);

        Storage::disk('public')->assertExists($path);

        $response = $this->actingAs($user)->deleteJson(route('message.destroy', $message->id));

        $response->assertStatus(200);

        // Soft delete check
        $this->assertSoftDeleted('messages', [
            'id' => $message->id,
        ]);

        // Attachment physical file should be deleted
        Storage::disk('public')->assertMissing($path);
    }

    public function test_user_cannot_delete_other_persons_message()
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        $user = User::factory()->create(['role' => 'user', 'is_active' => true]);
        $conversation = Conversation::create(['user_id' => $user->id, 'status' => 'open']);

        $messageAdmin = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $admin->id,
            'body' => 'Pesan admin',
        ]);

        $response = $this->actingAs($user)->deleteJson(route('message.destroy', $messageAdmin->id));

        $response->assertStatus(403);
        $this->assertDatabaseHas('messages', [
            'id' => $messageAdmin->id,
            'deleted_at' => null,
        ]);
    }

    public function test_edited_at_only_updated_on_first_edit()
    {
        $user = User::factory()->create(['role' => 'user', 'is_active' => true]);
        $conversation = Conversation::create(['user_id' => $user->id, 'status' => 'open']);
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'body' => 'Pesan asli',
        ]);

        // First edit
        $this->actingAs($user)->patchJson(route('message.update', $message->id), [
            'body' => 'Pesan diedit pertama',
        ]);

        $message->refresh();
        $this->assertTrue($message->is_edited);
        $firstEditTime = $message->edited_at;
        $this->assertNotNull($firstEditTime);

        // Sleep 1 second to ensure different timestamp
        sleep(1);

        // Second edit
        $this->actingAs($user)->patchJson(route('message.update', $message->id), [
            'body' => 'Pesan diedit kedua',
        ]);

        $message->refresh();
        $this->assertEquals('Pesan diedit kedua', $message->body);
        $this->assertEquals($firstEditTime->timestamp, $message->edited_at->timestamp);
    }

    public function test_attachment_path_not_changed_on_edit()
    {
        $user = User::factory()->create(['role' => 'user', 'is_active' => true]);
        $conversation = Conversation::create(['user_id' => $user->id, 'status' => 'open']);
        $path = 'chat_attachments/dummy.jpg';

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'body' => 'Pesan asli dengan lampiran',
            'attachment_path' => $path,
            'attachment_type' => 'image',
        ]);

        $this->actingAs($user)->patchJson(route('message.update', $message->id), [
            'body' => 'Pesan diedit',
        ]);

        $message->refresh();
        $this->assertEquals('Pesan diedit', $message->body);
        $this->assertEquals($path, $message->attachment_path);
        $this->assertEquals('image', $message->attachment_type);
    }

    public function test_get_messages_returns_is_edited_and_body()
    {
        $user = User::factory()->create(['role' => 'user', 'is_active' => true]);
        $conversation = Conversation::create(['user_id' => $user->id, 'status' => 'open']);
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'body' => 'Pesan asli',
        ]);

        $this->actingAs($user)->patchJson(route('message.update', $message->id), [
            'body' => 'Pesan telah diedit untuk polling',
        ]);

        $response = $this->actingAs($user)->getJson(route('chat.messages'));

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $message->id,
            'body' => 'Pesan telah diedit untuk polling',
            'is_edited' => true,
        ]);
    }
}
