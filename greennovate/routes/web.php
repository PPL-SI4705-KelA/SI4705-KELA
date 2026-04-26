<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Petugas\PetugasDashboardController;
use Illuminate\Support\Facades\Route;

// ─── Public ───────────────────────────────────────────────────────────────────
Route::get('/', function () {
    return view('welcome');
});

// ─── Guest routes ─────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/login', [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// ─── Authenticated routes (semua role) ────────────────────────────────────────
Route::middleware(['auth', 'check.active'])->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dispatcher: redirect ke dashboard sesuai role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Profile (semua role yang sudah login) ──
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/',         [ProfileController::class, 'index'])->name('index');
        Route::get('/edit',     [ProfileController::class, 'edit'])->name('edit');
        Route::post('/update',  [ProfileController::class, 'update'])->name('update');
        Route::get('/password', [ProfileController::class, 'showChangePasswordForm'])->name('password.form');
        Route::post('/password',[ProfileController::class, 'updatePassword'])->name('password.update');
    });

    // ── Admin routes ───────────────────────────────────────────────────────────
    // Hanya bisa diakses user dengan role 'admin'.
    // Jika role lain mencoba → 403
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        // Tambahkan route admin lainnya di sini, contoh:
        // Route::resource('users', AdminUserController::class);
        // Route::resource('kegiatan', AdminKegiatanController::class);
    });

    // ── Petugas routes ─────────────────────────────────────────────────────────
    // Hanya bisa diakses user dengan role 'petugas'.
    // Jika role lain mencoba → 403
    Route::prefix('petugas')->name('petugas.')->middleware('role:petugas')->group(function () {
        Route::get('/dashboard', [PetugasDashboardController::class, 'index'])->name('dashboard');
        // Tambahkan route petugas lainnya di sini, contoh:
        // Route::resource('donasi', PetugasDonasController::class);
    });
});
