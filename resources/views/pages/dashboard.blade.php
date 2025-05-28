@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'dashboard',
])

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5 col-md-4">
                                <div class="icon-big text-center icon-warning">
                                    <i class="nc-icon nc-paper"></i>
                                </div>
                            </div>
                            <div class="col-7 col-md-8">
                                <div class="numbers">
                                    <p class="card-category">Total Laporan Masuk</p>
                                    <p class="card-title">{{ $jmlLaporan }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5 col-md-4">
                                <div class="icon-big text-center icon-warning">
                                    <i class="nc-icon nc-book-bookmark"></i>
                                </div>
                            </div>
                            <div class="col-7 col-md-8">
                                <div class="numbers">
                                    <p class="card-category">Laporan Terverifikasi</p>
                                    <p class="card-title">{{ $laporanTerverifikasi }}
                                    <p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5 col-md-4">
                                <div class="icon-big text-center icon-warning">
                                    <i class="nc-icon nc-bullet-list-67"></i>
                                </div>
                            </div>
                            <div class="col-7 col-md-8">
                                <div class="numbers">
                                    <p class="card-category">Laporan Aktif</p>
                                    <p class="card-title">{{ $laporanAktif }}
                                    <p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5 col-md-4">
                                <div class="icon-big text-center icon-warning">
                                    <i class="nc-icon nc-single-copy-04"></i>
                                </div>
                            </div>
                            <div class="col-7 col-md-8">
                                <div class="numbers">
                                    <p class="card-category">Laporan Selesai</p>
                                    <p class="card-title">{{ $laporanSelesai }}
                                    <p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header ">
                        <h5 class="card-title">Laporan Masuk</h5>
                        <p class="card-category">Laporan per bulan</p>
                    </div>
                    <div class="card-body ">
                        <canvas id=laporanPerBulan width="400" height="125"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Top 5 Prioritas Perbaikan</h5>
                        <p class="card-category">Berdasarkan hasil perhitungan SPK</p>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-hover table-row-bordered">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>ID Laporan</th>
                                    <th>Deskripsi</th>
                                    <th>Status</th>
                                    <th>Nilai SPK</th>
                                    <th>Teknisi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse (collect($spkRank)->take(5) as $item)
                                    <tr>
                                        <td>{{ $item['rank'] ?? '-' }}</td>
                                        <td>{{ $item['id_laporan'] ?? '-' }}</td>
                                        <td>{{ $item['deskripsi'] ?? 'Tidak ada deskripsi' }}</td>
                                        <td>
                                            @php
                                                $status = $item['status'] ?? 'Tidak diketahui';

                                                $statusColor = match ($status) {
                                                    'Diproses' => 'bg-primary text-white',
                                                    'Diperbaiki' => 'bg-secondary text-white',
                                                    default => 'bg-primary text-white',
                                                };
                                            @endphp
                                            <span class="badge p-2 {{ $statusColor }}">
                                                {{ $status }}
                                            </span>
                                        </td>

                                        <td>{{ isset($item['Q']) ? number_format($item['Q'], 4) : '890' }}</td>
                                        <td>
                                            {{-- {{ laporan->penugasan->user->nama }} --}}
                                            {{ $item['penugasan']['nama_teknisi'] ?? 'Belum Ditugaskan' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            <i class="fas fa-info-circle"></i> Tidak ada data prioritas perbaikan yang
                                            tersedia
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card ">
                    <div class="card-header ">
                        <h5 class="card-title">Status Laporan</h5>
                        <p class="card-category">Laporan per status</p>
                    </div>
                    <div class="card-body ">
                        <canvas id="statusLaporanChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card card-chart">
                    <div class="card-header">
                        <h5 class="card-title">Laporan per gedung</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="laporanGedungChart" width="400" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            //grafik laporan per bulan
            const ctx1 = document.getElementById('laporanPerBulan').getContext('2d');
            new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: {!! json_encode($laporanPerBulan->keys()) !!},
                    datasets: [{
                        label: 'Jumlah Laporan Masuk',
                        data: {!! json_encode($laporanPerBulan->values()) !!},
                        borderColor: 'rgba(218, 165, 32, 1)',
                        backgroundColor: 'rgba(218, 165, 32, 1)',
                        fill: true,
                        tension: 0.1,
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            //pie chart status laporan
            const ctx2 = document.getElementById('statusLaporanChart');
            new Chart(ctx2, {
                type: 'pie',
                data: {
                    labels: {!! json_encode($statusLaporan->keys()) !!},
                    datasets: [{
                        data: {!! json_encode($statusLaporan->values()) !!},
                        backgroundColor: ['#ffc107', '#17a2b8', '#fd7e14', '#28a745', '#dc3545']
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            display: true
                        }
                    }
                }
            });

            //graik laporan per gedung
            const ctx3 = document.getElementById('laporanGedungChart');
            new Chart(ctx3, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($laporanPerGedung->keys()) !!},
                    datasets: [{
                        label: 'Jumlah Laporan',
                        data: {!! json_encode($laporanPerGedung->values()) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
@endpush
