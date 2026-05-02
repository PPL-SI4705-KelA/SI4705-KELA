<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Membuat 3 akun awal:
     * - 1 akun admin  (hardcoded, tidak bisa daftar lewat form)
     * - 1 akun petugas (hardcoded, tidak bisa daftar lewat form)
     * - 1 akun user biasa untuk testing
     */
    public function run(): void
    {
        // ── Admin ──────────────────────────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'pardede281204@gmail.com'],
            [
                'name'      => 'Admin Greennovate',
                'password'  => Hash::make('QWERTY12345'),
                'role'      => User::ROLE_ADMIN,
                'is_active' => true,
            ]
        );

        // ── Petugas ────────────────────────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'petugas@greennovate.test'],
            [
                'name'      => 'Petugas Greennovate',
                'password'  => Hash::make('petugas123'),
                'role'      => User::ROLE_PETUGAS,
                'is_active' => true,
            ]
        );

        // ── User biasa (untuk testing) ─────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'user@greennovate.test'],
            [
                'name'      => 'User Biasa',
                'password'  => Hash::make('user12345'),
                'role'      => User::ROLE_USER,
                'is_active' => true,
            ]
        );
    }
}