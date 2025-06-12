<?php

namespace App\Exports\Sheets;

use App\Models\LaporanKerusakan;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCharts;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;

class AnalisisTrenSheet implements FromQuery, WithTitle, WithHeadings, WithMapping, WithCharts, WithEvents
{
    private $startDate;
    private $endDate;
    private $rowCount = 0;
    // Definisikan nama sheet untuk digunakan kembali dan menghindari typo
    private $sheetName = 'Analisis Tren & Anggaran';

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function query()
    {
        return LaporanKerusakan::with(['fasilitas.ruangan.gedung', 'status', 'pelaporLaporan.user'])
            ->whereBetween('tanggal_lapor', [$this->startDate, $this->endDate])
            ->orderBy('tanggal_lapor', 'asc');
    }

    public function title(): string
    {
        return $this->sheetName;
    }

    public function headings(): array
    {
        return [
            'Fasilitas',
            'Lokasi (Gedung - Ruangan)',
            'Jumlah Kerusakan',
            'Deskripsi',
            'Pelapor',
            'Status',
            'Tanggal Lapor',
        ];
    }

    public function map($laporan): array
    {
        $this->rowCount++;
        $pelaporNames = $laporan->pelaporLaporan->pluck('user.nama')->implode(', ');

        return [
            $laporan->fasilitas->nama_fasilitas ?? 'N/A',
            ($laporan->fasilitas->ruangan->gedung->nama_gedung ?? 'N/A') . ' - ' . ($laporan->fasilitas->ruangan->nama_ruangan ?? 'N/A'),
            $laporan->jumlah_kerusakan,
            $laporan->deskripsi,
            $pelaporNames,
            $laporan->status->nama_status ?? 'N/A',
            $laporan->tanggal_lapor->translatedFormat('d M Y'),
        ];
    }

    public function charts()
    {
        // Data untuk Grafik Tren Harian
        $trenData = LaporanKerusakan::query()
            ->select(DB::raw('DATE(tanggal_lapor) as tanggal'), DB::raw('COUNT(*) as jumlah'))
            ->whereBetween('tanggal_lapor', [$this->startDate, $this->endDate])
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        // Data untuk Grafik per Gedung
        $gedungData = LaporanKerusakan::query()
            ->join('fasilitas', 'laporan_kerusakan.id_fasilitas', '=', 'fasilitas.id_fasilitas')
            ->join('ruangan', 'fasilitas.id_ruangan', '=', 'ruangan.id_ruangan')
            ->join('gedung', 'ruangan.id_gedung', '=', 'gedung.id_gedung')
            ->select('gedung.nama_gedung', DB::raw('COUNT(*) as jumlah'))
            ->whereBetween('laporan_kerusakan.tanggal_lapor', [$this->startDate, $this->endDate])
            ->groupBy('gedung.nama_gedung')
            ->orderBy('jumlah', 'desc')
            ->get();

        // --- PERBAIKAN DI BAWAH INI ---
        // Menggunakan single quotes (') untuk seluruh string agar PHP tidak mem-parsing '$' sebagai variabel.
        $dataSeriesLabels1 = [new DataSeriesValues('String', "'{$this->sheetName}'!\$K\$2", null, 1)];
        $xAxisTickValues1 = [new DataSeriesValues('String', "'{$this->sheetName}'!\$J\$3:\$J$" . (count($trenData) + 2), null, count($trenData))];
        $dataSeriesValues1 = [new DataSeriesValues('Number', "'{$this->sheetName}'!\$K\$3:\$K$" . (count($trenData) + 2), null, count($trenData))];

        $series1 = new DataSeries(
            DataSeries::TYPE_LINECHART,
            DataSeries::GROUPING_STANDARD,
            range(0, count($dataSeriesValues1) - 1),
            $dataSeriesLabels1,
            $xAxisTickValues1,
            $dataSeriesValues1
        );

        $plotArea1 = new PlotArea(null, [$series1]);
        $legend1 = new Legend(Legend::POSITION_TOP, null, false);
        $title1 = new Title('Grafik Tren Laporan Kerusakan per Hari');
        $yAxisLabel1 = new Title('Jumlah Laporan');

        $chart1 = new Chart(
            'chart1',
            $title1,
            $legend1,
            $plotArea1,
            true,
            0,
            null,
            $yAxisLabel1
        );
        $chart1->setTopLeftPosition('I' . ($this->rowCount + 5));
        $chart1->setBottomRightPosition('P' . ($this->rowCount + 20));

        // --- PERBAIKAN DI BAWAH INI ---
        $dataSeriesLabels2 = [new DataSeriesValues('String', "'{$this->sheetName}'!\$S\$2", null, 1)];
        $xAxisTickValues2 = [new DataSeriesValues('String', "'{$this->sheetName}'!\$R\$3:\$R$" . (count($gedungData) + 2), null, count($gedungData))];
        $dataSeriesValues2 = [new DataSeriesValues('Number', "'{$this->sheetName}'!\$S\$3:\$S$" . (count($gedungData) + 2), null, count($gedungData))];

        $series2 = new DataSeries(
            DataSeries::TYPE_BARCHART,
            DataSeries::GROUPING_CLUSTERED,
            range(0, count($dataSeriesValues2) - 1),
            $dataSeriesLabels2,
            $xAxisTickValues2,
            $dataSeriesValues2
        );
        $series2->setPlotDirection(DataSeries::DIRECTION_COL);

        $plotArea2 = new PlotArea(null, [$series2]);
        $legend2 = new Legend(Legend::POSITION_TOP, null, false);
        $title2 = new Title('Grafik Jumlah Laporan per Gedung');
        $yAxisLabel2 = new Title('Jumlah Laporan');

        $chart2 = new Chart(
            'chart2',
            $title2,
            $legend2,
            $plotArea2,
            true,
            0,
            null,
            $yAxisLabel2
        );
        $chart2->setTopLeftPosition('Q' . ($this->rowCount + 5));
        $chart2->setBottomRightPosition('X' . ($this->rowCount + 20));

        return [$chart1, $chart2];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Sembunyikan kolom data mentah untuk grafik
                $event->sheet->getColumnDimension('J')->setVisible(false);
                $event->sheet->getColumnDimension('K')->setVisible(false);
                $event->sheet->getColumnDimension('R')->setVisible(false);
                $event->sheet->getColumnDimension('S')->setVisible(false);

                // --- Menulis Data untuk Grafik Tren Harian (di kolom tersembunyi) ---
                $trenData = LaporanKerusakan::query()
                    ->select(DB::raw('DATE(tanggal_lapor) as tanggal'), DB::raw('COUNT(*) as jumlah'))
                    ->whereBetween('tanggal_lapor', [$this->startDate, $this->endDate])
                    ->groupBy('tanggal')
                    ->orderBy('tanggal', 'asc')
                    ->get();

                $event->sheet->setCellValue('J2', 'Tanggal');
                $event->sheet->setCellValue('K2', 'Jumlah Laporan Harian');
                $row = 3;
                foreach ($trenData as $data) {
                    // --- PERBAIKAN FORMAT TANGGAL DI SINI ---
                    // $data->tanggal adalah string, perlu di-parse menjadi Carbon object dulu
                    $event->sheet->setCellValue('J' . $row, \Carbon\Carbon::parse($data->tanggal)->translatedFormat('d M Y'));
                    $event->sheet->setCellValue('K' . $row, $data->jumlah);
                    $row++;
                }

                // --- Menulis Data untuk Grafik Gedung & Tabel Alokasi ---
                $gedungData = LaporanKerusakan::query()
                    ->join('fasilitas', 'laporan_kerusakan.id_fasilitas', '=', 'fasilitas.id_fasilitas')
                    ->join('ruangan', 'fasilitas.id_ruangan', '=', 'ruangan.id_ruangan')
                    ->join('gedung', 'ruangan.id_gedung', '=', 'gedung.id_gedung')
                    ->select('gedung.nama_gedung', DB::raw('COUNT(*) as jumlah'))
                    ->whereBetween('laporan_kerusakan.tanggal_lapor', [$this->startDate, $this->endDate])
                    ->groupBy('gedung.nama_gedung')
                    ->orderBy('jumlah', 'desc')
                    ->get();

                // Menulis data mentah untuk grafik gedung
                $event->sheet->setCellValue('R2', 'Gedung');
                $event->sheet->setCellValue('S2', 'Jumlah Laporan Gedung');
                $row = 3;
                foreach ($gedungData as $data) {
                    $event->sheet->setCellValue('R' . $row, $data->nama_gedung);
                    $event->sheet->setCellValue('S' . $row, $data->jumlah);
                    $row++;
                }

                // --- Menulis Tabel Alokasi Anggaran ---
                $startRowAlokasi = $this->rowCount + 5;
                $event->sheet->setCellValue('Y' . $startRowAlokasi, 'Tabel Alokasi Anggaran Perbaikan');
                $event->sheet->getStyle('Y' . $startRowAlokasi)->getFont()->setBold(true);

                $headerRow = $startRowAlokasi + 1;
                $event->sheet->setCellValue('Y' . $headerRow, 'Nama Gedung');
                $event->sheet->setCellValue('Z' . $headerRow, 'Jumlah Laporan');
                $event->sheet->setCellValue('AA' . $headerRow, 'Kategori Alokasi');
                $event->sheet->getStyle('Y' . $headerRow . ':AA' . $headerRow)->getFont()->setBold(true);

                $maxLaporan = $gedungData->max('jumlah') ?? 1;
                $currentRow = $headerRow + 1;
                foreach ($gedungData as $data) {
                    $kategori = 'Alokasi Rendah';
                    if ($data->jumlah > $maxLaporan * 0.66) {
                        $kategori = 'Alokasi Tinggi';
                    } elseif ($data->jumlah > $maxLaporan * 0.33) {
                        $kategori = 'Alokasi Sedang';
                    }
                    $event->sheet->setCellValue('Y' . $currentRow, $data->nama_gedung);
                    $event->sheet->setCellValue('Z' . $currentRow, $data->jumlah);
                    $event->sheet->setCellValue('AA' . $currentRow, $kategori);
                    $currentRow++;
                }
            },
        ];
    }
}
