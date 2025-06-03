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

        // Kirim data ke views
        return view('laporan_prioritas.index', ['ranked' => $ranked]);
    }

    public function getPrioritas()
    {
        $data = DB::table('laporan_kerusakan as l')
            ->join('kriteria_penilaian as k', 'l.id_laporan', '=', 'k.id_laporan')
            ->join('status_laporan as s', 'l.id_status', '=', 's.id_status') // JOIN status
            ->whereIn('l.id_status', [2, 3])
            ->select(
                'l.id_laporan',
                'l.deskripsi',         // ambil deskripsi laporan
                's.nama_status',       // ambil nama status dari join status_laporan
                'k.tingkat_kerusakan',
                'k.frekuensi_digunakan',
                'k.dampak',
                'k.estimasi_biaya',
                'k.potensi_bahaya'
            )
            ->get();

        // Konversi ke array
        $dataArray = $data->map(function ($item) {
            return (array) $item;
        })->toArray();

        // Kriteria dan Bobot
        $kriteria = [
            'tingkat_kerusakan' => ['weight' => 0.3, 'type' => 'benefit'],
            'frekuensi_digunakan' => ['weight' => 0.1, 'type' => 'benefit'],
            'dampak' => ['weight' => 0.05, 'type' => 'benefit'],
            'estimasi_biaya' => ['weight' => 0.35, 'type' => 'cost'],
            'potensi_bahaya' => ['weight' => 0.2, 'type' => 'benefit'],
        ];

        // Hitung max/min
        $normalMatrix = [];
        $maxMin = [];

        foreach ($kriteria as $key => $meta) {
            $column = array_column($dataArray, $key);
            $maxMin[$key] = empty($column) ? 1 : ($meta['type'] === 'benefit' ? max($column) : min($column));
        }

        // Normalisasi matriks
        foreach ($dataArray as $row) {
            $id = $row['id_laporan'];
            $normalMatrix[$id] = [];

            foreach ($kriteria as $key => $meta) {
                $normalMatrix[$id][$key] = $meta['type'] === 'benefit'
                    ? $row[$key] / $maxMin[$key]
                    : $maxMin[$key] / $row[$key];
            }
        }

        // Hitung WSM, WPM, dan WASPAS
        $results = [];

        foreach ($normalMatrix as $id => $nilai) {
            $WSM = 0;
            $WPM = 1;

            foreach ($nilai as $key => $val) {
                $bobot = $kriteria[$key]['weight'];
                $WSM += $val * $bobot;
                $WPM *= pow($val, $bobot);
            }

            $Q = 0.5 * $WSM + 0.5 * $WPM;

            // Ambil deskripsi dan status dari dataArray (cari berdasarkan id)
            $original = collect($dataArray)->firstWhere('id_laporan', $id);

            $results[] = [
                'id_laporan' => $original['id_laporan'],
                'deskripsi' => $original['deskripsi'] ?? null,
                'status' => $original['nama_status'] ?? null,
                'Q' => round($Q, 4),
            ];
        }

        // Ranking berdasarkan nilai Q
        usort($results, fn($a, $b) => $b['Q'] <=> $a['Q']);

        $ranked = [];
        $rank = 1;
        foreach ($results as $item) {
            $item['rank'] = $rank++;

            // Tambahkan data penugasan teknisi
            $penugasan = PenugasanTeknisi::where('id_laporan', $item['id_laporan'])->first();
            $item['penugasan'] = $penugasan;

            $ranked[] = $item;
        }

        return $ranked;
    }
}
