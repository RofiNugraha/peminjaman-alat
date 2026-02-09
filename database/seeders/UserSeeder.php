<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'nama' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        for ($i = 1; $i <= 50; $i++) {
            User::create([
                'nama' => "User Dummy $i",
                'username' => "user$i",
                'email' => "user$i@example.com",
                'password' => Hash::make('password123'),
                'role' => ['petugas', 'peminjam'][array_rand(['petugas', 'peminjam'])],
            ]);
        }
    }
}