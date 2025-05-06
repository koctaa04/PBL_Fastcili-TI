<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PenugasanTeknisi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PenugasanTeknisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PenugasanTeknisi::insert([
            ['id_laporan' => 1, 'id_user' => 5, 'tanggal_selesai' => null, 'status_perbaikan' => 'Sedang dikerjakan'],
            ['id_laporan' => 2, 'id_user' => 5, 'tanggal_selesai' => null, 'status_perbaikan' => 'Sedang dikerjakan'],
            ['id_laporan' => 3, 'id_user' => 5, 'tanggal_selesai' => now(), 'status_perbaikan' => 'Selesai Dikerjakan'],
            ['id_laporan' => 4, 'id_user' => 5, 'tanggal_selesai' => now(), 'status_perbaikan' => 'Sedang dikerjakan'],
            ['id_laporan' => 5, 'id_user' => 5, 'tanggal_selesai' => now(), 'status_perbaikan' => 'Selesai Dikerjakan'],
        ]);
    }
}
