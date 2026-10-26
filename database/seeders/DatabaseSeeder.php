<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // ADMIN: peran diberikan lewat forceFill (hanya dari seeder, bukan dari input pengguna)
        $admin = User::firstOrCreate(
            ['email' => 'admin@kossinga.com'],
            ['name' => 'Admin KosSinga', 'password' => Hash::make('password123')]
        );
        $admin->forceFill(['role' => 'admin'])->save();

        // PENGGUNA BIASA: untuk menguji bahwa akses ditolak (403)
        User::firstOrCreate(
            ['email' => 'user@kossinga.com'],
            ['name' => 'Mahasiswa Uji', 'password' => Hash::make('password123')]
        );

        // ...$this->call([...]) milikmu tetap di bawah sini
    }
}