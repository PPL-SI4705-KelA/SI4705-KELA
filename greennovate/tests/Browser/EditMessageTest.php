<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class EditMessageTest extends DuskTestCase
{
    private $userEmail = 'user@greennovate.test';

    private function loginAs(Browser $browser, $email)
    {
        $user = User::where('email', $email)->first();
        return $browser->loginAs($user);
    }

    public function testEditPesanBerhasil()
    {
        $user = User::where('email', $this->userEmail)->first();

        // Cleanup existing conversations for this user to prevent first() conflict
        Conversation::where('user_id', $user->id)->delete();

        $conversation = Conversation::create([
            'user_id' => $user->id,
            'subject' => 'Tanya Donasi',
        ]);

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'body' => 'Pesan sebelum diedit',
        ]);

        $this->browse(function (Browser $browser) use ($message) {
            $this->loginAs($browser, $this->userEmail)
                 ->visit('/chat')
                 ->waitForText('Pesan sebelum diedit', 15)
                 ->script("
                    window.prompt = function() { return 'Pesan yang sudah diedit via Dusk'; };
                    window.editMessage({$message->id}, 'Pesan sebelum diedit');
                 ");

            $browser->pause(2000)->screenshot('edit-test-debug');
            $browser->waitForText('Pesan yang sudah diedit via Dusk', 15)
                    ->assertSee('diedit')
                    ->pause(15000)
                    ->logout();
        });
    }

    public function testDeletePesanBerhasil()
    {
        $user = User::where('email', $this->userEmail)->first();

        // Cleanup existing conversations for this user to prevent first() conflict
        Conversation::where('user_id', $user->id)->delete();

        $conversation = Conversation::create([
            'user_id' => $user->id,
            'subject' => 'Tanya Donasi',
        ]);

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'body' => 'Pesan yang akan dihapus',
        ]);

        $this->browse(function (Browser $browser) use ($message) {
            $this->loginAs($browser, $this->userEmail)
                 ->visit('/chat')
                 ->waitForText('Pesan yang akan dihapus', 15)
                 ->script("
                    window.confirm = function() { return true; };
                    window.deleteMessage({$message->id});
                 ");

            $browser->waitUntilMissingText('Pesan yang akan dihapus', 15)
                    ->assertDontSee('Pesan yang akan dihapus')
                    ->pause(15000)
                    ->logout();
        });
    }
}
