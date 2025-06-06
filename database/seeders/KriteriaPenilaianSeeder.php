<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KriteriaPenilaian;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KriteriaPenilaianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        KriteriaPenilaian::insert([
            [
                'id_laporan' => 4,
                'tingkat_kerusakan' => 1,
                'frekuensi_digunakan' => 3,
                'dampak' => 5,
                'estimasi_biaya' => 2,
                'potensi_bahaya' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_laporan' => 6,
                'tingkat_kerusakan' => 3,
                'frekuensi_digunakan' => 3,
                'dampak' => 3,
                'estimasi_biaya' => 2,
                'potensi_bahaya' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_laporan' => 3,
                'tingkat_kerusakan' => 3,
                'frekuensi_digunakan' => 3,
                'dampak' => 3,
                'estimasi_biaya' => 2,
                'potensi_bahaya' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
