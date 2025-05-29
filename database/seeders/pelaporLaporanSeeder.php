<?php

namespace Database\Seeders;

use App\Models\PelaporLaporan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class pelaporLaporanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PelaporLaporan::insert([
            [
                'id_user' => 2,
                'id_laporan' => 1, // laporan: "PC tidak bisa menyala"
                'deskripsi_tambahan' => 'PC tidak bisa menyala',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 4,
                'id_laporan' => 2, // laporan: "Kipas mati total"
                'deskripsi_tambahan' => 'Port kabel tidak berfungsi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 2,
                'id_laporan' => 3,
                'deskripsi_tambahan' => 'AC hanya mengeluarkan angin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 4,
                'id_laporan' => 4,
                'deskripsi_tambahan' => 'Papan Tulis Rusak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 3,
                'id_laporan' => 1,
                'deskripsi_tambahan' => 'PC mengeluarkan bunyi beep saat dinyalakan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
