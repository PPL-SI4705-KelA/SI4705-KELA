<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\KegiatanController as AdminKegiatanController;
use App\Http\Controllers\Admin\LokasiLahanController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Petugas\PetugasDashboardController;
use App\Http\Controllers\ProfileController;
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

<<<<<<< HEAD
    // ── Riwayat Partisipasi ───────────────────────────────────────────────────
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
    Route::get('/riwayat/{type}/{id}', [RiwayatController::class, 'detail'])
        ->name('riwayat.detail')
        ->where('type', 'donasi|pembelian|kegiatan');
    Route::get('/riwayat/{type}/{id}/download-dokumentasi', [RiwayatController::class, 'downloadDokumentasi'])
        ->name('riwayat.download')
        ->where('type', 'donasi|pembelian|kegiatan');

=======
>>>>>>> parent of 5b93d16 (Merge yuka_branch (Participation History) into main)
    // ── Admin routes ──────────────────────────────────────────────────────────
    Route::middleware('is.admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
            Route::resource('kegiatan', AdminKegiatanController::class);
            Route::resource('lokasi', LokasiLahanController::class);
        });

    // ── Petugas routes ────────────────────────────────────────────────────────
    Route::middleware('is.petugas')
        ->prefix('petugas')
        ->name('petugas.')
        ->group(function () {
            Route::get('/dashboard', [PetugasDashboardController::class, 'index'])->name('dashboard');
        });
});