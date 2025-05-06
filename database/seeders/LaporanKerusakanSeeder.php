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
            ['id_user' => 2, 'id_fasilitas' => 2, 'deskripsi' => 'PC tidak bisa menyala', 'foto_kerusakan' => 'tes.jpg' , 'tanggal_lapor' => now(), 'id_status' => 1],
            ['id_user' => 3, 'id_fasilitas' => 3, 'deskripsi' => 'Kipas mati total', 'foto_kerusakan' => 'tes.jpg' , 'tanggal_lapor' => now(), 'id_status' => 1],
            ['id_user' => 4, 'id_fasilitas' => 4, 'deskripsi' => 'Papan tulis retak', 'foto_kerusakan' => 'tes.jpg' , 'tanggal_lapor' => now(), 'id_status' => 2],
            ['id_user' => 2, 'id_fasilitas' => 5, 'deskripsi' => 'Kursi goyang', 'foto_kerusakan' => 'tes.jpg' , 'tanggal_lapor' => now(), 'id_status' => 1],
            ['id_user' => 3, 'id_fasilitas' => 1, 'deskripsi' => 'Proyektor tidak nyala', 'foto_kerusakan' => 'tes.jpg' , 'tanggal_lapor' => now(), 'id_status' => 3],
        ]);
    }
}
