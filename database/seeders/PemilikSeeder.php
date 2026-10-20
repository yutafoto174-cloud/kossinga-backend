<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PemilikSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['id_pemilik' => 1, 'nama_pemilik' => 'Bu Made',    'no_wa' => '6281234567890'],
            ['id_pemilik' => 2, 'nama_pemilik' => 'Pak Komang', 'no_wa' => '6281234567891'],
            ['id_pemilik' => 3, 'nama_pemilik' => 'Bu Ketut',   'no_wa' => '6281234567892'],
        ];

        foreach ($data as $row) {
            // updateOrInsert: jika id sudah ada -> diperbarui, jika belum -> ditambahkan
            // sehingga seeder aman dijalankan berulang tanpa data ganda
            DB::table('pemiliks')->updateOrInsert(
                ['id_pemilik' => $row['id_pemilik']],
                $row + ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}