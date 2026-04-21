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
     * Membuat 3 akun test — satu per role — untuk keperluan pengujian.
     */
    public function run(): void
    {
        $users = [
            [
                'name'     => 'Admin Greennovate',
                'email'    => 'admin@greennovate.test',
                'password' => Hash::make('Password123!'),
                'role'     => 'admin',
                'is_active'=> true,
            ],
            [
                'name'     => 'Petugas Greennovate',
                'email'    => 'petugas@greennovate.test',
                'password' => Hash::make('Password123!'),
                'role'     => 'petugas',
                'is_active'=> true,
            ],
            [
                'name'     => 'User Greennovate',
                'email'    => 'user@greennovate.test',
                'password' => Hash::make('Password123!'),
                'role'     => 'user',
                'is_active'=> true,
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
