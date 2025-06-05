<?php

namespace Database\Seeders;

use App\Models\Ruangan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ruangan::insert([
            ['id_gedung' => 21, 'nama_ruangan' => 'Lab Jaringan', 'kode_ruangan' => 'TS-101'],
            ['id_gedung' => 21, 'nama_ruangan' => 'Lab RPL', 'kode_ruangan' => 'TS-102'],
            ['id_gedung' => 21, 'nama_ruangan' => 'Lab Basis Data', 'kode_ruangan' => 'TS-103'],
            ['id_gedung' => 21, 'nama_ruangan' => 'Kelas TI-1A', 'kode_ruangan' => 'TS-201'],
            ['id_gedung' => 21, 'nama_ruangan' => 'Kelas TI-1B', 'kode_ruangan' => 'TS-202'],
            ['id_gedung' => 21, 'nama_ruangan' => 'Kelas TI-2A', 'kode_ruangan' => 'TS-203'],
            ['id_gedung' => 21, 'nama_ruangan' => 'Kelas TI-2B', 'kode_ruangan' => 'TS-204'],
            ['id_gedung' => 21, 'nama_ruangan' => 'Kelas TI-3A', 'kode_ruangan' => 'TS-301'],
            ['id_gedung' => 21, 'nama_ruangan' => 'Kelas TI-3B', 'kode_ruangan' => 'TS-302'],
            ['id_gedung' => 21, 'nama_ruangan' => 'Ruang Dosen TI', 'kode_ruangan' => 'TS-401'],
            ['id_gedung' => 21, 'nama_ruangan' => 'Ruang Kepala Jurusan', 'kode_ruangan' => 'TS-402'],
            ['id_gedung' => 21, 'nama_ruangan' => 'Ruang Rapat TI', 'kode_ruangan' => 'TS-403'],
            ['id_gedung' => 21, 'nama_ruangan' => 'Lab Multimedia', 'kode_ruangan' => 'TS-104'],
            ['id_gedung' => 21, 'nama_ruangan' => 'Lab Robotika', 'kode_ruangan' => 'TS-105'],
            ['id_gedung' => 21, 'nama_ruangan' => 'Ruang Server', 'kode_ruangan' => 'TS-106'],
            ['id_gedung' => 1, 'nama_ruangan' => 'Ruangan Direktur', 'kode_ruangan' => 'AA-01'],
        ]);
    }
}
