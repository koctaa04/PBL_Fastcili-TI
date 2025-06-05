<?php

namespace Database\Seeders;

use App\Models\Fasilitas;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FasilitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Fasilitas::insert([
            ['id_ruangan' => 1,  'kode_fasilitas' => 'PCR1' ,'nama_fasilitas' => 'Proyektor', 'jumlah' => 2 , 'created_at' => now()],
            ['id_ruangan' => 1,  'kode_fasilitas' => 'PCR2' ,'nama_fasilitas' => 'PC Client', 'jumlah' => 20 , 'created_at' => now()],
            ['id_ruangan' => 1,  'kode_fasilitas' => 'PCR3' ,'nama_fasilitas' => 'PC Server', 'jumlah' => 1 , 'created_at' => now()],
            ['id_ruangan' => 2,  'kode_fasilitas' => 'PCR4' ,'nama_fasilitas' => 'Whiteboard', 'jumlah' => 1 , 'created_at' => now()],
            ['id_ruangan' => 2,  'kode_fasilitas' => 'PCR5' ,'nama_fasilitas' => 'AC', 'jumlah' => 2 , 'created_at' => now()],
            ['id_ruangan' => 3,  'kode_fasilitas' => 'PCR6' ,'nama_fasilitas' => 'Router Mikrotik', 'jumlah' => 1 , 'created_at' => now()],
            ['id_ruangan' => 3,  'kode_fasilitas' => 'PCR7' ,'nama_fasilitas' => 'Switch Hub', 'jumlah' => 2 , 'created_at' => now()],
            ['id_ruangan' => 4,  'kode_fasilitas' => 'PCR8' ,'nama_fasilitas' => 'Kursi Kuliah', 'jumlah' => 30 , 'created_at' => now()],
            ['id_ruangan' => 4,  'kode_fasilitas' => 'PCR9' ,'nama_fasilitas' => 'Meja Kuliah', 'jumlah' => 30 , 'created_at' => now()],
            ['id_ruangan' => 5,  'kode_fasilitas' => 'PCR10' ,'nama_fasilitas' => 'Speaker Aktif', 'jumlah' => 2 , 'created_at' => now()],
            ['id_ruangan' => 6,  'kode_fasilitas' => 'PCR11' ,'nama_fasilitas' => 'LCD TV', 'jumlah' => 1 , 'created_at' => now()],
            ['id_ruangan' => 7,  'kode_fasilitas' => 'PCR12' ,'nama_fasilitas' => 'Kipas Angin', 'jumlah' => 3 , 'created_at' => now()],
            ['id_ruangan' => 8,  'kode_fasilitas' => 'PCR13' ,'nama_fasilitas' => 'Stop Kontak Meja', 'jumlah' => 20 , 'created_at' => now()],
            ['id_ruangan' => 9,  'kode_fasilitas' => 'PCR14' ,'nama_fasilitas' => 'Kamera CCTV', 'jumlah' => 2 , 'created_at' => now()],
            ['id_ruangan' => 10, 'kode_fasilitas' => 'PCR15' , 'nama_fasilitas' => 'Lemari Arsip', 'jumlah' => 2 , 'created_at' => now()],
            ['id_ruangan' => 11, 'kode_fasilitas' => 'PCR16' , 'nama_fasilitas' => 'Meja Kerja', 'jumlah' => 1 , 'created_at' => now()],
            ['id_ruangan' => 12, 'kode_fasilitas' => 'PCR17' , 'nama_fasilitas' => 'Microphone Wireless', 'jumlah' => 1 , 'created_at' => now()],
            ['id_ruangan' => 13, 'kode_fasilitas' => 'PCR18' , 'nama_fasilitas' => 'Komputer Editing', 'jumlah' => 10 , 'created_at' => now()],
            ['id_ruangan' => 14, 'kode_fasilitas' => 'PCR19' , 'nama_fasilitas' => 'Kit Robotik', 'jumlah' => 15 , 'created_at' => now()],
            ['id_ruangan' => 14, 'kode_fasilitas' => 'PCR20' , 'nama_fasilitas' => 'Printer 3D', 'jumlah' => 1 , 'created_at' => now()],
            ['id_ruangan' => 15, 'kode_fasilitas' => 'PCR21' , 'nama_fasilitas' => 'Rak Server', 'jumlah' => 1 , 'created_at' => now()],
            ['id_ruangan' => 15, 'kode_fasilitas' => 'PCR22' , 'nama_fasilitas' => 'UPS', 'jumlah' => 2 , 'created_at' => now()],
            ['id_ruangan' => 16, 'kode_fasilitas' => 'PCR23' , 'nama_fasilitas' => 'AC', 'jumlah' => 1 , 'created_at' => now()],
        ]);
    }
}
