<?php

use App\Http\Controllers\Admin\AdminChatController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\JenisPohonController;
use App\Http\Controllers\Admin\KegiatanController as AdminKegiatanController;
use App\Http\Controllers\Admin\LokasiLahanController;
use App\Http\Controllers\Admin\AdminMonitoringController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Petugas\PetugasDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\DonationController;
use Illuminate\Support\Facades\Route;
use App\Models\DokumentasiKegiatan;


// ─── Public ───────────────────────────────────────────────────────────────────
Route::get('/', [LandingController::class, 'index'])->name('home');

// ─── Kegiatan public (bisa diakses tanpa login) ───────────────────────────────
Route::get('/kegiatan', [KegiatanController::class, 'index'])->name('kegiatan.index');
Route::get('/kegiatan/{slug}', [KegiatanController::class, 'show'])->name('kegiatan.show');

// ─── Guest routes (Hanya untuk yang belum login) ──────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/login', [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// ─── Authenticated routes (Semua role yang sudah login & aktif) ───────────────
Route::middleware(['auth', 'check.active'])->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dispatcher: Mengarahkan user ke dashboard sesuai role masing-masing
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Fitur Kontribusi Penanaman Pohon ──────────────────────────────────────
    Route::get('/pembelian', [PembelianController::class, 'index'])->name('pembelian.index');
    Route::post('/pembelian/checkout', [PembelianController::class, 'checkout'])->name('pembelian.checkout');
    Route::get('/pembelian/invoice/{id}', [PembelianController::class, 'invoice'])->name('pembelian.invoice');
    Route::post(
        '/pembelian/upload-bukti/{id}',
        [PembelianController::class, 'uploadBukti']
    )->name('pembelian.upload-bukti');

    // ── Kegiatan - hanya form daftar yang butuh login ─────────────────────────
    Route::get('/kegiatan/{slug}/daftar', [KegiatanController::class, 'showDaftarForm'])->name('kegiatan.daftar.form');
    Route::post('/kegiatan/{slug}/daftar', [KegiatanController::class, 'daftar'])->name('kegiatan.daftar');

    // ── Profile & Settings ────────────────────────────────────────────────────
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::patch('/profile/preferences', [ProfileController::class, 'updatePreferences'])->name('profile.preferences');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ── Donasi ────────────────────────────────────────────────────────────
    Route::get('/donations', [DonationController::class, 'index'])->name('donations.index');
    Route::post('/donations/proses', [DonationController::class, 'prosesValidasiPertama'])->name('donations.proses');
    Route::get('/donations/confirmation', [DonationController::class, 'confirmation'])->name('donations.confirmation');
    Route::post('/donations/submit', [DonationController::class, 'lanjutPembayaran'])->name('donations.submit');
    Route::get('/donations/payment/{donasi}', [DonationController::class, 'payment'])->name('donations.payment');
    Route::post('/donations/upload-proof/{donasi}', [DonationController::class, 'uploadBuktiPembayaran'])->name('donations.upload-proof');

    // ── Riwayat Partisipasi (PB-11) ───────────────────────────────────────
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
    Route::get('/riwayat/{tipe}/{id}/detail', [RiwayatController::class, 'detail'])->name('riwayat.detail');
    Route::get('/riwayat/{tipe}/{id}/download', [RiwayatController::class, 'download'])->name('riwayat.download');

    // ── API Riwayat ───────────────────────────────────────────────────────
    Route::get('/api/riwayat', [RiwayatController::class, 'apiIndex'])->name('api.riwayat.index');
    Route::get('/api/riwayat/{tipe}/{id}/detail', [RiwayatController::class, 'apiDetail'])->name('api.riwayat.detail');

    // ── Chat (User) ───────────────────────────────────────────────────────
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');
    Route::get('/chat/messages', [ChatController::class, 'getMessages'])->name('chat.messages');

    // ── Admin routes ──────────────────────────────────────────────────────────
    Route::middleware('is.admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
            Route::resource('kegiatan', AdminKegiatanController::class);
            Route::resource('lokasi', LokasiLahanController::class);
            Route::resource('jenis-pohon', JenisPohonController::class)->except(['show']);

            // ── Chat (Admin) ──────────────────────────────────────────────────
            Route::get('/chat', [AdminChatController::class, 'index'])->name('chat.index');
            Route::get('/chat/{conversation}', [AdminChatController::class, 'show'])->name('chat.show');
            Route::post('/chat/{conversation}/reply', [AdminChatController::class, 'reply'])->name('chat.reply');
            Route::get('/chat/{conversation}/messages', [AdminChatController::class, 'getMessages'])->name('chat.messages');

            // Monitoring Routes
            Route::get('/kegiatan/{id}/peserta', [AdminMonitoringController::class, 'pesertaKegiatan'])->name('kegiatan.peserta');
            Route::get('/peserta', [AdminMonitoringController::class, 'pesertaIndex'])->name('peserta.index');
            Route::get('/donasi', [AdminMonitoringController::class, 'donasiIndex'])->name('donasi.index');
            Route::get('/pembelian', [AdminMonitoringController::class, 'pembelianIndex'])->name('pembelian.index');
            Route::get('/pengguna', [AdminMonitoringController::class, 'penggunaIndex'])->name('pengguna.index');
            Route::get('/reports/donasi.csv', [AdminMonitoringController::class, 'exportDonasiCsv'])->name('reports.donasi.csv');
        });

    // ── Petugas routes (PB-21) ────────────────────────────────────────────────
    Route::middleware('is.petugas')
        ->prefix('petugas')
        ->name('petugas.')
        ->group(function () {
            Route::get('/dashboard', [PetugasDashboardController::class, 'index'])->name('dashboard');
            Route::get('/semua-kegiatan', [PetugasDashboardController::class, 'semuaKegiatan'])->name('semua-kegiatan');

            Route::get('/realisasi', [PetugasDashboardController::class, 'showRealisasiForm'])->name('realisasi');
            Route::post('/realisasi', [PetugasDashboardController::class, 'storeRealisasiForm'])->name('realisasi.store');

            // API endpoints
            Route::get('/api/jenis-pohon', [PetugasDashboardController::class, 'getJenisPohon'])->name('api.jenis-pohon');
            Route::post('/api/kegiatan/{kegiatan}/realisasi', [PetugasDashboardController::class, 'storeRealisasi'])->name('api.store-realisasi');
            Route::get('/api/dashboard', [PetugasDashboardController::class, 'apiDashboard'])->name('api.dashboard');

            // Documentation Upload (Unified)
            Route::post('/api/kegiatan/{kegiatan}/dokumentasi', [PetugasDashboardController::class, 'uploadDokumentasi'])->name('api.store-dokumentasi');
        });

});
