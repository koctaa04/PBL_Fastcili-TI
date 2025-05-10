<?php

namespace Database\Seeders;

use App\Models\Fasilitas;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FasilitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Fasilitas::insert([
            ['id_ruangan' => 1, 'nama_fasilitas' => 'Proyektor', 'jumlah' => 5],
            ['id_ruangan' => 1, 'nama_fasilitas' => 'PC Server', 'jumlah' => 5],
            ['id_ruangan' => 2, 'nama_fasilitas' => 'Kipas Angin', 'jumlah' => 5],
            ['id_ruangan' => 3, 'nama_fasilitas' => 'Whiteboard', 'jumlah' => 5],
            ['id_ruangan' => 4, 'nama_fasilitas' => 'Kursi Kuliah', 'jumlah' => 5],
        ]);
    }
}
