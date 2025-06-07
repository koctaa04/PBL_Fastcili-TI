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
            [
                'id_laporan' => 4,
                'id_user' => 5,
                'status_perbaikan' => 'Sedang dikerjakan',
                'tanggal_selesai' => null,
                'tenggat' => now()->addDays(3),
                'catatan_teknisi' => null,
                'dokumentasi' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_laporan' => 6,
                'id_user' => 5,
                'status_perbaikan' => 'Selesai Dikerjakan',
                'tanggal_selesai' => now(),
                'tenggat' => now()->addDays(3),
                'catatan_teknisi' => 'Printer sudah diperbaiki. Kerusakan diakibatkan karena kabel printer rusak',
                'dokumentasi' => 'printer-3d.jpg',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
