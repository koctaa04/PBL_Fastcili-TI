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
                'id_user' => 10,
                'id_laporan' => 4, // Whiteboard retak
                'deskripsi_tambahan' => 'Papn sudah di lem tapi tetap retak.',
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
            [
                'id_user' => 20,
                'id_laporan' => 1, // Proyektor tidak bisa menyambung
                'deskripsi_tambahan' => 'Tampilan hanya muncul sebentar lalu hilang.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 21,
                'id_laporan' => 1, // Proyektor tidak bisa menyambung
                'deskripsi_tambahan' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 22,
                'id_laporan' => 1, // Proyektor tidak bisa menyambung
                'deskripsi_tambahan' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 21,
                'id_laporan' => 2, // Lemari Arsip pintunya rusak
                'deskripsi_tambahan' => 'Pintu lemari sudah tidak bisa ditutup rapat.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 22,
                'id_laporan' => 3, // Printer tidak bisa mencetak
                'deskripsi_tambahan' => 'Kertas selalu macet saat proses mencetak.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 23,
                'id_laporan' => 4, // Whiteboard retak
                'deskripsi_tambahan' => 'Retakan melebar ke tengah papan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 24,
                'id_laporan' => 5, // AC mengeluarkan bunyi keras
                'deskripsi_tambahan' => 'Bunyi keras terdengar saat dinyalakan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 20,
                'id_laporan' => 6, // Printer 3D nozzle tersumbat
                'deskripsi_tambahan' => 'Hasil cetak menjadi berantakan dan tidak presisi.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 21,
                'id_laporan' => 7, // Kit Robotik tidak menyala
                'deskripsi_tambahan' => 'Sudah coba ganti kabel dan baterai, masih mati.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 22,
                'id_laporan' => 8, // Dispenser tidak keluar air panas
                'deskripsi_tambahan' => 'Lampu indikator panas tidak menyala.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 23,
                'id_laporan' => 9, // Monitor tidak menampilkan gambar
                'deskripsi_tambahan' => 'Sudah ganti kabel VGA/HDMI tetap blank.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 24,
                'id_laporan' => 10, // Router tidak memancarkan sinyal
                'deskripsi_tambahan' => 'Lampu indikator Wi-Fi mati total.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 20,
                'id_laporan' => 11, // CCTV mati total
                'deskripsi_tambahan' => 'Tidak bisa dipantau dari ruang kontrol.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 21,
                'id_laporan' => 12, // Mic tidak menangkap suara
                'deskripsi_tambahan' => 'Mic menyala tapi tidak mengeluarkan suara.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 22,
                'id_laporan' => 13, // AC tidak dingin
                'deskripsi_tambahan' => 'Udara keluar tapi tetap panas.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 22,
                'id_laporan' => 14, // Speaker Rusak
                'deskripsi_tambahan' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
