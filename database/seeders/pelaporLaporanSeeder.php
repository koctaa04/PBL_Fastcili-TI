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
                'id_user' => 1,
                'id_laporan' => 1, // laporan: "PC tidak bisa menyala"
                'deskripsi_tambahan' => 'Sudah dicoba ganti kabel, tetap tidak menyala',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 2,
                'id_laporan' => 1,
                'deskripsi_tambahan' => 'PC mengeluarkan bunyi beep saat dinyalakan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 1,
                'id_laporan' => 2, // laporan: "Kipas mati total"
                'deskripsi_tambahan' => 'Ruangan jadi pengap karena tidak ada sirkulasi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
