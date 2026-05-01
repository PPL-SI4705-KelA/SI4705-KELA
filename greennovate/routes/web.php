<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\LokasiLahanController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Petugas\PetugasDashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

Route::get('/', [LandingController::class, 'index'])->name('home');

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/login', [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Auth routes
Route::middleware(['auth', 'check.active'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::post('/update', [ProfileController::class, 'update'])->name('update');
        Route::get('/password', [ProfileController::class, 'showChangePasswordForm'])->name('password.form');
        Route::post('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    });

    // Admin routes
    Route::middleware('is.admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
            Route::resource('lokasi', LokasiLahanController::class);
        });

    // Petugas routes
    Route::middleware('is.petugas')
        ->prefix('petugas')
        ->name('petugas.')
        ->group(function () {
            Route::get('/dashboard', [PetugasDashboardController::class, 'index'])->name('dashboard');
        });
});
