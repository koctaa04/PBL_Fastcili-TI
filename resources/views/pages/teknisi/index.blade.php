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
                            <div class="col-8 col-md-8">
                                <div class="numbers">
                                    <p class="card-category">Total Penugasan</p>
                                    <p class="card-title">{{ $jmlPenugasan }}</p>
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
                            <div class="col-5 col-md-2">
                                <div class="icon-big text-center icon-warning">
                                    <i class="nc-icon nc-book-bookmark"></i>
                                </div>
                            </div>
                            <div class="col-8 col-md-10">
                                <div class="numbers">
                                    <p class="card-category text-nowrap">Laporan Belum Ditugaskan</p>
                                    <p class="card-title">{{ $laporanBlmPenugasan }}<p>
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
                            <div class="col-5 col-md-2">
                                <div class="icon-big text-center icon-warning">
                                    <i class="nc-icon nc-bullet-list-67"></i>
                                </div>
                            </div>
                            <div class="col-8 col-md-10">
                                <div class="numbers">
                                    <p class="card-category text-nowrap">Laporan Sedang Diperbaikan</p>
                                    <p class="card-title">{{ $laporanDikerjakan }}<p>
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
                            <div class="col-5 col-md-2">
                                <div class="icon-big text-center icon-warning">
                                    <i class="nc-icon nc-single-copy-04"></i>
                                </div>
                            </div>
                            <div class="col-8 col-md-10">
                                <div class="numbers">
                                    <p class="card-category text-nowrap">Laporan Sudah Diperbaiki</p>
                                    <p class="card-title">{{ $laporanSelesaiDikerjakan }}<p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            @if ($penugasan && $penugasan->tanggal_selesai == null)
                <div class="card shadow rounded p-4">
                    <h3 class="mb-4 fw-bold">Penugasan Perbaikan</h3>
                    <div class="row g-0">
                        <div class="col-md-4 d-flex align-items-center justify-content-center p-3 rounded-start">
                            <img src="{{ asset('storage/uploads/laporan_kerusakan/' . $penugasan->laporan->foto_kerusakan) }}"
                                onerror="this.onerror=null;this.src='{{ asset('images/fasilitas-rusak.jpeg') }}';"
                                alt="Foto Kerusakan" class="img-fluid rounded">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body p-4">
                                <h5 class="card-title mb-4">Laporan Kerusakan Fasilitas</h5>

                                <p class="mb-2"><strong>Fasilitas:</strong>
                                    {{ $penugasan->laporan->fasilitas->nama_fasilitas }}</p>
                                <p class="mb-2"><strong>Tanggal Lapor:</strong>
                                    {{ \Carbon\Carbon::parse($penugasan->laporan->tanggal_lapor)->format('d M Y') }}</p>
                                <p class="mb-2"><strong>Deskripsi:</strong>
                                    {{ $penugasan->laporan->pelaporLaporan->first()->deskripsi_tambahan ?? '-' }}
                                </p>
                                <p class="mb-2"><strong>Teknisi:</strong>
                                    {{ $penugasan->laporan->penugasan->user->nama ?? '-' }}
                                </p>
                                <div class="d-flex justify-content-end mt-4">
                                    <button
                                        onclick="modalAction('{{ route('teknisi.feedback', ['id' => $penugasan->id_penugasan]) }}')"
                                        class="btn btn-primary btn-sm me-2">
                                        Dokumentasi Perbaikan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="jumbotron bg-white shadow-sm rounded p-4">
                    <h2 class="display-5 fw-bold">Selamat Datang, {{ auth()->user()->nama }}</h2>
                    <p class="lead mt-3">Pantau dan tangani laporan kerusakan fasilitas kampus secara efisien. Silakan cek
                        daftar tugas yang telah ditugaskan kepada Anda, dan pastikan setiap perbaikan diselesaikan tepat
                        waktu demi kenyamanan bersama.</p>
                    <hr class="my-4">
                    <p>🔧 Jangan lupa untuk memperbarui status pekerjaan setelah perbaikan selesai dilakukan.</p>
                </div>
            @endif
            @if ($riwayat->count() > 0)
                <div class="card p-4">
                    <div class="card-header">
                        <h3>Riwayat Penugasan Perbaikan</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover table-sm" id="table_level">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nama Fasilitas</th>
                                        <th scope="col">Ruangan</th>
                                        <th scope="col">Gedung</th>
                                        <th scope="col">Tanggal lapor</th>
                                        <th scope="col">Tanggal Selesai</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($riwayat as $lapor => $l)
                                        <tr>
                                            <th scope="row">{{ $lapor + 1 }}</th>
                                            <td>{{ $l->laporan->fasilitas->nama_fasilitas }}</td>
                                            <td>{{ $l->laporan->fasilitas->ruangan->nama_ruangan }}
                                            </td>
                                            <td>{{ $l->laporan->fasilitas->ruangan->gedung->nama_gedung }}
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($l->laporan->tanggal_lapor)->format('d M Y') }}
                                            </td>
                                            <td>
                                                {{ $l->tanggal_selesai ? \Carbon\Carbon::parse($l->tanggal_selesai)->format('d M Y') : '-' }}
                                            </td>
                                            <td>
                                                <button
                                                    onclick="modalAction('{{ route('teknisi.detailRiwayat', ['id' => $l->id_penugasan]) }}')"
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
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card ">
                    <div class="card-header ">
                        <h5 class="card-title">Penugasan</h5>
                        <p class="card-category">Perbaikan per bulan</p>
                    </div>
                    <div class="card-body ">
                        <canvas id=perbaikanPerBulan width="400" height="170"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-chart">
                    <div class="card-header">
                        <h5 class="card-title">Penugasan per gedung</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="penugasanGedungChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false"
        aria-hidden="true"></div>
@endsection

@push('scripts')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }
        var dataLaporan;

        //grafik penugasan per bulan
        const ctx1 = document.getElementById('perbaikanPerBulan').getContext('2d');
        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: {!! json_encode($perbaikanPerBulan->keys()) !!},
                datasets: [{
                    label: 'Jumlah Perbaikan',
                    data: {!! json_encode($perbaikanPerBulan->values()) !!},
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

        //graik penugasan per gedung
            const ctx3 = document.getElementById('penugasanGedungChart');
            new Chart(ctx3, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($penugasanPerGedung->keys()) !!},
                    datasets: [{
                        label: 'Jumlah Penugasan',
                        data: {!! json_encode($penugasanPerGedung->values()) !!},
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
    </script>
@endpush
