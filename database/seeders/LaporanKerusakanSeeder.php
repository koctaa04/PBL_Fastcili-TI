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
            ['id_fasilitas' => 2, 'deskripsi' => 'PC tidak bisa menyala', 'foto_kerusakan' => 'gedung1.jpg' , 'tanggal_lapor' => now(), 'id_status' => 1],
            ['id_fasilitas' => 3, 'deskripsi' => 'Kipas mati total', 'foto_kerusakan' => 'gedung2.jpg' , 'tanggal_lapor' => now(), 'id_status' => 1],
        ]);
    }
}
