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
            ['id_gedung' => 1, 'nama_ruangan' => 'Lab Jaringan', 'kode_ruangan' => 'AA'],
            ['id_gedung' => 1, 'nama_ruangan' => 'Lab RPL', 'kode_ruangan' => 'AA'],
            ['id_gedung' => 2, 'nama_ruangan' => 'Lab Basis Data', 'kode_ruangan' => 'AA'],
            ['id_gedung' => 2, 'nama_ruangan' => 'Kelas TI-1A', 'kode_ruangan' => 'AA'],
            ['id_gedung' => 3, 'nama_ruangan' => 'Kelas TI-1B', 'kode_ruangan' => 'AA'],
        ]);
    }
}
