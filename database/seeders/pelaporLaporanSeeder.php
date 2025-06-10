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
                'id_user' => 10,
                'id_laporan' => 1, // Proyektor tidak bisa menyambung
                'deskripsi_tambahan' => 'Sudah coba ganti kabel HDMI tapi tetap tidak muncul tampilan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 11,
                'id_laporan' => 2, // Lemari Arsip pintunya rusak
                'deskripsi_tambahan' => 'Engsel pintu sudah longgar sejak minggu lalu.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 10,
                'id_laporan' => 3, // Printer tidak bisa mencetak
                'deskripsi_tambahan' => 'Sudah diinstal ulang drivernya tapi tetap tidak bisa cetak.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 11,
                'id_laporan' => 4, // Whiteboard retak
                'deskripsi_tambahan' => 'Retakan menyebar dan bisa membahayakan jika jatuh.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 21,
                'id_laporan' => 5, // AC mengeluarkan bunyi keras
                'deskripsi_tambahan' => 'Bunyi sangat mengganggu saat perkuliahan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 22,
                'id_laporan' => 6, // Printer 3D nozzle tersumbat
                'deskripsi_tambahan' => 'Sudah dibersihkan tapi nozzle tetap mampet.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 23,
                'id_laporan' => 7, // Kit Robotik tidak menyala
                'deskripsi_tambahan' => 'Dugaan masalah di kabel powernya.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 21,
                'id_laporan' => 8, // Dispenser tidak keluar air panas
                'deskripsi_tambahan' => 'Sudah coba isi ulang, tapi air tetap tidak panas.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
