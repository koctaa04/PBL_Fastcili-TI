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
        // KriteriaPenilaian::insert([
        //     ['id_laporan' => 1, 'tingkat_kerusakan' => '1', 'frekuensi_digunakan' => '1', 'dampak' => '1' , 'estimasi_biaya' => '1', 'potensi_bahaya' => '1'],
        //     ['id_laporan' => 2, 'tingkat_kerusakan' => '1', 'frekuensi_digunakan' => '5', 'dampak' => '1' , 'estimasi_biaya' => '5', 'potensi_bahaya' => '1'],
        //     ['id_laporan' => 3, 'tingkat_kerusakan' => '5', 'frekuensi_digunakan' => '1', 'dampak' => '1' , 'estimasi_biaya' => '1', 'potensi_bahaya' => '5'],
        //     ['id_laporan' => 4, 'tingkat_kerusakan' => '5', 'frekuensi_digunakan' => '3', 'dampak' => '5' , 'estimasi_biaya' => '1', 'potensi_bahaya' => '1'],
        //     ['id_laporan' => 5, 'tingkat_kerusakan' => '3', 'frekuensi_digunakan' => '1', 'dampak' => '1' , 'estimasi_biaya' => '1', 'potensi_bahaya' => '1'],
        // ]);
    }
}
