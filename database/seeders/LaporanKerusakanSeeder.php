<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LaporanKerusakan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LaporanKerusakanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LaporanKerusakan::insert([
            ['id_fasilitas' => 2, 'deskripsi' => 'PC tidak bisa menyala', 'foto_kerusakan' => 'pcrusak.png', 'tanggal_lapor' => now(), 'id_status' => 1],
            ['id_fasilitas' => 3, 'deskripsi' => 'Port kabel tidak berfungsi', 'foto_kerusakan' => 'pcrusak.png', 'tanggal_lapor' => now(), 'id_status' => 1],
            ['id_fasilitas' => 23, 'deskripsi' => 'AC hanya mengeluarkan angin', 'foto_kerusakan' => 'ac-rusak.png', 'tanggal_lapor' => now(), 'id_status' => 1],
            ['id_fasilitas' => 4, 'deskripsi' => 'Papan Tulis Rusak', 'foto_kerusakan' => 'ppntulis-rusak.jpg', 'tanggal_lapor' => now(), 'id_status' => 1],
        ]);
    }
}
