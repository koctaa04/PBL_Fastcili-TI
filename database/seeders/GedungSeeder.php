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
            ['nama_gedung' => 'Graha Polinema', 'kode_gedung' => 'GP', 'deskripsi' => 'Gedung tempat diadakannya acara yang melibatkan peserta ataupun tamu dalam jumlah besar.'],
            ['nama_gedung' => 'Gedung Kuliah Administrasi Niaga', 'kode_gedung' => 'AB', 'deskripsi' => 'Gedung tempat berlangsungnya kegiatan belajar mengajar untuk Jurusan Administrasi Niaga.'],
            ['nama_gedung' => 'Gedung Kuliah Akuntansi', 'kode_gedung' => 'AD', 'deskripsi' => 'Gedung yang digunakan untuk perkuliahan dan kegiatan akademik Jurusan Akuntansi.'],
            ['nama_gedung' => 'Gedung Kuliah Administrasi Niaga dan Akuntansi', 'kode_gedung' => 'AE', 'deskripsi' => 'Gedung kuliah bersama untuk mahasiswa Administrasi Niaga dan Akuntansi.'],
            ['nama_gedung' => 'Gedung Kuliah Program 1 Tahun dan Pusat Komunikasi', 'kode_gedung' => 'AF', 'deskripsi' => 'Gedung khusus untuk pelaksanaan program pendidikan satu tahun dan juga berfungsi sebagai Gedung Pusat Komunikasi (PUSKOM).'],
            ['nama_gedung' => 'Laboratorium Teknik Elektro', 'kode_gedung' => 'AG', 'deskripsi' => 'Laboratorium khusus Jurusan Teknik Elektro untuk pelaksanaan kegiatan belajar mengajar dan penelitian.'],
            ['nama_gedung' => 'Gedung Kuliah Teknik Elektro', 'kode_gedung' => 'AH', 'deskripsi' => 'Gedung utama untuk kegiatan perkuliahan Jurusan Teknik Elektro dan juga auditorium untuk acara tertentu.'],
            ['nama_gedung' => 'Bengkel Laboratorium Teknik Telekomunikasi', 'kode_gedung' => 'AI', 'deskripsi' => 'Fasilitas laboratorium untuk praktik Teknik Telekomunikasi.'],
            ['nama_gedung' => 'Bengkel Laboratorium Teknik Elektronika', 'kode_gedung' => 'AJ', 'deskripsi' => 'Laboratorium praktikum untuk mahasiswa Teknik Elektronika.'],
            ['nama_gedung' => 'Bengkel Laboratorium Teknik Listrik', 'kode_gedung' => 'AK', 'deskripsi' => 'Laboratorium khusus mahasiswa Teknik Listrik untuk pelaksanaan praktikum.'],
            ['nama_gedung' => 'Laboratorium Broadcasting', 'kode_gedung' => 'AL', 'deskripsi' => 'Gedung fasilitas laboratorium untuk praktik broadcasting khusus mahasiswa Jurusan Teknik Elektro.'],
            ['nama_gedung' => 'Aula Pertamina', 'kode_gedung' => 'AM', 'deskripsi' => 'Aula besar yang biasa digunakan untuk seminar dan kegiatan kampus lainnya.'],
            ['nama_gedung' => 'Gedung Arsip', 'kode_gedung' => 'AU', 'deskripsi' => 'Gedung yang berfungsi sebagai tempat penyimpanan arsip dan dokumen kampus.'],
            ['nama_gedung' => 'Gedung Kuliah Teknik Kimia', 'kode_gedung' => 'AO', 'deskripsi' => 'Gedung untuk perkuliahan Jurusan Teknik Kimia.'],
            ['nama_gedung' => 'Laboratorium Biodiesel', 'kode_gedung' => 'AP', 'deskripsi' => 'Laboratorium khusus penelitian dan pengembangan biodiesel.'],
            ['nama_gedung' => 'Laboratorium Teknik Kimia', 'kode_gedung' => 'AQ', 'deskripsi' => 'Laboratorium untuk kegiatan praktikum mahasiswa Jurusan Teknik Kimia.'],
            ['nama_gedung' => 'Poliklinik', 'kode_gedung' => 'AR', 'deskripsi' => 'Fasilitas kesehatan kampus yang melayani mahasiswa dan staf Politeknik Negeri Malang'],
            ['nama_gedung' => 'Garasi dan Sekretariat Bersama', 'kode_gedung' => 'AS', 'deskripsi' => 'Gedung untuk kendaraan operasional dan dinas, serta sebagai sekretariat beberapa organisasi, himpunan, dan unit kampus.'],
            ['nama_gedung' => 'Parkiran Utama, Kantin, dan Gym', 'kode_gedung' => 'AX', 'deskripsi' => 'Gedung tempat kendaraan dari mahasiswa, dosen dan juga pegawai parkir. Juga terdapat kantin, dan fasilitas olahraga di lantai 2.'],
            ['nama_gedung' => 'Gedung Kuliah Teknik Sipil dan Teknologi Informasi', 'kode_gedung' => 'TS', 'deskripsi' => 'Gedung utama untuk kegiatan belajar mengajar Jurusan Teknik Sipil dan Teknologi Informasi dan auditorium.'],
            ['nama_gedung' => 'Gedung Kuliah Teknik Mesin', 'kode_gedung' => 'TM', 'deskripsi' => 'Gedung utama yang terdapat kelas, bengkel, dan juga hanggar untuk kegiatan belajar mengajar Jurusan Teknik Mesin.'],
        ]);
    }
}
