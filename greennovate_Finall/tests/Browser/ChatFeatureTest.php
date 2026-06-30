<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;

class ChatFeatureTest extends DuskTestCase
{
    private $userEmail = 'user@greennovate.test';
    private $adminEmail = 'pardede281204@gmail.com';
    private $petugasEmail = 'petugas@greennovate.test';

    protected function setUp(): void
    {
        parent::setUp();

        // Clean up code removed to preserve chat history during testing
        // $user = User::where([['email', '=', $this->userEmail]])->first();
        // if ($user) {
        //     $conversations = Conversation::where('user_id', $user->id)->get();
        //     foreach ($conversations as $conv) {
        //         Message::where('conversation_id', $conv->id)->delete();
        //         $conv->delete();
        //     }
        // }
    }

    private function loginAs(Browser $browser, $email)
    {
        $user = User::where([['email', '=', $email]])->first();
        return $browser->loginAs($user);
    }

    /**
     * TC-01: Membuka halaman chat sebagai user
     */
    public function testMembukaHalamanChat()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAs($browser, $this->userEmail)
                ->visit('/chat')
                ->waitForText('Hubungi Admin', 10)
                ->assertSee('Hubungi Admin')
                ->assertPresent('#chat-input')
                ->assertPresent('#chat-submit')
                ->pause(15000)
                ->logout();
        });
    }

    /**
     * TC-03: Kirim pesan kosong
     */
    public function testKirimPesanKosong()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAs($browser, $this->userEmail)
                ->visit('/chat')
                ->waitForText('Hubungi Admin', 10)
                // Bypass frontend required attribute if present
                ->script("document.getElementById('chat-input').removeAttribute('required');");

            $browser->clear('#chat-input')
                ->pause(500)
                ->click('#chat-submit')
                ->waitForText('Pesan tidak boleh kosong', 10)
                ->assertSee('Pesan tidak boleh kosong')
                ->pause(15000)
                ->logout();
        });
    }

    /**
     * TC-04: Kirim pesan melebihi batas karakter
     */
    public function testKirimPesanMelebihiBatasKarakter()
    {
        $this->browse(function (Browser $browser) {
            // Generate a string with more than 1000 characters
            $longMessage = str_repeat('A', 1005);

            $this->loginAs($browser, $this->userEmail)
                ->visit('/chat')
                ->waitForText('Hubungi Admin', 10)
                ->script("document.getElementById('chat-input').removeAttribute('maxlength');");

            $browser->type('#chat-input', $longMessage)
                ->click('#chat-submit')
                ->waitForText('Pesan maksimal 1000 karakter', 10)
                ->assertSee('Pesan maksimal 1000 karakter')
                ->pause(15000)
                ->logout();
        });
    }

    /**
     * TC-06: Admin membalas pesan user
     */
    public function testAdminMembalasPesanUser()
    {
        $user = User::where([['email', '=', $this->userEmail]])->first();
        $conv = Conversation::firstOrCreate(['user_id' => $user->id], ['status' => 'open']);
        Message::create(['conversation_id' => $conv->id, 'sender_id' => $user->id, 'body' => 'Pesan untuk Admin']);

        $this->browse(function (Browser $browser) use ($conv) {
            $this->loginAs($browser, $this->adminEmail)
                ->visit('/admin/chat-debug/' . $conv->id)
                ->screenshot('debug-conv-lookup')
                ->pause(2000)
                ->visit('/admin/chat')
                ->waitForText('Pesan Masuk', 15)
                ->screenshot('debug-admin-chat-index')
                ->clickLink($conv->user->name ?? 'User Biasa')
                ->screenshot('debug-admin-chat-show')
                ->waitForText('Percakapan', 20)
                ->type('#chat-input', 'Terima kasih, kami akan segera proses')
                ->pause(1000)
                ->click('#chat-submit')
                ->waitForText('Terima kasih, kami akan segera proses', 15)
                ->assertSee('Terima kasih, kami akan segera proses')
                ->pause(15000)
                ->logout();
        });

        // Verifikasi database
        $admin = User::where([['email', '=', $this->adminEmail]])->first();
        $this->assertTrue(Message::where('sender_id', $admin->id)
            ->where('body', 'Terima kasih, kami akan segera proses')
            ->exists());
    }

    /**
     * TC-08: Riwayat percakapan tetap tersimpan
     */
    public function testRiwayatPercakapanTetapTersimpan()
    {
        // Pastikan ada riwayat
        $user = User::where([['email', '=', $this->userEmail]])->first();
        $conv = Conversation::firstOrCreate(['user_id' => $user->id], ['status' => 'open']);
        Message::create(['conversation_id' => $conv->id, 'sender_id' => $user->id, 'body' => 'Halo, saya ingin tanya soal donasi saya']);

        $this->browse(function (Browser $browser) {
            $this->loginAs($browser, $this->userEmail)
                ->visit('/chat')
                ->waitForText('Halo, saya ingin tanya soal donasi saya', 10)
                ->assertSee('Halo, saya ingin tanya soal donasi saya')
                ->pause(15000)
                ->logout();
        });
    }

    /**
     * TC-09: User coba akses percakapan milik user lain
     */
    public function testUserAksesPercakapanUserLain()
    {
        // Buat user dummy lain dan percakapannya
        $otherUser = User::factory()->create(['role' => 'user']);
        $otherConv = Conversation::create(['user_id' => $otherUser->id, 'status' => 'open']);

        $this->browse(function (Browser $browser) use ($otherConv) {
            $this->loginAs($browser, $this->userEmail)
                // Endpoint AJAX request untuk get messages (returns JSON)
                ->visit('/chat/messages?conversation_id=' . $otherConv->id)
                ->pause(3000)
                ->screenshot('debug-access-denied')
                ->assertSee('Anda tidak memiliki akses ke percakapan ini')
                ->pause(15000)
                ->logout();
        });

        $otherConv->delete();
        $otherUser->delete();
    }

    /**
     * TC-10: Akses halaman chat tanpa login
     */
    public function testAksesHalamanChatTanpaLogin()
    {
        $this->browse(function (Browser $browser) {
            $browser->logout() // Ensure not logged in
                ->visit('/chat')
                ->assertPathIs('/login')
                ->pause(15000);
        });
    }

    /**
     * TC-11: User dengan role petugas tidak bisa akses /chat sebagai user biasa
     */
    public function testPetugasAksesChatUser()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAs($browser, $this->petugasEmail)
                ->visit('/chat')
                // Should redirect to petugas dashboard
                ->assertPathIs('/petugas/dashboard')
                ->pause(15000)
                ->logout();
        });
    }

    /**
     * TC-12: Admin tidak bisa akses /chat milik user
     */
    public function testAdminAksesChatUser()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAs($browser, $this->adminEmail)
                ->visit('/chat')
                // Should redirect to admin dashboard
                ->assertPathIs('/admin/dashboard')
                ->pause(15000)
                ->logout();
        });
    }
}
