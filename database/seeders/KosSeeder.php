<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KosSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['id_kos' => 1, 'id_pemilik' => 1, 'nama' => 'Kos Putri Asri',         'harga' => 650000,  'daerah' => 'Jineng Dalem', 'tipe' => 'Putri',  'fasilitas' => 'WiFi, Kamar mandi dalam, Parkir motor', 'status' => 'Y'],
            ['id_kos' => 2, 'id_pemilik' => 2, 'nama' => 'Kos Putra Mandiri',      'harga' => 550000,  'daerah' => 'Banyuasri',    'tipe' => 'Putra',  'fasilitas' => 'WiFi, Dapur bersama',                   'status' => 'Y'],
            ['id_kos' => 3, 'id_pemilik' => 3, 'nama' => 'Kos Campur Singaraja',   'harga' => 500000,  'daerah' => 'Kampung Baru', 'tipe' => 'Campur', 'fasilitas' => 'Parkir motor, Kipas angin',             'status' => 'N'],
            ['id_kos' => 4, 'id_pemilik' => 1, 'nama' => 'Kos Eksklusif Undiksha', 'harga' => 1200000, 'daerah' => 'Jineng Dalem', 'tipe' => 'Putri',  'fasilitas' => 'AC, WiFi, Kamar mandi dalam, Laundry',  'status' => 'Y'],
            ['id_kos' => 5, 'id_pemilik' => 2, 'nama' => 'Kos Putra Bahari',       'harga' => 700000,  'daerah' => 'Kaliuntu',     'tipe' => 'Putra',  'fasilitas' => 'WiFi, AC, Parkir motor',                'status' => 'Y'],
        ];

        foreach ($data as $row) {
            $row['slug'] = Str::slug($row['nama']);   // 'Kos Putri Asri' -> 'kos-putri-asri'
            $row['foto'] = null;

            DB::table('kos')->updateOrInsert(
                ['id_kos' => $row['id_kos']],
                $row + ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}