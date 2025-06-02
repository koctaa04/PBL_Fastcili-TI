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
use App\Models\PelaporLaporan;

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


    public function index(WaspasController $waspasController)
    {
        // Hitung jumlah laporan
        $jmlLaporan = LaporanKerusakan::count();
        $laporanAktif = LaporanKerusakan::whereIn('id_status', [1, 2, 3])->count();
        $laporanTerverifikasi = LaporanKerusakan::whereIn('id_status', [2])->count();
        $laporanSelesai = LaporanKerusakan::whereIn('id_status', [4])->count();

        // Data grafik laporan perbulan
        $tahun = now()->year;
        $laporanPerBulan = $this->getLaporanPerBulan($tahun);

        // Data pie chart status laporan
        $statusLaporan = $this->getStatusLaporan();

        // Data grafik laporan per gedung
        $laporanPerGedung = $this->getLaporanPerGedung();

        // Data ranking SPK
        $spkRank = $waspasController->getPrioritas();

        // Debug data yang dikirim ke view
        $debugData = [
            'jmlLaporan' => $jmlLaporan,
            'laporanAktif' => $laporanAktif,
            'laporanTerverifikasi' => $laporanTerverifikasi,
            'laporanSelesai' => $laporanSelesai,
            'laporanPerBulan' => $laporanPerBulan,
            'statusLaporan' => $statusLaporan,
            'laporanPerGedung' => $laporanPerGedung,
            'spkRank' => $spkRank,
        ];

        return view('pages.dashboard', $debugData);
    }

    // Helper method untuk data laporan perbulan
    protected function getLaporanPerBulan($tahun)
    {
        $bulanLengkap = collect([
            'Jan' => 0,
            'Feb' => 0,
            'Mar' => 0,
            'Apr' => 0,
            'May' => 0,
            'Jun' => 0,
            'Jul' => 0,
            'Aug' => 0,
            'Sep' => 0,
            'Oct' => 0,
            'Nov' => 0,
            'Dec' => 0
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

        return $bulanLengkap->merge($dataLaporan);
    }

    // Helper method untuk data status laporan
    protected function getStatusLaporan()
    {
        $statusCount = LaporanKerusakan::select('id_status', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('id_status')
            ->pluck('jumlah', 'id_status');

        $statusLabel = StatusLaporan::pluck('nama_status', 'id_status');

        return $statusLabel->mapWithKeys(function ($namaStatus, $id) use ($statusCount) {
            return [$namaStatus => $statusCount[$id] ?? 0];
        });
    }

    // Helper method untuk data laporan per gedung
    protected function getLaporanPerGedung()
    {
        $countPerGedung = DB::table('laporan_kerusakan')
            ->join('fasilitas', 'laporan_kerusakan.id_fasilitas', '=', 'fasilitas.id_fasilitas')
            ->join('ruangan', 'fasilitas.id_ruangan', '=', 'ruangan.id_ruangan')
            ->join('gedung', 'ruangan.id_gedung', '=', 'gedung.id_gedung')
            ->select('gedung.id_gedung', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('gedung.id_gedung')
            ->pluck('jumlah', 'gedung.id_gedung');

        $gedungLabels = Gedung::pluck('kode_gedung', 'id_gedung');

        return $gedungLabels->mapWithKeys(function ($kodeGedung, $id) use ($countPerGedung) {
            return [$kodeGedung => $countPerGedung[$id] ?? 0];
        });
    }

    public function pelapor()
    {
        $laporan = PelaporLaporan::where('id_user', Auth::id())->get();
        $status = PelaporLaporan::where('id_user', Auth::id())
            ->orderBy('created_at', 'desc')
            ->first();
        return view('pages.pelapor.index', compact('laporan', 'status'));
    }

    public function teknisi()
    {
        $riwayat = PenugasanTeknisi::where('id_user', Auth::id())->get();
        $penugasan = PenugasanTeknisi::with('laporan')
            ->where('id_user', Auth::id())
            ->orderBy('created_at', 'desc')
            ->first();
        return view('pages.teknisi.index', compact('riwayat', 'penugasan'));
    }
}
