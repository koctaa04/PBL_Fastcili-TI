<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LaporanKerusakan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\Carbon;

class LaporanKerusakanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LaporanKerusakan::insert([
            [
                'id_fasilitas' => 1,
                'deskripsi' => 'Proyektor tidak bisa menyambung',
                'foto_kerusakan' => 'pcrusak.png',
                'jumlah_kerusakan' => 1,
                'tanggal_lapor' => Carbon::parse('2025-03-10'),
                'tanggal_selesai' => null,
                'id_status' => 1
            ],
            [
                'id_fasilitas' => 2,
                'deskripsi' => 'Lemari Arsip pintunya rusak',
                'foto_kerusakan' => 'lemari-rusak.jpg',
                'jumlah_kerusakan' => 1,
                'tanggal_lapor' => $lapor2 = Carbon::parse('2025-03-15'),
                'tanggal_selesai' => $lapor2->copy()->addDays(5),
                'id_status' => 3
            ],
            [
                'id_fasilitas' => 3,
                'deskripsi' => 'Printer tidak bisa mencetak',
                'foto_kerusakan' => 'printer-rusak.jpg',
                'jumlah_kerusakan' => 1,
                'tanggal_lapor' => $lapor3 = Carbon::parse('2025-01-20'),
                'tanggal_selesai' => $lapor3->copy()->addDays(3),
                'id_status' => 2
            ],
            [
                'id_fasilitas' => 4,
                'deskripsi' => 'Whiteboard retak',
                'foto_kerusakan' => 'ppntulis-rusak.jpg',
                'jumlah_kerusakan' => 1,
                'tanggal_lapor' => Carbon::parse('2025-01-02'),
                'tanggal_selesai' => null,
                'id_status' => 2
            ],
            [
                'id_fasilitas' => 12,
                'deskripsi' => 'AC mengeluarkan bunyi keras',
                'foto_kerusakan' => 'ac-rusak.png',
                'jumlah_kerusakan' => 1,
                'tanggal_lapor' => $lapor5 = Carbon::parse('2025-03-25'),
                'tanggal_selesai' => $lapor5->copy()->addDays(6),
                'id_status' => 3
            ],
            [
                'id_fasilitas' => 17,
                'deskripsi' => 'Printer 3D nozzle tersumbat',
                'foto_kerusakan' => 'printer3d-rusak.jpg',
                'jumlah_kerusakan' => 1,
                'tanggal_lapor' => Carbon::parse('2025-04-10'),
                'tanggal_selesai' => null,
                'id_status' => 2
            ],
            [
                'id_fasilitas' => 16,
                'deskripsi' => 'Kit Robotik tidak menyala',
                'foto_kerusakan' => 'kit-rusak.jpg',
                'jumlah_kerusakan' => 1,
                'tanggal_lapor' => $lapor7 = Carbon::parse('2025-05-05'),
                'tanggal_selesai' => $lapor7->copy()->addDays(4),
                'id_status' => 4
            ],
            [
                'id_fasilitas' => 13,
                'deskripsi' => 'Dispenser tidak keluar air panas',
                'foto_kerusakan' => 'dispenser-rusak.jpg',
                'jumlah_kerusakan' => 1,
                'tanggal_lapor' => Carbon::parse('2025-02-25'),
                'tanggal_selesai' => null,
                'id_status' => 1
            ],
        ]);
    }
}
