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
    ['nama_gedung' => 'Kantor Pusat', 'kode_gedung' => 'AA', 'deskripsi' => 'Gedung utama yang menjadi pusat administrasi dan manajemen kampus.'],
    ['nama_gedung' => 'Jurusan Administrasi Niaga', 'kode_gedung' => 'AB', 'deskripsi' => 'Gedung tempat berlangsungnya kegiatan belajar mengajar untuk Jurusan Administrasi Niaga.'],
    ['nama_gedung' => 'Laboratorium Jurusan Administrasi Niaga dan Akuntansi', 'kode_gedung' => 'AC', 'deskripsi' => 'Laboratorium pendukung praktikum mahasiswa jurusan Administrasi Niaga dan Akuntansi.'],
    ['nama_gedung' => 'Jurusan Akuntansi', 'kode_gedung' => 'AD', 'deskripsi' => 'Gedung yang digunakan untuk perkuliahan dan kegiatan akademik Jurusan Akuntansi.'],
    ['nama_gedung' => 'Gedung Kuliah Jurusan Administrasi Niaga dan Akuntansi', 'kode_gedung' => 'AE', 'deskripsi' => 'Gedung kuliah bersama untuk mahasiswa Administrasi Niaga dan Akuntansi.'],
    ['nama_gedung' => 'Gedung Kuliah Program 1 Tahun', 'kode_gedung' => 'AF', 'deskripsi' => 'Gedung khusus untuk pelaksanaan program pendidikan satu tahun.'],
    ['nama_gedung' => 'Jurusan Teknik Elektro dan Teknik Sipil', 'kode_gedung' => 'AG', 'deskripsi' => 'Gedung gabungan antara jurusan Teknik Elektro dan Teknik Sipil.'],
    ['nama_gedung' => 'Jurusan Teknik Telekomunikasi dan Manajemen Informatika', 'kode_gedung' => 'AH', 'deskripsi' => 'Gedung utama untuk kegiatan perkuliahan jurusan Teknik Telekomunikasi dan MI.'],
    ['nama_gedung' => 'Bengkel Laboratorium Teknik Telekomunikasi', 'kode_gedung' => 'AI', 'deskripsi' => 'Fasilitas laboratorium untuk praktik Teknik Telekomunikasi.'],
    ['nama_gedung' => 'Bengkel Laboratorium Teknik Elektronika', 'kode_gedung' => 'AJ', 'deskripsi' => 'Laboratorium praktik mahasiswa jurusan Teknik Elektronika.'],
    ['nama_gedung' => 'Bengkel Laboratorium Teknik Listrik', 'kode_gedung' => 'AK', 'deskripsi' => 'Laboratorium khusus Teknik Listrik untuk pelaksanaan praktikum.'],
    ['nama_gedung' => 'Laboratorium Broadcasting', 'kode_gedung' => 'AL', 'deskripsi' => 'Gedung fasilitas laboratorium untuk praktik broadcasting.'],
    ['nama_gedung' => 'Aula Pertamina', 'kode_gedung' => 'AM', 'deskripsi' => 'Aula besar yang biasa digunakan untuk seminar, wisuda, dan kegiatan kampus lainnya.'],
    ['nama_gedung' => 'UPT Pengembangan Pembelajaran', 'kode_gedung' => 'AN', 'deskripsi' => 'Unit yang berfungsi dalam pengembangan sistem dan teknologi pembelajaran kampus.'],
    ['nama_gedung' => 'Gedung Kuliah Jurusan Teknik Kimia', 'kode_gedung' => 'AO', 'deskripsi' => 'Gedung untuk perkuliahan jurusan Teknik Kimia.'],
    ['nama_gedung' => 'Laboratorium Biodiesel', 'kode_gedung' => 'AP', 'deskripsi' => 'Laboratorium khusus penelitian dan pengembangan biodiesel.'],
    ['nama_gedung' => 'Laboratorium Jurusan Teknik Kimia', 'kode_gedung' => 'AQ', 'deskripsi' => 'Laboratorium untuk kegiatan praktikum Teknik Kimia.'],
    ['nama_gedung' => 'Poliklinik', 'kode_gedung' => 'AR', 'deskripsi' => 'Fasilitas kesehatan kampus yang melayani mahasiswa dan staf.'],
    ['nama_gedung' => 'Garasi dan Sekretariat Bersama', 'kode_gedung' => 'AS', 'deskripsi' => 'Gedung untuk kendaraan operasional dan sekretariat beberapa unit kampus.'],
    ['nama_gedung' => 'Teknik Sipil', 'kode_gedung' => 'TS', 'deskripsi' => 'Gedung utama untuk kegiatan belajar mengajar Jurusan Teknik Sipil.'],
    ['nama_gedung' => 'Teknik Mesin', 'kode_gedung' => 'TM', 'deskripsi' => 'Gedung utama untuk kegiatan belajar mengajar Jurusan Teknik Mesin.'],
]);

        
    }
}
