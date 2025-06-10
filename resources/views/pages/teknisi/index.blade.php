@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'dashboard',
])

@section('content')
    <div class="content">
        {{-- Kartu Statistik --}}
        <div class="row">
            {{-- Total Penugasan --}}
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats shadow">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-5 col-md-4">
                                <div class="icon-big text-center icon-warning">
                                    <i class="nc-icon nc-paper text-warning"></i>
                                </div>
                            </div>
                            <div class="col-7 col-md-8">
                                <div class="numbers">
                                    <p class="card-category">Total Penugasan</p>
                                    <h4 class="card-title">{{ $jmlPenugasan }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Laporan Belum Ditugaskan --}}
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats shadow">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-5 col-md-4">
                                <div class="icon-big text-center icon-warning">
                                    <i class="nc-icon nc-book-bookmark text-primary"></i>
                                </div>
                            </div>
                            <div class="col-7 col-md-8">
                                <div class="numbers">
                                    <p class="card-category text-nowrap">Laporan Belum Ditugaskan</p>
                                    <h4 class="card-title">{{ $laporanBlmPenugasan }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Laporan Sedang Diperbaiki --}}
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats shadow">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-5 col-md-4">
                                <div class="icon-big text-center icon-warning">
                                    <i class="nc-icon nc-bullet-list-67 text-danger"></i> 
                                </div>
                            </div>
                            <div class="col-7 col-md-8">
                                <div class="numbers">
                                    <p class="card-category text-nowrap">Laporan Sedang Diperbaiki</p>
                                    <h4 class="card-title">{{ $laporanDikerjakan }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Laporan Sudah Diperbaiki --}}
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats shadow">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-5 col-md-4">
                                <div class="icon-big text-center icon-warning">
                                    <i class="nc-icon nc-single-copy-04 text-success"></i>
                                </div>
                            </div>
                            <div class="col-7 col-md-8">
                                <div class="numbers">
                                    <p class="card-category text-nowrap">Laporan Sudah Diperbaiki</p>
                                    <h4 class="card-title">{{ $laporanSelesaiDikerjakan }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Area Konten Utama --}}
        <div class="container-fluid">
            @if ($penugasan && $penugasan->tanggal_selesai == null)
                {{-- Penugasan Aktif --}}
                <div class="card shadow rounded p-4 mb-4">
                    <h3 class="mb-4 fw-bold text-dark">Penugasan Perbaikan</h3>
                    <div class="row g-0 align-items-center">
                        <div class="col-md-4 d-flex align-items-center justify-content-center p-3">
                            <img src="{{ asset('storage/uploads/laporan_kerusakan/' . $penugasan->laporan->foto_kerusakan) }}"

                                onerror="this.onerror=null;this.src='{{ asset('images/fasilitas-rusak.jpeg') }}';"
                                alt="Foto Kerusakan" class="img-fluid rounded shadow-sm">

                                onerror="this.onerror=null;this.src='{{ asset('foto_kerusakan.jpg') }}';"
                                alt="Foto Kerusakan" class="img-fluid rounded">

                        </div>
                        <div class="col-md-8">
                            <div class="card-body p-4">
                                <h5 class="card-title mb-4">Laporan Kerusakan Fasilitas</h5>
                                <p class="mb-2"><strong>Fasilitas:</strong>
                                    {{ $penugasan->laporan->fasilitas->nama_fasilitas }}</p>
                                <p class="mb-2"><strong>Lokasi:</strong>
                                    {{ $penugasan->laporan->fasilitas->ruangan->nama_ruangan }}, {{ $penugasan->laporan->fasilitas->ruangan->gedung->nama_gedung }}</p>
                                <p class="mb-2"><strong>Tanggal Lapor:</strong>

                                     {{ $penugasan->laporan->tanggal_lapor ? \Carbon\Carbon::parse($penugasan->laporan->tanggal_lapor)
                                        ->locale('id')->translatedFormat('l, d F Y') : '-' }}
                                <p class="mb-2"><strong>Tenggat:</strong>
                                    {{ $penugasan->tenggat ? \Carbon\Carbon::parse($penugasan->tenggat)
                                        ->locale('id')->translatedFormat('l, d F Y') : '-' }}
                                    {{ $penugasan->laporan->tanggal_lapor->translatedFormat('l, d F Y') }}</p>
                                <p class="mb-2"><strong>Deskripsi:</strong>
                                    {{ $penugasan->laporan->pelaporLaporan->first()->deskripsi_tambahan ?? '-' }}
                                </p>
                                <p class="mb-2"><strong>Deskripsi:</strong>
                                    {{ $penugasan->laporan->pelaporLaporan->first()->deskripsi_tambahan ?? 'Tidak ada deskripsi tambahan.' }}
                                </p>
                                <div class="d-flex justify-content-end mt-4">
                                    <button

                                        onclick="modalAction('{{ route('teknisi.feedback', ['id' => $penugasan->id_penugasan]) }}')"
                                        class="btn btn-primary btn-round btn-sm">
                                        <i class="nc-icon nc-settings-gear-65"></i> Dokumentasi Perbaikan

                                        onclick="modalAction('{{ url('/perbaikan/edit/' . $penugasan->id_penugasan) }}')"
                                        class="btn btn-primary btn-sm me-2">
                                        Dokumentasi Perbaikan

                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                {{-- Sambutan --}}
                <div class="jumbotron bg-white shadow rounded p-5 mb-4">
                    <h2 class="display-5 fw-bold mb-3">Selamat Datang, {{ auth()->user()->nama }}!</h2> 
                    <p class="lead mt-3">Pantau dan tangani laporan kerusakan fasilitas kampus secara efisien. Silakan cek
                        daftar tugas yang telah ditugaskan kepada Anda, dan pastikan setiap perbaikan diselesaikan tepat
                        waktu demi kenyamanan bersama.</p>
                    <hr class="my-4">
                    <p>🔧 Jangan lupa untuk memperbarui status pekerjaan setelah perbaikan selesai dilakukan.</p>
                </div>
            @endif

            @if ($riwayat->count() > 0)
                {{-- Tabel Riwayat Penugasan --}}
                <div class="card p-4 shadow">
                    <div class="card-header bg-light pb-0">
                        <h3 class="card-title text-dark">Riwayat Penugasan Perbaikan</h3>
                        <p class="card-category">Daftar perbaikan yang telah diselesaikan</p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-sm" id="table_level">
                                <thead class="bg-light text-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nama Fasilitas</th>
                                        <th scope="col">Ruangan</th>
                                        <th scope="col">Gedung</th>
                                        <th scope="col">Tanggal Lapor</th>
                                        <th scope="col">Tanggal Selesai</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($riwayat as $lapor => $l)
                                        <tr>
                                            <th scope="row">{{ $lapor + 1 }}</th>
                                            <td>{{ $l->laporan->fasilitas->nama_fasilitas }}</td>
                                            <td>{{ $l->laporan->fasilitas->ruangan->nama_ruangan }}</td>
                                            <td>{{ $l->laporan->fasilitas->ruangan->gedung->nama_gedung }}</td>
                                            <td>
                                                {{ $l->laporan->tanggal_lapor
                                                    ? \Carbon\Carbon::parse($l->laporan->tanggal_lapor)->locale('id')->translatedFormat('l, d F Y')
                                                    : '-' }}
                                            </td>
                                            <td>

                                                {{ $l->laporan->tanggal_selesai
                                                    ? \Carbon\Carbon::parse($l->laporan->tanggal_selesai)->locale('id')->translatedFormat('l, d F Y')

                                                {{ $l->laporan->tanggal_lapor
                                                    ? $l->laporan->tanggal_lapor->translatedFormat('l, d F Y')
                                                    : '-' }}
                                            </td>
                                            <td>
                                                {{ $l->tanggal_selesai
                                                    ? $l->tanggal_selesai->translatedFormat('l, d F Y')

                                                    : '-' }}
                                            </td>
                                            <td>
                                                <button

                                                    onclick="modalAction('{{ route('teknisi.detailRiwayat', ['id' => $l->id_penugasan]) }}')"
                                                    class="btn btn-info btn-sm btn-round">
                                                    <i class="nc-icon nc-zoom-split"></i> Detail

                                                    onclick="modalAction('{{ url('/perbaikan/detail/'. $l->id_penugasan) }}')"
                                                    class="btn btn-warning btn-sm me-2">
                                                    Detail

                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Grafik --}}
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card shadow">
                        <div class="card-header bg-light pb-0">
                            <h5 class="card-title text-dark">Penugasan Per Bulan</h5>
                            <p class="card-category">Perbaikan per bulan</p>
                        </div>
                        <div class="card-body">
                            <canvas id="perbaikanPerBulan"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-chart shadow">
                        <div class="card-header bg-light pb-0">
                            <h5 class="card-title text-dark">Penugasan Per Gedung</h5>
                            <p class="card-category">Distribusi perbaikan antar gedung</p>
                        </div>
                        <div class="card-body">
                            <canvas id="penugasanGedungChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- Modal --}}
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false"

    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="true" data-keyboard="false"

        aria-hidden="true"></div>
@endsection

@push('scripts')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }

        const perbaikanPerBulanData = {!! json_encode($perbaikanPerBulan) !!};
        const penugasanPerGedungData = {!! json_encode($penugasanPerGedung) !!};

        const ctx1 = document.getElementById('perbaikanPerBulan').getContext('2d');
        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: Object.keys(perbaikanPerBulanData),
                datasets: [{
                    label: 'Jumlah Perbaikan',
                    data: Object.values(perbaikanPerBulanData),
                    borderColor: 'rgba(218, 165, 32, 1)',
                    backgroundColor: 'rgba(218, 165, 32, 0.2)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: 'rgba(218, 165, 32, 1)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(218, 165, 32, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 14
                            }
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 12
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: {
                                size: 12
                            },
                            precision: 0 
                        }
                    }
                }
            }
        });

        const ctx3 = document.getElementById('penugasanGedungChart').getContext('2d');
        new Chart(ctx3, {
            type: 'bar',
            data: {
                labels: Object.keys(penugasanPerGedungData),
                datasets: [{
                    label: 'Jumlah Penugasan',
                    data: Object.values(penugasanPerGedungData),
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)', 
                        'rgba(255, 99, 132, 0.7)', 
                        'rgba(75, 192, 192, 0.7)', 
                        'rgba(153, 102, 255, 0.7)', 
                        'rgba(255, 159, 64, 0.7)', 
                        'rgba(201, 203, 207, 0.7)' 
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(201, 203, 207, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false 
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 12
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: {
                                size: 12
                            },
                            precision: 0
                        }
                    }
                }
            }
        });
    </script>
@endpush
