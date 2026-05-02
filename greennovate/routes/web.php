<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\KegiatanController as AdminKegiatanController;
use App\Http\Controllers\Petugas\PetugasDashboardController;
use Illuminate\Support\Facades\Route;

// ─── Public ───────────────────────────────────────────────────────────────────
Route::get('/', function () {
    return view('welcome');
});

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

    // ── Profile (Bisa diakses Admin, Petugas, dan User) ──
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/',              [ProfileController::class, 'index'])->name('index');
        Route::get('/edit',          [ProfileController::class, 'edit'])->name('edit');
        Route::post('/update',       [ProfileController::class, 'update'])->name('update');
        Route::get('/password',      [ProfileController::class, 'showChangePasswordForm'])->name('password.form');
        Route::post('/password',     [ProfileController::class, 'updatePassword'])->name('password.update');
    });

    // ── Admin routes ───────────────────────────────────────────────────────────
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // GN-12: CRUD Kegiatan (Fitur Manajemen Reboisasi/Lingkungan)
        Route::resource('kegiatan', AdminKegiatanController::class);
    });

    // ── Petugas routes ─────────────────────────────────────────────────────────
    Route::prefix('petugas')->name('petugas.')->middleware('role:petugas')->group(function () {
        Route::get('/dashboard', [PetugasDashboardController::class, 'index'])->name('dashboard');
    });
});