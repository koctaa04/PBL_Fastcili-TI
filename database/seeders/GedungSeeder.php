<?php

namespace Database\Seeders;

use App\Models\Gedung;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GedungSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Gedung::insert([
            ['nama_gedung' => 'Kantor Pusat', 'kode_gedung' => 'AA'],
            ['nama_gedung' => 'Jurusan Administrasi Niaga', 'kode_gedung' => 'AB'],
            ['nama_gedung' => 'Laboratorium Jurusan Administrasi Niaga dan Akuntansi', 'kode_gedung' => 'AC'],
            ['nama_gedung' => 'Jurusan Akuntansi', 'kode_gedung' => 'AD'],
            ['nama_gedung' => 'Gedung Kuliah Jurusan Administrasi Niaga dan Akuntansi', 'kode_gedung' => 'AE'],
            ['nama_gedung' => 'Gedung Kuliah Program 1 Tahun', 'kode_gedung' => 'AF'],
            ['nama_gedung' => 'Jurusan Teknik Elektro dan Teknik Sipil', 'kode_gedung' => 'AG'],
            ['nama_gedung' => 'Jurusan Teknik Telekomunikasi dan Manajemen Informatika', 'kode_gedung' => 'AH'],
            ['nama_gedung' => 'Bengkel Laboratorium Teknik Telekomunikasi', 'kode_gedung' => 'AI'],
            ['nama_gedung' => 'Bengkel Laboratorium Teknik Elektronika', 'kode_gedung' => 'AJ'],
            ['nama_gedung' => 'Bengkel Laboratorium Teknik Listrik', 'kode_gedung' => 'AK'],
            ['nama_gedung' => 'Laboratorium Broadcasting', 'kode_gedung' => 'AL'],
            ['nama_gedung' => 'Aula Pertamina', 'kode_gedung' => 'AM'],
            ['nama_gedung' => 'UPT Pengembangan Pembelajaran', 'kode_gedung' => 'AN'],
            ['nama_gedung' => 'Gedung Kuliah Jurusan Teknik Kimia', 'kode_gedung' => 'AO'],
            ['nama_gedung' => 'Laboratorium Biodiesel', 'kode_gedung' => 'AP'],
            ['nama_gedung' => 'Laboratorium Jurusan Teknik Kimia', 'kode_gedung' => 'AQ'],
            ['nama_gedung' => 'Poliklinik', 'kode_gedung' => 'AR'],
            ['nama_gedung' => 'Garasi dan Sekretariat Bersama', 'kode_gedung' => 'AS'],
            ['nama_gedung' => 'Teknik Sipil', 'kode_gedung' => 'TS'],
            ['nama_gedung' => 'Teknik Mesin', 'kode_gedung' => 'TM'],
        ]);
        
    }
}
