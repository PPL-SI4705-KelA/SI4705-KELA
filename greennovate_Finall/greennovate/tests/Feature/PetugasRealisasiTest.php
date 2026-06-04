<?php

namespace Tests\Feature;

use App\Models\JenisPohon;
use App\Models\Kegiatan;
use App\Models\LokasiLahan;
use App\Models\Pembelian;
use App\Models\Realisasi;
use App\Models\User;
use App\Models\PendaftaranKegiatan;
use App\Http\Controllers\Petugas\PetugasDashboardController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PetugasRealisasiTest extends TestCase
{
    use RefreshDatabase;

    private User $petugas;
    private User $user;
    private User $admin;
    private LokasiLahan $lokasi;
    private Kegiatan $kegiatan;
    private JenisPohon $jenisPohon;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Setup roles
        $this->petugas = User::create([
            'name'      => 'Test Petugas',
            'email'     => 'petugas@example.com',
            'password'  => bcrypt('password'),
            'role'      => 'petugas',
            'is_active' => true,
        ]);

        $this->user = User::create([
            'name'      => 'Test User',
            'email'     => 'user@example.com',
            'password'  => bcrypt('password'),
            'role'      => 'user',
            'is_active' => true,
        ]);

        $this->admin = User::create([
            'name'      => 'Test Admin',
            'email'     => 'admin@example.com',
            'password'  => bcrypt('password'),
            'role'      => 'admin',
            'is_active' => true,
        ]);

        // 2. Setup master data
        $kategori = \App\Models\KategoriPohon::create([
            'nama' => 'Pohon Besar',
            'deskripsi' => 'Deskripsi',
        ]);

        $this->lokasi = LokasiLahan::create([
            'nama'      => 'Lahan Hutan Lindung',
            'alamat'    => 'Samboja, Kaltim',
            'deskripsi' => 'Konservasi',
        ]);

        $this->kegiatan = Kegiatan::create([
            'nama'            => 'Aksi Tanam Jati Raya',
            'lokasi_lahan_id' => $this->lokasi->id,
            'petugas_id'      => $this->petugas->id,
            'tanggal'         => '2026-06-10',
            'target_pohon'    => 100,
            'realisasi_pohon' => 10,
            'status'          => 'Berlangsung',
        ]);

        $this->jenisPohon = JenisPohon::create([
            'nama'       => 'Jati',
            'nama_latin' => 'Tectona grandis',
            'kategori_pohon_id' => $kategori->id,
            'harga'      => 50000,
            'status'     => 'active',
        ]);
    }

    // ── Access Control Tests ───────────────────────────────────────────────────

    /** @test */
    public function test_petugas_can_access_realisasi_page(): void
    {
        $response = $this->actingAs($this->petugas)->get('/petugas/realisasi');
        $response->assertStatus(200);
        $response->assertSee('Catat Realisasi Penanaman');
    }

    /** @test */
    public function test_user_role_cannot_access_petugas_routes(): void
    {
        $response = $this->actingAs($this->user)->get('/petugas/realisasi');
        $response->assertStatus(403);
    }

    /** @test */
    public function test_guest_is_redirected_to_login_when_accessing_petugas(): void
    {
        $response = $this->get('/petugas/realisasi');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function test_petugas_cannot_realize_kegiatan_assigned_to_others(): void
    {
        $otherPetugas = User::create([
            'name'      => 'Other Petugas',
            'email'     => 'other@example.com',
            'password'  => bcrypt('password'),
            'role'      => 'petugas',
            'is_active' => true,
        ]);

        $otherKegiatan = Kegiatan::create([
            'nama'            => 'Kegiatan Lain',
            'lokasi_lahan_id' => $this->lokasi->id,
            'petugas_id'      => $otherPetugas->id, // Assigned to other petugas
            'tanggal'         => '2026-06-12',
            'target_pohon'    => 50,
            'realisasi_pohon' => 0,
            'status'          => 'Berlangsung',
        ]);

        // Create successful transaction to bypass transaction check
        Pembelian::create([
            'user_id'        => $this->user->id,
            'nama_item'      => 'Bibit Pohon Jati',
            'jumlah_item'    => 10,
            'total_harga'    => 500000,
            'status'         => 'Sukses',
            'kode_transaksi' => 'TX-SUCCESS-JATI',
        ]);

        $response = $this->actingAs($this->petugas)->post('/petugas/realisasi', [
            'kegiatan_id'     => $otherKegiatan->id,
            'jenis_pohon_id'  => $this->jenisPohon->id,
            'jumlah_tertanam' => 5,
        ]);

        $response->assertStatus(403);
    }

    // ── Input Validations (Unit & Integration) ───────────────────────────────

    /** @test */
    public function test_validation_rules_for_jumlah_tertanam(): void
    {
        Pembelian::create([
            'user_id'        => $this->user->id,
            'nama_item'      => 'Bibit Pohon Jati',
            'jumlah_item'    => 10,
            'total_harga'    => 500000,
            'status'         => 'Sukses',
            'kode_transaksi' => 'TX-SUCCESS-JATI',
        ]);

        // 1. Wajib diisi
        $response1 = $this->actingAs($this->petugas)->post('/petugas/realisasi', [
            'kegiatan_id'     => $this->kegiatan->id,
            'jenis_pohon_id'  => $this->jenisPohon->id,
            'jumlah_tertanam' => '',
        ]);
        $response1->assertSessionHasErrors(['jumlah_tertanam' => 'Jumlah tertanam wajib diisi']);

        // 2. Tidak boleh negatif
        $response2 = $this->actingAs($this->petugas)->post('/petugas/realisasi', [
            'kegiatan_id'     => $this->kegiatan->id,
            'jenis_pohon_id'  => $this->jenisPohon->id,
            'jumlah_tertanam' => -5,
        ]);
        $response2->assertSessionHasErrors(['jumlah_tertanam' => 'Jumlah tidak boleh bernilai negatif']);

        // 3. Harus integer
        $response3 = $this->actingAs($this->petugas)->post('/petugas/realisasi', [
            'kegiatan_id'     => $this->kegiatan->id,
            'jenis_pohon_id'  => $this->jenisPohon->id,
            'jumlah_tertanam' => 'dua puluh',
        ]);
        $response3->assertSessionHasErrors(['jumlah_tertanam' => 'Masukkan angka bilangan bulat yang valid']);
    }

    // ── Logika Peringatan (Warning Logics) ───────────────────────────────────

    /** @test */
    public function test_warning_flag_triggers_when_exceeding_target(): void
    {
        $controller = new PetugasDashboardController();

        // Target kegiatan adalah 100 pohon
        $this->assertTrue($controller->triggersWarning(120, $this->kegiatan));
        $this->assertFalse($controller->triggersWarning(50, $this->kegiatan));
    }

    // ── Transaction Status Checks ────────────────────────────────────────────

    /** @test */
    public function test_blocks_realisasi_if_associated_transaction_is_not_sukses(): void
    {
        // 1. Transaction is Pending (not sukses)
        Pembelian::create([
            'user_id'        => $this->user->id,
            'nama_item'      => 'Bibit Pohon Jati',
            'jumlah_item'    => 10,
            'total_harga'    => 500000,
            'status'         => 'Pending',
            'kode_transaksi' => 'TX-PENDING-JATI',
        ]);

        $response = $this->actingAs($this->petugas)->post('/petugas/realisasi', [
            'kegiatan_id'     => $this->kegiatan->id,
            'jenis_pohon_id'  => $this->jenisPohon->id,
            'jumlah_tertanam' => 5,
        ]);

        $response->assertSessionHasErrors(['jumlah_tertanam' => 'Realisasi tidak dapat diinput, transaksi belum diverifikasi']);
    }

    /** @test */
    public function test_allows_realisasi_if_associated_transaction_is_sukses(): void
    {
        // Transaction is Sukses
        Pembelian::create([
            'user_id'        => $this->user->id,
            'nama_item'      => 'Bibit Pohon Jati',
            'jumlah_item'    => 10,
            'total_harga'    => 500000,
            'status'         => 'Sukses',
            'kode_transaksi' => 'TX-SUKSES-JATI',
        ]);

        $response = $this->actingAs($this->petugas)->post('/petugas/realisasi', [
            'kegiatan_id'     => $this->kegiatan->id,
            'jenis_pohon_id'  => $this->jenisPohon->id,
            'jumlah_tertanam' => 20,
            'catatan'         => 'Penanaman bibit jati berkualitas',
        ]);

        $response->assertRedirect(route('petugas.dashboard'));
        $response->assertSessionHas('success', 'Realisasi penanaman berhasil disimpan');

        // Check db record is stored correctly
        $this->assertDatabaseHas('realisasis', [
            'kegiatan_id'    => $this->kegiatan->id,
            'petugas_id'     => $this->petugas->id,
            'jenis_pohon_id' => $this->jenisPohon->id,
            'jumlah'         => 20,
            'catatan'        => 'Penanaman bibit jati berkualitas',
        ]);

        // Check kegiatan progress updated: kegiatan.realisasi_pohon (10 + 20 = 30)
        $this->assertEquals(30, $this->kegiatan->fresh()->realisasi_pohon);
    }

    // ── E2E Detail Riwayat update ────────────────────────────────────────────

    /** @test */
    public function test_user_can_see_updated_tree_count_in_riwayat_details(): void
    {
        // 1. Create a successful purchase transaction
        $pembelian = Pembelian::create([
            'user_id'        => $this->user->id,
            'nama_item'      => 'Bibit Pohon Jati',
            'jumlah_item'    => 10,
            'total_harga'    => 500000,
            'status'         => 'Sukses',
            'kode_transaksi' => 'TX-SUKSES-JATI-E2E',
        ]);

        // 2. Check initial detail (planted trees should be 0)
        $responseInit = $this->actingAs($this->user)->getJson(route('riwayat.detail', [
            'tipe' => 'pembelian',
            'id'   => $pembelian->id
        ]));
        $responseInit->assertStatus(200);
        $responseInit->assertJsonFragment(['pohon_tertanam' => 0]);

        // 3. Save realization of Jati tree (e.g. 5 trees)
        $this->actingAs($this->petugas)->post('/petugas/realisasi', [
            'kegiatan_id'     => $this->kegiatan->id,
            'jenis_pohon_id'  => $this->jenisPohon->id,
            'jumlah_tertanam' => 5,
        ]);

        // 4. Check riwayat detail again (planted trees should be updated to 5)
        $responseAfter = $this->actingAs($this->user)->getJson(route('riwayat.detail', [
            'tipe' => 'pembelian',
            'id'   => $pembelian->id
        ]));
        $responseAfter->assertStatus(200);
        $responseAfter->assertJsonFragment(['pohon_tertanam' => 5]);
    }
}
