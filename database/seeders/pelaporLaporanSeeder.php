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
                'id_user' => 31,
                'id_laporan' => 1, // laporan: "PC tidak bisa menyala"
                'deskripsi_tambahan' => 'PC tidak bisa menyala',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 32,
                'id_laporan' => 2, // laporan: "Kipas mati total"
                'deskripsi_tambahan' => 'Port kabel tidak berfungsi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 38,
                'id_laporan' => 3,
                'deskripsi_tambahan' => 'AC hanya mengeluarkan angin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 25,
                'id_laporan' => 4,
                'deskripsi_tambahan' => 'Papan Tulis Rusak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 40,
                'id_laporan' => 1,
                'deskripsi_tambahan' => 'PC mengeluarkan bunyi beep saat dinyalakan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 44,
                'id_laporan' => 5,
                'deskripsi_tambahan' => 'Proyektor tidak bisa menyambung',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 20,
                'id_laporan' => 6,
                'deskripsi_tambahan' => 'Kaki meja patah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 31,
                'id_laporan' => 7,
                'deskripsi_tambahan' => 'Printer rusak dan berdengung',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 40,
                'id_laporan' => 8,
                'deskripsi_tambahan' => 'Kipas angin tidak menyala',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 28,
                'id_laporan' => 4,
                'deskripsi_tambahan' => 'Papan Tulis Rusak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 49,
                'id_laporan' => 8,
                'deskripsi_tambahan' => 'Kipas angin tidak bisa dinyalakan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 18,
                'id_laporan' => 5,
                'deskripsi_tambahan' => 'Proyektor tidak bisa hidup',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 41,
                'id_laporan' => 5,
                'deskripsi_tambahan' => 'Proyektor tidak bisa menyambung',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 36,
                'id_laporan' => 7,
                'deskripsi_tambahan' => 'Printer tidak bisa digunakan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 18,
                'id_laporan' => 8,
                'deskripsi_tambahan' => 'Kipas angin tidak menyala',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
