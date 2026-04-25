<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

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

        // Read only
        Route::get('/', [ProfileController::class, 'index'])->name('index');

        // Edit page
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');

        // Update data
        Route::post('/update', [ProfileController::class, 'update'])->name('update');

        // Change password page
        Route::get('/password', [ProfileController::class, 'showChangePasswordForm'])
            ->name('password.form');

        // Process change password
        Route::post('/password', [ProfileController::class, 'updatePassword'])
            ->name('password.update');
    });
});

