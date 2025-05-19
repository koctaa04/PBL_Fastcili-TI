<?php

namespace App\Http\Controllers;

use App\Models\LaporanKerusakan;
use Illuminate\Support\Facades\Auth;

use App\Models\Gedung;
use App\Models\StatusLaporan;
use App\Models\PenugasanTeknisi;
use App\Models\KriteriaPenilaian;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $jmlLaporan = LaporanKerusakan::count();
        $laporanAktif = LaporanKerusakan::whereIn('id_status', [1, 2, 3])->count();
        $laporanTerverifikasi = LaporanKerusakan::whereIn('id_status', [2])->count();
        $laporanSelesai = LaporanKerusakan::whereIn('id_status', [4])->count();

        //grafik laporan perbulan
        $tahun = now()->year;

        $bulanLengkap = collect([
            'Jan' => 0, 'Feb' => 0, 'Mar' => 0, 'Apr' => 0,
            'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0,
            'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0
        ]);

        $dataLaporan = LaporanKerusakan::selectRaw('MONTH(tanggal_lapor) as bulan, COUNT(*) as jumlah')
            ->whereYear('tanggal_lapor', $tahun)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get()
            ->pluck('jumlah', 'bulan')
            ->mapWithKeys(function ($jumlah, $bulanAngka) {
                $namaBulan = Carbon::create()->month($bulanAngka)->format('M');
                return [$namaBulan => $jumlah];
            });

        $laporanPerBulan = $bulanLengkap->merge($dataLaporan);

        //pie chart status laporan
        $statusLaporan = LaporanKerusakan::select('id_status', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('id_status')
            ->pluck('jumlah', 'id_status');

        $statusLabel = StatusLaporan::pluck('nama_status', 'id_status');

        $statusLaporan = $statusLabel->mapWithKeys(function ($statusLabel, $id) use ($statusLaporan) {
            return [$statusLabel => $statusLaporan[$id] ?? 0];
        });

        //grafik laproan pergedung
        $laporanPerGedung = DB::table('laporan_kerusakan')
            ->join('fasilitas', 'laporan_kerusakan.id_fasilitas', '=', 'fasilitas.id_fasilitas')
            ->join('ruangan', 'fasilitas.id_ruangan', '=', 'ruangan.id_ruangan')
            ->join('gedung', 'ruangan.id_gedung', '=', 'gedung.id_gedung')
            ->select('gedung.id_gedung', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('gedung.id_gedung')
            ->pluck('jumlah', 'gedung.id_gedung');

        $gedungLabels = Gedung::pluck('kode_gedung', 'id_gedung');

        $laporanPerGedung = $gedungLabels->mapWithKeys(function ($kode, $id) use ($laporanPerGedung) {
            return [$kode => $laporanPerGedung[$id] ?? 0];
        });

        $spkRank = $this->getRanked();

        return view('pages.dashboard', [
            'jmlLaporan' => $jmlLaporan,
            'laporanAktif' => $laporanAktif,
            'laporanTerverifikasi' => $laporanTerverifikasi,
            'laporanSelesai' => $laporanSelesai,
            'laporanPerBulan' => $laporanPerBulan,
            'statusLaporan' => $statusLaporan,
            'laporanPerGedung' => $laporanPerGedung,
            'spkRank' => $spkRank,
        ]);
    }

    public function getRanked()
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
            $normalizedMatrix[] = [
                'id_laporan' => $item->laporan->id_laporan,
                'deskripsi' => $item->laporan->deskripsi,
                'status' => $item->laporan->status->nama_status,
                'tingkat_kerusakan' => ($item->tingkat_kerusakan - $minMaxValues->min_tingkat_kerusakan) / ($minMaxValues->max_tingkat_kerusakan - $minMaxValues->min_tingkat_kerusakan),
                'frekuensi_digunakan' => ($item->frekuensi_digunakan - $minMaxValues->min_frekuensi_digunakan) / ($minMaxValues->max_frekuensi_digunakan - $minMaxValues->min_frekuensi_digunakan),
                'dampak' => ($item->dampak - $minMaxValues->min_dampak) / ($minMaxValues->max_dampak - $minMaxValues->min_dampak),
                'estimasi_biaya' => ($item->estimasi_biaya - $minMaxValues->max_estimasi_biaya) / ($minMaxValues->min_estimasi_biaya - $minMaxValues->max_estimasi_biaya),
                'potensi_bahaya' => ($item->potensi_bahaya - $minMaxValues->min_potensi_bahaya) / ($minMaxValues->max_potensi_bahaya - $minMaxValues->min_potensi_bahaya),
            ];
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

        return $ranked;
    }


    public function pelapor()
    {
        $laporan = LaporanKerusakan::where('id_user', Auth::id())->get();
        $status = LaporanKerusakan::where('id_user', Auth::id())
            ->orderBy('created_at', 'desc')
            ->first();
        return view('pages.pelapor.index', compact('laporan', 'status'));
    }
}
