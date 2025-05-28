<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanKerusakan;
use App\Models\PenugasanTeknisi;
use App\Models\KriteriaPenilaian;
use Illuminate\Support\Facades\DB;

class WaspasController extends Controller
{
    public function index()
    {
        $ranked = $this->getPrioritas();
        dd($ranked);
        // Kirim data ke views
        return view('laporan_prioritas.index', ['ranked' => $ranked]);
    }

    public function getPrioritas(){
        // Mengambil nilai min dan max untuk setiap kriteria
        $minMaxValues = DB::table('kriteria_penilaian')
            ->join('laporan_kerusakan', 'kriteria_penilaian.id_laporan', '=', 'laporan_kerusakan.id_laporan')
            ->where('laporan_kerusakan.id_status', [2,3])
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
        $weight = [0.35, 0.3, 0.1, 0.05, 0.2]; // Bobot kriteria

        // Step 1: Normalisasi matriks keputusan
        foreach ($alternatif as $item) {
            if ($item->laporan->id_status == 2 || $item->laporan->id_status == 3) {
                // Kriteria benefit (semakin besar semakin baik)
                $tingkat_kerusakan_norm = ($minMaxValues->max_tingkat_kerusakan - $minMaxValues->min_tingkat_kerusakan) != 0
                    ? ($item->tingkat_kerusakan - $minMaxValues->min_tingkat_kerusakan) / ($minMaxValues->max_tingkat_kerusakan - $minMaxValues->min_tingkat_kerusakan)
                    : 0;

                $frekuensi_digunakan_norm = ($minMaxValues->max_frekuensi_digunakan - $minMaxValues->min_frekuensi_digunakan) != 0
                    ? ($item->frekuensi_digunakan - $minMaxValues->min_frekuensi_digunakan) / ($minMaxValues->max_frekuensi_digunakan - $minMaxValues->min_frekuensi_digunakan)
                    : 0;

                $dampak_norm = ($minMaxValues->max_dampak - $minMaxValues->min_dampak) != 0
                    ? ($item->dampak - $minMaxValues->min_dampak) / ($minMaxValues->max_dampak - $minMaxValues->min_dampak)
                    : 0;

                $potensi_bahaya_norm = ($minMaxValues->max_potensi_bahaya - $minMaxValues->min_potensi_bahaya) != 0
                    ? ($item->potensi_bahaya - $minMaxValues->min_potensi_bahaya) / ($minMaxValues->max_potensi_bahaya - $minMaxValues->min_potensi_bahaya)
                    : 0;

                // Kriteria cost (semakin kecil semakin baik)
                $estimasi_biaya_norm = ($minMaxValues->max_estimasi_biaya - $minMaxValues->min_estimasi_biaya) != 0
                    ? ($minMaxValues->max_estimasi_biaya - $item->estimasi_biaya) / ($minMaxValues->max_estimasi_biaya - $minMaxValues->min_estimasi_biaya)
                    : 0;

                $normalizedMatrix[] = [
                    'id_laporan' => $item->laporan->id_laporan,
                    'deskripsi' => $item->laporan->deskripsi,
                    'status' => $item->laporan->status->nama_status,
                    'tingkat_kerusakan' => $tingkat_kerusakan_norm,
                    'frekuensi_digunakan' => $frekuensi_digunakan_norm,
                    'dampak' => $dampak_norm,
                    'estimasi_biaya' => $estimasi_biaya_norm,
                    'potensi_bahaya' => $potensi_bahaya_norm,
                ];
            }
        }

        // Step 2: Hitung Weighted Sum Model (WSM) dan Weighted Product Model (WPM)
        $results = [];
        foreach ($normalizedMatrix as $item) {
            // WSM Calculation
            $WSM = ($item['tingkat_kerusakan'] * $weight[0])
                + ($item['frekuensi_digunakan'] * $weight[1])
                + ($item['dampak'] * $weight[2])
                + ($item['estimasi_biaya'] * $weight[3])
                + ($item['potensi_bahaya'] * $weight[4]);

            // WPM Calculation (using logarithmic to avoid very small numbers)
            $WPM = pow($item['tingkat_kerusakan'], $weight[0])
                * pow($item['frekuensi_digunakan'], $weight[1])
                * pow($item['dampak'], $weight[2])
                * pow($item['estimasi_biaya'], $weight[3])
                * pow($item['potensi_bahaya'], $weight[4]);

            // Combine WSM and WPM (λ = 0.5 for equal importance)
            $lambda = 0.5;
            $Q = ($lambda * $WSM) + ((1 - $lambda) * $WPM);

            $results[] = [
                'id_laporan' => $item['id_laporan'],
                'deskripsi' => $item['deskripsi'],
                'status' => $item['status'],
                'WSM' => $WSM,
                'WPM' => $WPM,
                'Q' => $Q
            ];
        }

        // Step 3: Ranking berdasarkan nilai Q
        usort($results, function ($a, $b) {
            return $b['Q'] <=> $a['Q'];
        });

        // Tambahkan ranking
        $ranked = [];
        $rank = 1;
        foreach ($results as $item) {
            $item['rank'] = $rank++;
            $ranked[] = $item;
        }

        foreach ($ranked as &$item) {
            // Ambil data penugasan teknisi berdasarkan id_laporan
            $penugasan = PenugasanTeknisi::where('id_laporan', $item['id_laporan'])->first();

            // Tambahkan data penugasan ke masing-masing item hasil ranking
            $item['penugasan'] = $penugasan;
        }
        return $ranked;
    }
}
