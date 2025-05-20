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
                'rating_pengguna' => 3,
                'feedback_pengguna' => 'Mohon segera ditangani karena dipakai praktikum',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 2,
                'id_laporan' => 1,
                'deskripsi_tambahan' => 'PC mengeluarkan bunyi beep saat dinyalakan',
                'rating_pengguna' => 4,
                'feedback_pengguna' => 'Mungkin masalah RAM',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 1,
                'id_laporan' => 2, // laporan: "Kipas mati total"
                'deskripsi_tambahan' => 'Ruangan jadi pengap karena tidak ada sirkulasi',
                'rating_pengguna' => 5,
                'feedback_pengguna' => 'Wajib segera diganti',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
