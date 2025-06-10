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
            ['id_ruangan' => 1,  'kode_fasilitas' => 'AA-01-PROY',  'nama_fasilitas' => 'Proyektor',           'jumlah' => 1, 'created_at' => now()],
            ['id_ruangan' => 1,  'kode_fasilitas' => 'AA-01-LMRI',  'nama_fasilitas' => 'Lemari Arsip',        'jumlah' => 3, 'created_at' => now()],
            ['id_ruangan' => 1,  'kode_fasilitas' => 'AA-01-PRNT',  'nama_fasilitas' => 'Printer',             'jumlah' => 2, 'created_at' => now()],
            ['id_ruangan' => 2,  'kode_fasilitas' => 'AA-02-WHBD',  'nama_fasilitas' => 'Whiteboard',          'jumlah' => 1, 'created_at' => now()],
            ['id_ruangan' => 2,  'kode_fasilitas' => 'AA-02-MNTR',  'nama_fasilitas' => 'Monitor Presentasi',  'jumlah' => 1, 'created_at' => now()],
            ['id_ruangan' => 3,  'kode_fasilitas' => 'AA-03-MNTR',  'nama_fasilitas' => 'Monitor Presentasi',  'jumlah' => 1, 'created_at' => now()],
            ['id_ruangan' => 5,  'kode_fasilitas' => 'GP-01-SNDS',  'nama_fasilitas' => 'Sound System',        'jumlah' => 4, 'created_at' => now()],
            ['id_ruangan' => 62, 'kode_fasilitas' => 'TS-01-RTRM',  'nama_fasilitas' => 'Router Mikrotik',     'jumlah' => 1, 'created_at' => now()],
            ['id_ruangan' => 62, 'kode_fasilitas' => 'TS-01-SWHB',  'nama_fasilitas' => 'Switch Hub',          'jumlah' => 2, 'created_at' => now()],
            ['id_ruangan' => 63, 'kode_fasilitas' => 'TS-02-KRSI',  'nama_fasilitas' => 'Kursi Kuliah',        'jumlah' => 30, 'created_at' => now()],
            ['id_ruangan' => 63, 'kode_fasilitas' => 'TS-02-MJKL',  'nama_fasilitas' => 'Meja Kuliah',         'jumlah' => 30, 'created_at' => now()],
            ['id_ruangan' => 63, 'kode_fasilitas' => 'TS-02-ACAC',  'nama_fasilitas' => 'AC',                  'jumlah' => 30, 'created_at' => now()],
            ['id_ruangan' => 71, 'kode_fasilitas' => 'TS-10-DSPN',  'nama_fasilitas' => 'Dispenser',           'jumlah' => 2, 'created_at' => now()],
            ['id_ruangan' => 71, 'kode_fasilitas' => 'TS-10-ACAC',  'nama_fasilitas' => 'AC',                  'jumlah' => 30, 'created_at' => now()],
            ['id_ruangan' => 71, 'kode_fasilitas' => 'TS-10-LCDT',  'nama_fasilitas' => 'LCD TV',              'jumlah' => 1, 'created_at' => now()],
            ['id_ruangan' => 75, 'kode_fasilitas' => 'TS-14-KTRB',  'nama_fasilitas' => 'Kit Robotik',         'jumlah' => 15, 'created_at' => now()],
            ['id_ruangan' => 75, 'kode_fasilitas' => 'TS-14-PR3D',  'nama_fasilitas' => 'Printer 3D',          'jumlah' => 2, 'created_at' => now()],
            ['id_ruangan' => 76, 'kode_fasilitas' => 'TS-15-RKSR',  'nama_fasilitas' => 'Rak Server',          'jumlah' => 5, 'created_at' => now()],
            ['id_ruangan' => 76, 'kode_fasilitas' => 'TS-15-UPSS',  'nama_fasilitas' => 'UPS',                 'jumlah' => 4, 'created_at' => now()],
        ]);
    }
}
