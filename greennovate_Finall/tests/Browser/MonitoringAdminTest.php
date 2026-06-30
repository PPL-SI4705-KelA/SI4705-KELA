<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Kegiatan;
use App\Models\Donasi;
use App\Models\PendaftaranKegiatan;
use Carbon\Carbon;
use Illuminate\Support\Str;

class MonitoringAdminTest extends DuskTestCase
{
    private $adminEmail = 'pardede281204@gmail.com';
    private $adminPassword = 'QWERTY12345';
    private $userEmail = 'user@greennovate.test';
    private $userPassword = 'user12345';
    private $petugasEmail = 'petugas@greennovate.test';
    private $petugasPassword = 'petugas123';

    private function loginAsAdmin(Browser $browser)
    {
        $browser->logout()
                ->visit('/login')
                ->type('login', $this->adminEmail)
                ->type('password', $this->adminPassword)
                ->press('Masuk')
                ->pause(1000);
        return $browser;
    }

    private function prepareDummyData()
    {
        $admin = User::where('email', $this->adminEmail)->first();
        if (!$admin) return null;

        // Buat Kegiatan kosong (Tanpa Peserta)
        $kegiatanKosong = Kegiatan::updateOrCreate(
            ['slug' => 'kegiatan-dummy-kosong'],
            [
                'nama' => 'Kegiatan Dummy Kosong',
                'deskripsi' => 'Deskripsi dummy',
                'lokasi_lahan_id' => \App\Models\LokasiLahan::first()->id ?? 1,
                'petugas_id' => User::where('role', 'petugas')->first()->id ?? 1,
                'target_pohon' => 10,
                'status' => 'Berlangsung',
                'tanggal' => Carbon::now()->addDays(5),
            ]
        );

        // Hapus peserta jika kebetulan ada
        PendaftaranKegiatan::where('kegiatan_id', $kegiatanKosong->id)->delete();

        // Hapus donasi dummy lama jika ada agar created_at selalu fresh
        Donasi::where('kode_transaksi', 'like', 'TEST-MONITORING-%')->delete();

        // Buat 3 Donasi dummy dengan status berbeda
        $statuses = ['pending', 'sukses', 'expired'];
        foreach ($statuses as $status) {
            Donasi::create([
                'kode_transaksi' => 'TEST-MONITORING-' . strtoupper($status),
                'user_id' => $admin->id,
                'kegiatan_id' => $kegiatanKosong->id,
                'nama_donasi' => 'Donasi Dummy ' . $status,
                'nama_donatur' => 'Admin Test',
                'nomor_hp' => '08123456789',
                'jumlah' => 100000,
                'status' => $status,
                'created_at' => now(),
            ]);
        }

        return $kegiatanKosong;
    }

    /**
     * TC-01 & TC-02: Admin membuka menu Peserta Kegiatan (termasuk cek empty state)
     */
    public function testAdminAksesDaftarPeserta()
    {
        $kegiatanKosong = $this->prepareDummyData();
        if (!$kegiatanKosong) $this->markTestSkipped('Data setup failed');

        $this->browse(function (Browser $browser) use ($kegiatanKosong) {
            $this->loginAsAdmin($browser)
                 ->visit('/admin/peserta')
                 ->assertSee('Peserta Kegiatan')
                 ->assertSee('Filter & Saring Peserta')
                 
                 // Kunjungi kegiatan kosong (TC-02)
                 ->visit('/admin/kegiatan/' . $kegiatanKosong->id . '/peserta')
                 ->assertSee('Belum ada peserta terdaftar')
                 ->logout();
        });
    }

    /**
     * TC-03: Admin membuka menu Daftar Donasi
     * TC-08, TC-09, TC-10: Cek representasi UI untuk status pending, success, expired
     */
    public function testAdminAksesDaftarDonasiDanStatus()
    {
        $this->prepareDummyData();

        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser)
                 ->visit('/admin/donasi')
                 ->assertSee('Daftar Transaksi Donasi')
                 
                 // Cek representasi badge status (test data dummy)
                 ->assertSee('Menunggu')
                 ->assertSee('Berhasil')
                 ->assertSee('Kadaluarsa')
                 ->logout();
        });
    }

    /**
     * TC-04: Admin membuka menu Daftar Pengguna
     */
    public function testAdminAksesDaftarPengguna()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser)
                 ->visit('/admin/pengguna')
                 // Memastikan minimal nama-nama role muncul di tabel
                 ->assertSee('admin')
                 ->assertSee('user')
                 ->logout();
        });
    }

    /**
     * TC-05: Admin menekan tombol Export CSV (pastikan URL buttonnya benar)
     */
    public function testAdminMelihatTombolExportDonasi()
    {
        // Ensure download directory exists and is clean
        $downloadPath = storage_path('dusk-downloads');
        if (\Illuminate\Support\Facades\File::exists($downloadPath)) {
            \Illuminate\Support\Facades\File::cleanDirectory($downloadPath);
        } else {
            \Illuminate\Support\Facades\File::makeDirectory($downloadPath, 0755, true);
        }

        $this->browse(function (Browser $browser) use ($downloadPath) {
            $this->loginAsAdmin($browser)
                 ->visit('/admin/donasi')
                 ->assertSee('Export ke CSV')
                 // Memastikan link tujuan sesuai
                 ->assertSourceHas('reports/donasi.csv')
                 // Klik tombol download
                 ->clickLink('Export ke CSV')
                 ->pause(3000); // Tunggu proses download selesai
            
            // Cek apakah file CSV berhasil di-download
            $files = \Illuminate\Support\Facades\File::glob($downloadPath . '/*.csv');
            $this->assertNotEmpty($files, 'Indikasi sukses: File CSV berhasil kedownload.');

            $browser->logout();
        });
    }

    /**
     * TC-07: Pengguna non-admin ditolak (403)
     */
    public function testAksesMonitoringDitolakBagiNonAdmin()
    {
        $kegiatanKosong = $this->prepareDummyData();

        $this->browse(function (Browser $browser) use ($kegiatanKosong) {
            // Test 1: User Biasa
            $browser->logout()
                    ->visit('/login')
                    ->type('login', $this->userEmail)
                    ->type('password', $this->userPassword)
                    ->press('Masuk')
                    ->pause(1000)
                    ->visit('/admin/kegiatan/' . $kegiatanKosong->id . '/peserta')
                    ->assertSee('403') // Halaman forbidden default Laravel
                    ->logout();

            // Test 2: Petugas
            $browser->visit('/login')
                    ->type('login', $this->petugasEmail)
                    ->type('password', $this->petugasPassword)
                    ->press('Masuk')
                    ->pause(1000)
                    ->visit('/admin/donasi')
                    ->assertSee('403')
                    ->logout();
        });
    }

    /**
     * TC-XX: Admin memfilter donasi dengan status Menunggu (pending)
     */
    public function testAdminFilterDonasiStatusPending()
    {
        $this->prepareDummyData();

        $donasiPending = \App\Models\Donasi::where('kode_transaksi', 'TEST-MONITORING-PENDING')->first();
        $donasiSukses = \App\Models\Donasi::where('kode_transaksi', 'TEST-MONITORING-SUKSES')->first();
        $donasiExpired = \App\Models\Donasi::where('kode_transaksi', 'TEST-MONITORING-EXPIRED')->first();

        $this->browse(function (Browser $browser) use ($donasiPending, $donasiSukses, $donasiExpired) {
            $this->loginAsAdmin($browser)
                 ->visit('/admin/donasi')
                 ->select('status', 'pending')
                 ->pause(2000) // Tunggu form submit & page reload
                 ->assertQueryStringHas('status', 'pending')
                 ->assertSee('#' . $donasiPending->id)
                 ->assertDontSee('#' . $donasiSukses->id)
                 ->assertDontSee('#' . $donasiExpired->id)
                 ->logout();
        });
    }

    /**
     * TC-XX: Admin memfilter donasi dengan status Berhasil (success)
     */
    public function testAdminFilterDonasiStatusSuccess()
    {
        $this->prepareDummyData();

        $donasiPending = \App\Models\Donasi::where('kode_transaksi', 'TEST-MONITORING-PENDING')->first();
        $donasiSukses = \App\Models\Donasi::where('kode_transaksi', 'TEST-MONITORING-SUKSES')->first();
        $donasiExpired = \App\Models\Donasi::where('kode_transaksi', 'TEST-MONITORING-EXPIRED')->first();

        $this->browse(function (Browser $browser) use ($donasiPending, $donasiSukses, $donasiExpired) {
            $this->loginAsAdmin($browser)
                 ->visit('/admin/donasi')
                 ->select('status', 'success')
                 ->pause(2000) // Tunggu form submit & page reload
                 ->assertQueryStringHas('status', 'success')
                 ->assertSee('#' . $donasiSukses->id)
                 ->assertDontSee('#' . $donasiPending->id)
                 ->assertDontSee('#' . $donasiExpired->id)
                 ->logout();
        });
    }

    /**
     * TC-XX: Admin memfilter donasi dengan status Kadaluarsa (expired)
     */
    public function testAdminFilterDonasiStatusExpired()
    {
        $this->prepareDummyData();

        $donasiPending = \App\Models\Donasi::where('kode_transaksi', 'TEST-MONITORING-PENDING')->first();
        $donasiSukses = \App\Models\Donasi::where('kode_transaksi', 'TEST-MONITORING-SUKSES')->first();
        $donasiExpired = \App\Models\Donasi::where('kode_transaksi', 'TEST-MONITORING-EXPIRED')->first();

        $this->browse(function (Browser $browser) use ($donasiPending, $donasiSukses, $donasiExpired) {
            $this->loginAsAdmin($browser)
                 ->visit('/admin/donasi')
                 ->select('status', 'expired')
                 ->pause(2000) // Tunggu form submit & page reload
                 ->assertQueryStringHas('status', 'expired')
                 ->assertSee('#' . $donasiExpired->id)
                 ->assertDontSee('#' . $donasiPending->id)
                 ->assertDontSee('#' . $donasiSukses->id)
                 ->logout();
        });
    }

    /**
     * TC-XX: Admin memfilter donasi dengan status Gagal (gagal)
     */
    public function testAdminFilterDonasiStatusGagal()
    {
        $this->prepareDummyData();

        $donasiPending = \App\Models\Donasi::where('kode_transaksi', 'TEST-MONITORING-PENDING')->first();
        $donasiSukses = \App\Models\Donasi::where('kode_transaksi', 'TEST-MONITORING-SUKSES')->first();
        $donasiExpired = \App\Models\Donasi::where('kode_transaksi', 'TEST-MONITORING-EXPIRED')->first();

        $this->browse(function (Browser $browser) use ($donasiPending, $donasiSukses, $donasiExpired) {
            $this->loginAsAdmin($browser)
                 ->visit('/admin/donasi')
                 ->select('status', 'gagal')
                 ->pause(2000) // Tunggu form submit & page reload
                 ->assertQueryStringHas('status', 'gagal')
                 ->assertSee('Belum ada data donasi')
                 ->assertDontSee('#' . $donasiPending->id)
                 ->assertDontSee('#' . $donasiSukses->id)
                 ->assertDontSee('#' . $donasiExpired->id)
                 ->logout();
        });
    }

    /**
     * TC-XX: Admin melihat bukti donasi (menggunakan ID spesifik 93)
     */
    public function testAdminMelihatBuktiDonasi()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser)
                 ->visit('/admin/donasi')
                 ->waitFor('#btn-lihat-93', 5) // Pastikan tombol untuk ID 93 ada
                 ->click('#btn-lihat-93')
                 ->pause(1000) // Tunggu modal animasi terbuka
                 ->assertVisible('#imageModal')
                 ->assertVisible('#modalImage')
                 ->click('#imageModal button') // Tutup modal
                 ->pause(500)
                 ->logout();
        });
    }

    /**
     * TC-XX: Admin menerima donasi (Terima)
     */
    public function testAdminMenerimaDonasi()
    {
        $this->prepareDummyData();
        $donasiPending = \App\Models\Donasi::where('kode_transaksi', 'TEST-MONITORING-PENDING')->first();

        $this->browse(function (Browser $browser) use ($donasiPending) {
            $this->loginAsAdmin($browser)
                 ->visit('/admin/donasi')
                 ->waitFor('#btn-terima-' . $donasiPending->id, 5)
                 ->click('#btn-terima-' . $donasiPending->id)
                 ->acceptDialog()
                 ->pause(2000) // Tunggu reload setelah disubmit
                 ->assertSee('Donasi berhasil diterima')
                 ->logout();
        });
        
        $donasiPending->refresh();
        $this->assertEquals('sukses', $donasiPending->status);
    }

    /**
     * TC-XX: Admin menolak donasi (Tolak)
     */
    public function testAdminMenolakDonasi()
    {
        $this->prepareDummyData();
        $donasiPending = \App\Models\Donasi::where('kode_transaksi', 'TEST-MONITORING-PENDING')->first();

        $this->browse(function (Browser $browser) use ($donasiPending) {
            $this->loginAsAdmin($browser)
                 ->visit('/admin/donasi')
                 ->waitFor('#btn-tolak-' . $donasiPending->id, 5)
                 ->click('#btn-tolak-' . $donasiPending->id)
                 ->acceptDialog()
                 ->pause(2000) // Tunggu reload setelah disubmit
                 ->assertSee('Donasi berhasil ditolak')
                 ->logout();
        });

        $donasiPending->refresh();
        $this->assertEquals('gagal', $donasiPending->status);
    }
}
