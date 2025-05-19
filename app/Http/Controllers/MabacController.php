<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanKerusakan;
use App\Models\PenugasanTeknisi;
use App\Models\KriteriaPenilaian;
use Illuminate\Support\Facades\DB;

class MabacController extends Controller
{
    public function index()
    {
        // Mengambil nilai min dan max untuk setiap kriteria
        $minMaxValues = DB::table('kriteria_penilaian')
            ->select(
                DB::raw('MIN(tingkat_kerusakan) as min_tingkat_kerusakan'),
                DB::raw('MAX(tingkat_kerusakan) as max_tingkat_kerusakan'),
                DB::raw('MIN(frekuensi_digunakan) as min_frekuensi_digunakan'),
                DB::raw('MAX(frekuensi_digunakan) as max_frekuensi_digunakan'),
                DB::raw('MIN(dampak) as min_dampak'),
                DB::raw('MAX(dampak) as max_dampak'),
                DB::raw('MIN(estimasi_biaya) as min_estimasi_biaya'),
                DB::raw('MAX(estimasi_biaya) as max_estimasi_biaya'),
                DB::raw('MIN(potensi_bahaya) as min_potensi_bahaya'),
                DB::raw('MAX(potensi_bahaya) as max_potensi_bahaya')
            )
            ->first();

        // Mengambil data alternatif
        $alternatif = KriteriaPenilaian::with('laporan')->get();
        $laporan = LaporanKerusakan::with('status')->get();

        $normalizedMatrix = [];
        $weightedMatrix = [];
        $weight = [0.35, 0.3, 0.1, 0.05, 0.2];

        // Melakukan Normalisasi (X)
        foreach ($alternatif as $item) {
            if ($item->laporan->id_status == 2) {
                $tingkat_kerusakan_range = $minMaxValues->max_tingkat_kerusakan - $minMaxValues->min_tingkat_kerusakan;
                $frekuensi_digunakan_range = $minMaxValues->max_frekuensi_digunakan - $minMaxValues->min_frekuensi_digunakan;
                $dampak_range = $minMaxValues->max_dampak - $minMaxValues->min_dampak;
                $estimasi_biaya_range = $minMaxValues->min_estimasi_biaya - $minMaxValues->max_estimasi_biaya;
                $potensi_bahaya_range = $minMaxValues->max_potensi_bahaya - $minMaxValues->min_potensi_bahaya;

                $normalizedMatrix[] = [
                    'id_laporan' => $item->laporan->id_laporan,
                    'deskripsi' => $item->laporan->deskripsi,
                    'status' => $item->laporan->status->nama_status,
                    'tingkat_kerusakan' => $tingkat_kerusakan_range != 0 ? ($item->tingkat_kerusakan - $minMaxValues->min_tingkat_kerusakan) / $tingkat_kerusakan_range : 0,
                    'frekuensi_digunakan' => $frekuensi_digunakan_range != 0 ? ($item->frekuensi_digunakan - $minMaxValues->min_frekuensi_digunakan) / $frekuensi_digunakan_range : 0,
                    'dampak' => $dampak_range != 0 ? ($item->dampak - $minMaxValues->min_dampak) / $dampak_range : 0,
                    'estimasi_biaya' => $estimasi_biaya_range != 0 ? ($item->estimasi_biaya - $minMaxValues->max_estimasi_biaya) / $estimasi_biaya_range : 0,
                    'potensi_bahaya' => $potensi_bahaya_range != 0 ? ($item->potensi_bahaya - $minMaxValues->min_potensi_bahaya) / $potensi_bahaya_range : 0,
                ];
            }
        }

        // Membuat Matriks Berbobot (V)
        foreach ($normalizedMatrix as $i) {
            $weightedMatrix[] = [
                'id_laporan' => $i['id_laporan'],
                'deskripsi' => $i['deskripsi'],
                'status' => $i['status'],
                'tingkat_kerusakan' => ($i['tingkat_kerusakan'] * $weight[0]) + $weight[0],
                'frekuensi_digunakan' => ($i['frekuensi_digunakan'] * $weight[1]) + $weight[1],
                'dampak' => ($i['dampak'] * $weight[2]) + $weight[2],
                'estimasi_biaya' => ($i['estimasi_biaya'] * $weight[3]) + $weight[3],
                'potensi_bahaya' => ($i['potensi_bahaya'] * $weight[4]) + $weight[4]
            ];
        }

        $G = [
            'tingkat_kerusakan' => 1,
            'frekuensi_digunakan' => 1,
            'dampak' => 1,
            'estimasi_biaya' => 1,
            'potensi_bahaya' => 1,
        ];

        // Membuat matriks area perkiraan batas (G)
        foreach ($weightedMatrix as $item) {
            $G['tingkat_kerusakan'] *= $item['tingkat_kerusakan'];
            $G['frekuensi_digunakan'] *= $item['frekuensi_digunakan'];
            $G['dampak'] *= $item['dampak'];
            $G['estimasi_biaya'] *= $item['estimasi_biaya'];
            $G['potensi_bahaya'] *= $item['potensi_bahaya'];
        }

        $pangkat = 1 / count($weightedMatrix);

        foreach ($G as $key => $value) {
            $G[$key] = pow($value, $pangkat);
        }

        foreach ($weightedMatrix as $i) {
            $perkiraanBatas[] = [
                'id_laporan' => $i['id_laporan'],
                'deskripsi' => $i['deskripsi'],
                'status' => $i['status'],
                'tingkat_kerusakan' => $i['tingkat_kerusakan'] - $G['tingkat_kerusakan'],
                'frekuensi_digunakan' => $i['frekuensi_digunakan'] - $G['frekuensi_digunakan'],
                'dampak' => $i['dampak'] - $G['dampak'],
                'estimasi_biaya' => $i['estimasi_biaya'] - $G['estimasi_biaya'],
                'potensi_bahaya' => $i['potensi_bahaya'] - $G['potensi_bahaya']
            ];
        }

        foreach ($perkiraanBatas as $k) {
            $totalPerkiraan = $k['tingkat_kerusakan'] + $k['frekuensi_digunakan'] + $k['dampak'] + $k['estimasi_biaya'] + $k['potensi_bahaya'];

            $S[] = [
                'id_laporan' => $k['id_laporan'],
                'deskripsi' => $k['deskripsi'],
                'status' => $k['status'],
                'S' => $totalPerkiraan
            ];
        }

        usort($S, function ($a, $b) {
            return $b['S'] <=> $a['S'];
        });

        // Tambahkan ranking
        $ranked = [];
        $rank = 1;
        foreach ($S as $item) {
            $item['rank'] = $rank++;
            $ranked[] = $item;
        }

        foreach ($ranked as &$item) {
            // Ambil data penugasan teknisi berdasarkan id_laporan
            $penugasan = PenugasanTeknisi::where('id_laporan', $item['id_laporan'])->first();

            // Tambahkan data penugasan ke masing-masing item hasil ranking
            $item['penugasan'] = $penugasan;
        }

        // Kirim data ke views
        return view('laporan_prioritas.index', ['ranked' => $ranked]);
    }
}
