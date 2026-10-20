<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // akun untuk uji login API (dibuat hanya jika belum ada)
        User::firstOrCreate(
            ['email' => 'admin@kossinga.com'],
            ['name' => 'Admin KosSinga', 'password' => Hash::make('password123')]
        );

        // urutan penting: pemilik dulu, baru kos (kos menunjuk ke pemilik)
        $this->call([
            PemilikSeeder::class,
            KosSeeder::class,
        ]);
    }
}