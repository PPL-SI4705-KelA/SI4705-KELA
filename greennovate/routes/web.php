<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\JenisPohonController;
use App\Http\Controllers\Admin\KegiatanController as AdminKegiatanController;
use App\Http\Controllers\Admin\LokasiLahanController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Petugas\PetugasDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\NotifikasiController;
use Illuminate\Support\Facades\Route;

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

    // ── Kegiatan - hanya form daftar yang butuh login ─────────────────────────
    Route::get('/kegiatan/{slug}/daftar', [KegiatanController::class, 'showDaftarForm'])->name('kegiatan.daftar.form');
    Route::post('/kegiatan/{slug}/daftar', [KegiatanController::class, 'daftar'])->name('kegiatan.daftar');

    // ── Profile & Settings ────────────────────────────────────────────────────
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::patch('/profile/preferences', [ProfileController::class, 'updatePreferences'])->name('profile.preferences');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ── Riwayat Partisipasi (PB-11) ───────────────────────────────────────
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
    Route::get('/riwayat/{tipe}/{id}/detail', [RiwayatController::class, 'detail'])->name('riwayat.detail');
    Route::get('/riwayat/{tipe}/{id}/download', [RiwayatController::class, 'download'])->name('riwayat.download');

    // ── API Riwayat ───────────────────────────────────────────────────────
    Route::get('/api/riwayat', [RiwayatController::class, 'apiIndex'])->name('api.riwayat.index');
    Route::get('/api/riwayat/{tipe}/{id}/detail', [RiwayatController::class, 'apiDetail'])->name('api.riwayat.detail');

    // ── Achievement & O2 Stats ──────────────────────────────────────────
    Route::get('/achievement', [AchievementController::class, 'index'])->name('achievement.index');
    Route::get('/o2/stats', [AchievementController::class, 'stats'])->name('o2.stats');
    Route::get('/achievement/progress', [AchievementController::class, 'progress'])->name('achievement.progress');

    // ── Notifikasi ──────────────────────────────────────────────────────
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::patch('/notifikasi/baca-semua', [NotifikasiController::class, 'tandaiBacaSemua'])->name('notifikasi.baca-semua');
    Route::patch('/notifikasi/{id}/baca', [NotifikasiController::class, 'tandaiBaca'])->name('notifikasi.baca');

    // ── Admin routes ──────────────────────────────────────────────────────────
    Route::middleware('is.admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
            Route::resource('kegiatan', AdminKegiatanController::class);
            Route::resource('lokasi', LokasiLahanController::class);
            Route::resource('jenis-pohon', JenisPohonController::class)->except(['show']);
        });

    // ── Petugas routes (PB-21) ────────────────────────────────────────────────
    Route::middleware('is.petugas')
        ->prefix('petugas')
        ->name('petugas.')
        ->group(function () {
            Route::get('/dashboard', [PetugasDashboardController::class, 'index'])->name('dashboard');
            Route::get('/semua-kegiatan', [PetugasDashboardController::class, 'semuaKegiatan'])->name('semua-kegiatan');

            // API endpoints
            Route::get('/api/jenis-pohon', [PetugasDashboardController::class, 'getJenisPohon'])->name('api.jenis-pohon');
            Route::post('/api/kegiatan/{kegiatan}/realisasi', [PetugasDashboardController::class, 'storeRealisasi'])->name('api.store-realisasi');
            Route::get('/api/dashboard', [PetugasDashboardController::class, 'apiDashboard'])->name('api.dashboard');
        });
});