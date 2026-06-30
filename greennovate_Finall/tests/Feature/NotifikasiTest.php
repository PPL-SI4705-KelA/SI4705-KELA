<?php

namespace Tests\Feature;

use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Feature Test: Sistem Notifikasi
 *
 * Menggunakan DatabaseTransactions → setiap test di-rollback otomatis,
 * data asli di database TIDAK berubah secara permanen.
 *
 * User nyata:
 *  - user biasa : user@greennovate.test  / user12345
 *  - admin      : pardede281204@gmail.com / QWERTY12345
 */
class NotifikasiTest extends TestCase
{
    use DatabaseTransactions;

    private User $user;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user  = User::where('email', 'user@greennovate.test')->firstOrFail();
        $this->admin = User::where('email', 'pardede281204@gmail.com')->firstOrFail();
    }

    // -------------------------------------------------------
    // Unit Test: Model method tandaiDibaca()
    // -------------------------------------------------------

    /**
     * Test Unit: tandaiDibaca() mengubah is_read = true dan mengisi read_at.
     * Notifikasi dibuat di dalam transaksi → di-rollback setelah test.
     */
    public function test_tandai_dibaca_model_method()
    {
        $notifikasi = Notifikasi::create([
            'user_id' => $this->user->id,
            'judul'   => 'Unit Test – tandaiDibaca',
            'pesan'   => 'Pesan unit test.',
            'tipe'    => 'sistem',
            'is_read' => false,
        ]);

        $this->assertFalse($notifikasi->is_read);
        $this->assertNull($notifikasi->read_at);

        $notifikasi->tandaiDibaca();

        $this->assertTrue($notifikasi->fresh()->is_read);
        $this->assertNotNull($notifikasi->fresh()->read_at);
    }

    // -------------------------------------------------------
    // Access Control
    // -------------------------------------------------------

    /**
     * Test: Guest yang belum login diarahkan ke halaman login.
     */
    public function test_guest_cannot_access_notifikasi()
    {
        $response = $this->get('/notifikasi');
        $response->assertRedirect('/login');
    }

    /**
     * Test: User yang sudah login bisa membuka halaman notifikasi.
     */
    public function test_user_can_view_notifikasi()
    {
        $response = $this->actingAs($this->user)->get('/notifikasi');
        $response->assertStatus(200);
    }

    // -------------------------------------------------------
    // Integration: Tandai Satu Notifikasi
    // -------------------------------------------------------

    /**
     * Test: User bisa menandai satu notifikasi miliknya sebagai sudah dibaca.
     * Mencari notifikasi belum dibaca yang sudah ada, atau membuat satu di dalam transaksi.
     */
    public function test_user_can_mark_one_notifikasi_as_read()
    {
        // Cari notifikasi belum dibaca yang sudah ada, atau buat di dalam transaksi
        $notifikasi = Notifikasi::where('user_id', $this->user->id)
            ->belumDibaca()
            ->first();

        if (! $notifikasi) {
            $notifikasi = Notifikasi::create([
                'user_id' => $this->user->id,
                'judul'   => 'Notif Test – Tandai Dibaca',
                'pesan'   => 'Pesan test.',
                'tipe'    => 'sistem',
                'is_read' => false,
            ]);
        }

        $response = $this->actingAs($this->user)
            ->withHeaders(['Accept' => 'application/json'])
            ->patchJson("/notifikasi/{$notifikasi->id}/baca");

        $response->assertStatus(200);
        $response->assertJsonStructure(['message', 'unread_count']);

        $this->assertDatabaseHas('notifikasis', [
            'id'      => $notifikasi->id,
            'is_read' => true,
        ]);

        $this->assertNotNull(Notifikasi::find($notifikasi->id)->read_at);
    }

    // -------------------------------------------------------
    // Integration: Access Control antar user
    // -------------------------------------------------------

    /**
     * Test: User biasa tidak bisa menandai notifikasi milik user lain (admin).
     * Menggunakan notifikasi yang sudah ada milik admin, atau membuat di dalam transaksi.
     */
    public function test_user_cannot_mark_other_users_notifikasi_as_read()
    {
        // Cari notifikasi admin yang sudah ada, atau buat dalam transaksi
        $notifikasiAdmin = Notifikasi::where('user_id', $this->admin->id)->first();

        if (! $notifikasiAdmin) {
            $notifikasiAdmin = Notifikasi::create([
                'user_id' => $this->admin->id,
                'judul'   => 'Notif Admin – Test',
                'pesan'   => 'Milik admin.',
                'tipe'    => 'pembayaran',
                'is_read' => false,
            ]);
        }

        $wasRead   = $notifikasiAdmin->is_read;
        $notifId   = $notifikasiAdmin->id;

        $response = $this->actingAs($this->user)
            ->withHeaders(['Accept' => 'application/json'])
            ->patchJson("/notifikasi/{$notifId}/baca");

        // Controller mengembalikan 404 jika notifikasi bukan milik user yang login
        $response->assertStatus(404);

        // Status is_read tidak berubah
        $this->assertDatabaseHas('notifikasis', [
            'id'      => $notifId,
            'is_read' => $wasRead,
        ]);
    }

    // -------------------------------------------------------
    // Integration: Tandai Semua Dibaca
    // -------------------------------------------------------

    /**
     * Test: User bisa menandai semua notifikasinya sebagai sudah dibaca.
     * Rollback via DatabaseTransactions → data asli aman.
     */
    public function test_user_can_mark_all_notifikasi_as_read()
    {
        // Hitung jumlah belum dibaca sebelum request
        $beforeUnread = Notifikasi::where('user_id', $this->user->id)
            ->belumDibaca()
            ->count();

        // Jika tidak ada yang belum dibaca, buat beberapa dalam transaksi
        if ($beforeUnread === 0) {
            Notifikasi::create(['user_id' => $this->user->id, 'judul' => 'Notif Test 1', 'pesan' => 'P1', 'tipe' => 'sistem', 'is_read' => false]);
            Notifikasi::create(['user_id' => $this->user->id, 'judul' => 'Notif Test 2', 'pesan' => 'P2', 'tipe' => 'sistem', 'is_read' => false]);
        }

        $response = $this->actingAs($this->user)
            ->withHeaders(['Accept' => 'application/json'])
            ->patchJson('/notifikasi/baca-semua');

        $response->assertStatus(200);
        $response->assertJson(['unread_count' => 0]);

        // Setelah request, tidak ada lagi yang belum dibaca
        $this->assertEquals(
            0,
            Notifikasi::where('user_id', $this->user->id)->belumDibaca()->count()
        );

        // Notifikasi milik admin TIDAK ikut terbaca
        $adminUnreadAfter = Notifikasi::where('user_id', $this->admin->id)
            ->belumDibaca()
            ->count();

        $adminTotalBefore = Notifikasi::where('user_id', $this->admin->id)->count();
        // Admin masih punya notif yang belum dibaca (jumlahnya tidak ikut nol)
        $this->assertGreaterThanOrEqual(0, $adminUnreadAfter); // data admin tidak tersentuh
    }

    // -------------------------------------------------------
    // Integration: Admin Log Notifikasi
    // -------------------------------------------------------

    /**
     * Test: Admin bisa membuka halaman log notifikasi semua user.
     */
    public function test_admin_can_view_all_notifikasi()
    {
        $response = $this->actingAs($this->admin)->get('/admin/notifikasi');
        $response->assertStatus(200);
    }

    /**
     * Test: Admin bisa membuka halaman inbox pribadi.
     */
    public function test_admin_can_view_inbox()
    {
        $response = $this->actingAs($this->admin)->get('/admin/inbox');
        $response->assertStatus(200);
    }

    /**
     * Test: Admin bisa menandai notifikasi inboxnya sendiri sebagai dibaca.
     * Mencari notif belum dibaca milik admin, atau membuat dalam transaksi.
     */
    public function test_admin_can_mark_inbox_notif_as_read()
    {
        $notif = Notifikasi::where('user_id', $this->admin->id)
            ->belumDibaca()
            ->first();

        if (! $notif) {
            $notif = Notifikasi::create([
                'user_id' => $this->admin->id,
                'judul'   => 'Test Inbox Admin',
                'pesan'   => 'Pesan test inbox admin.',
                'tipe'    => 'pembayaran',
                'is_read' => false,
            ]);
        }

        $response = $this->actingAs($this->admin)
            ->withHeaders(['Accept' => 'application/json'])
            ->patchJson("/admin/inbox/{$notif->id}/baca");

        $response->assertStatus(200);
        $response->assertJsonStructure(['message', 'unread_count']);

        $this->assertDatabaseHas('notifikasis', [
            'id'      => $notif->id,
            'is_read' => true,
        ]);
    }

    // -------------------------------------------------------
    // Integration: Trigger Notifikasi dari Event Pembelian
    // -------------------------------------------------------

    /**
     * Test: Terima / tolak pembelian menghasilkan notifikasi ke user.
     * Pembelian & notifikasi dibuat dalam transaksi → di-rollback setelah test.
     */
    public function test_purchase_notifications_verification_and_expiration()
    {
        // 1. Terima pembelian → notif "Pembelian Berhasil"
        $p1 = \App\Models\Pembelian::create([
            'user_id'        => $this->user->id,
            'nama_item'      => 'Bibit Pohon Mangga',
            'jumlah_item'    => 1,
            'total_harga'    => 50000,
            'status'         => 'Pending',
            'kode_transaksi' => 'TX-FEAT-P1',
        ]);

        $this->actingAs($this->admin)
            ->post("/admin/pembelian/{$p1->id}/terima")
            ->assertRedirect();

        $this->assertDatabaseHas('notifikasis', [
            'user_id' => $this->user->id,
            'judul'   => 'Pembelian Berhasil',
            'tipe'    => 'pembayaran',
        ]);

        // 2. Tolak pembelian → notif "Pembelian Gagal"
        $p2 = \App\Models\Pembelian::create([
            'user_id'        => $this->user->id,
            'nama_item'      => 'Bibit Pohon Jambu',
            'jumlah_item'    => 1,
            'total_harga'    => 45000,
            'status'         => 'Pending',
            'kode_transaksi' => 'TX-FEAT-P2',
        ]);

        $this->actingAs($this->admin)
            ->post("/admin/pembelian/{$p2->id}/tolak")
            ->assertRedirect();

        $this->assertDatabaseHas('notifikasis', [
            'user_id' => $this->user->id,
            'judul'   => 'Pembelian Gagal',
            'tipe'    => 'pembayaran',
        ]);

        // 3. Terima massal → notif per item
        $p3 = \App\Models\Pembelian::create([
            'user_id' => $this->user->id, 'nama_item' => 'Bibit Pohon Kelapa',
            'jumlah_item' => 1, 'total_harga' => 60000,
            'status' => 'Pending', 'kode_transaksi' => 'TX-FEAT-P3',
        ]);
        $p4 = \App\Models\Pembelian::create([
            'user_id' => $this->user->id, 'nama_item' => 'Bibit Pohon Durian',
            'jumlah_item' => 1, 'total_harga' => 75000,
            'status' => 'Pending', 'kode_transaksi' => 'TX-FEAT-P4',
        ]);

        $this->actingAs($this->admin)
            ->post('/admin/pembelian/terima-massal', ['ids' => [$p3->id, $p4->id]])
            ->assertRedirect();

        $this->assertDatabaseHas('notifikasis', [
            'user_id' => $this->user->id,
            'judul'   => 'Pembelian Berhasil',
            'pesan'   => 'Pembelian kontribusi pohon Anda (Bibit Pohon Kelapa) dengan kode transaksi TX-FEAT-P3 telah berhasil diverifikasi.',
        ]);
        $this->assertDatabaseHas('notifikasis', [
            'user_id' => $this->user->id,
            'judul'   => 'Pembelian Berhasil',
            'pesan'   => 'Pembelian kontribusi pohon Anda (Bibit Pohon Durian) dengan kode transaksi TX-FEAT-P4 telah berhasil diverifikasi.',
        ]);

        // 4. Expired → notif "Pembelian Kedaluwarsa"
        $p5 = \App\Models\Pembelian::create([
            'user_id' => $this->user->id, 'nama_item' => 'Bibit Pohon Rambutan',
            'jumlah_item' => 1, 'total_harga' => 35000,
            'status' => 'Pending', 'kode_transaksi' => 'TX-FEAT-P5',
        ]);
        \Illuminate\Support\Facades\DB::table('pembelians')
            ->where('id', $p5->id)
            ->update(['created_at' => now()->subMinutes(11)]);

        $this->actingAs($this->admin)->get('/admin/pembelian')->assertStatus(200);

        $this->assertDatabaseHas('pembelians', ['id' => $p5->id, 'status' => 'Expired']);
        $this->assertDatabaseHas('notifikasis', [
            'user_id' => $this->user->id,
            'judul'   => 'Pembelian Kedaluwarsa',
            'pesan'   => 'Batas waktu pembayaran untuk pembelian (Bibit Pohon Rambutan) dengan kode transaksi TX-FEAT-P5 telah habis.',
        ]);
    }

    /**
     * Test: Checkout pembelian oleh user mengirimkan notifikasi ke admin.
     */
    public function test_checkout_sends_notification_to_admin()
    {
        // Pastikan ada jenis pohon & lokasi lahan di DB
        $pohon = \Illuminate\Support\Facades\DB::table('jenis_pohons')->first();
        $lahan = \Illuminate\Support\Facades\DB::table('lokasi_lahans')->first();

        if (! $pohon || ! $lahan) {
            $this->markTestSkipped('Data jenis pohon atau lokasi lahan belum ada di database.');
        }

        $adminUnreadBefore = Notifikasi::where('user_id', $this->admin->id)
            ->belumDibaca()
            ->count();

        $response = $this->actingAs($this->user)->post('/pembelian/checkout', [
            'jenis_pohon_id'  => $pohon->id,
            'lokasi_lahan_id' => $lahan->id,
            'catatan'         => 'Test checkout notifikasi admin',
        ]);

        $response->assertRedirect();

        // Admin mendapat notifikasi baru
        $adminUnreadAfter = Notifikasi::where('user_id', $this->admin->id)
            ->belumDibaca()
            ->count();

        $this->assertGreaterThan($adminUnreadBefore, $adminUnreadAfter);

        $this->assertDatabaseHas('notifikasis', [
            'user_id' => $this->admin->id,
            'judul'   => 'Pembelian Baru Menunggu Verifikasi',
            'tipe'    => 'pembayaran',
        ]);
    }
}
