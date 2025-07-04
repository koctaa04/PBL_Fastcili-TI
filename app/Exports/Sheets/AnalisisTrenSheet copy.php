<?php

namespace App\Exports\Sheets;

use App\Models\LaporanKerusakan;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Worksheet\Table;
use PhpOffice\PhpSpreadsheet\Worksheet\Table\TableStyle;

class AnalisisTrenSheet implements FromQuery, WithTitle, WithHeadings, WithMapping, WithEvents, ShouldAutoSize, WithStyles
{
    private $startDate;
    private $endDate;
    private $rowCount = 0;
    private $sheetName = 'Analisis Tren & Anggaran';

    private $trenLastRow;
    private $gedungLastRow;

    private $trenRowStart = 3;
    private $gedungRowStart = 3;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function query()
    {
        $q = LaporanKerusakan::whereBetween('tanggal_lapor', [$this->startDate, $this->endDate]);
        $this->rowCount = $q->count();
        return $q->orderBy('tanggal_lapor');
    }

    public function title(): string
    {
        return $this->sheetName;
    }

    public function headings(): array
    {
        return ['Fasilitas', 'Lokasi (Gedung - Ruangan)', 'Jumlah Kerusakan', 'Deskripsi', 'Pelapor', 'Status', 'Tanggal Lapor'];
    }

    public function map($laporan): array
    {
        return [
            $laporan->fasilitas->nama_fasilitas ?? 'N/A',
            ($laporan->fasilitas->ruangan->gedung->nama_gedung ?? 'N/A') . ' - ' . ($laporan->fasilitas->ruangan->nama_ruangan ?? 'N/A'),
            $laporan->jumlah_kerusakan,
            $laporan->deskripsi,
            $laporan->pelaporLaporan->first()?->user?->nama ?? 'N/A',
            $laporan->status->nama_status ?? 'N/A',
            $laporan->tanggal_lapor->translatedFormat('d M Y'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]]];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $e) {
                $ws = $e->sheet->getDelegate();

                // Tambah tabel tren
                if ($this->rowCount > 0) {
                    $range = 'A1:G' . ($this->rowCount + 1);
                    $tbl = new Table($range, 'TblTren');
                    $style = new TableStyle();
                    $style->setTheme(TableStyle::TABLE_STYLE_MEDIUM9);
                    $style->setShowRowStripes(true);
                    $tbl->setStyle($style);
                    $ws->addTable($tbl);
                }

                // Data grafik tren
                $trenData = LaporanKerusakan::select(DB::raw('DATE(tanggal_lapor) AS tgl'), DB::raw('COUNT(*) AS jml'))
                    ->whereBetween('tanggal_lapor', [$this->startDate, $this->endDate])
                    ->groupBy('tgl')->orderBy('tgl')->get();

                $ws->fromArray(['Tanggal', 'Jumlah'], null, 'J2');
                $r = $this->trenRowStart;
                foreach ($trenData as $d) {
                    $ws->setCellValue("J{$r}", \Carbon\Carbon::parse($d->tgl)->translatedFormat('d M Y'));
                    $ws->setCellValue("K{$r}", $d->jml);
                    $r++;
                }
                $this->trenLastRow = $r - 1;

                // Data grafik gedung
                $gedungData = LaporanKerusakan::join('fasilitas', 'laporan_kerusakan.id_fasilitas', '=', 'fasilitas.id_fasilitas')
                    ->join('ruangan', 'fasilitas.id_ruangan', '=', 'ruangan.id_ruangan')
                    ->join('gedung', 'ruangan.id_gedung', '=', 'gedung.id_gedung')
                    ->select('gedung.nama_gedung', DB::raw('COUNT(*) AS jml'))
                    ->whereBetween('tanggal_lapor', [$this->startDate, $this->endDate])
                    ->groupBy('gedung.nama_gedung')->orderBy('jml', 'desc')->get();

                $ws->fromArray(['Gedung', 'Jumlah'], null, 'R2');
                $r2 = $this->gedungRowStart;
                foreach ($gedungData as $d) {
                    $ws->setCellValue("R{$r2}", $d->nama_gedung);
                    $ws->setCellValue("S{$r2}", $d->jml);
                    $r2++;
                }
                $this->gedungLastRow = $r2 - 1;

                // Tambah grafik Tren Harian
                $trenChart = new Chart(
                    'ctTren',
                    new Title('Tren Harian'),
                    new Legend(Legend::POSITION_TOP, null, false),
                    new PlotArea(null, [
                        new DataSeries(
                            DataSeries::TYPE_LINECHART,
                            DataSeries::GROUPING_STANDARD,
                            range(0, 0),
                            [new DataSeriesValues('String', "'{$this->sheetName}'!\$K\$2", null, 1)],
                            [new DataSeriesValues('String', "'{$this->sheetName}'!\$J\$3:\$J\${$this->trenLastRow}", null, $this->trenLastRow - 2)],
                            [new DataSeriesValues('Number', "'{$this->sheetName}'!\$K\$3:\$K\${$this->trenLastRow}", null, $this->trenLastRow - 2)]
                        )
                    ])
                );
                $trenChart->setTopLeftPosition('I' . ($this->rowCount + 5));
                $trenChart->setBottomRightPosition('P' . ($this->rowCount + 20));
                $ws->addChart($trenChart);

                // Tambah grafik Gedung
                $gedungChart = new Chart(
                    'ctGedung',
                    new Title('Per Gedung'),
                    null,
                    new PlotArea(null, [
                        new DataSeries(
                            DataSeries::TYPE_BARCHART,
                            DataSeries::GROUPING_CLUSTERED,
                            range(0, 0),
                            [new DataSeriesValues('String', "'{$this->sheetName}'!\$S\$2", null, 1)],
                            [new DataSeriesValues('String', "'{$this->sheetName}'!\$R\$3:\$R\${$this->gedungLastRow}", null, $this->gedungLastRow - 2)],
                            [new DataSeriesValues('Number', "'{$this->sheetName}'!\$S\$3:\$S\${$this->gedungLastRow}", null, $this->gedungLastRow - 2)]
                        )
                    ])
                );
                $gedungChart->setTopLeftPosition('Q' . ($this->rowCount + 5));
                $gedungChart->setBottomRightPosition('X' . ($this->rowCount + 20));
                $ws->addChart($gedungChart);
            },
        ];
    }
}
