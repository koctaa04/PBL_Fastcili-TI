@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'dashboard',
])

@section('content')
    <div class="content">
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
                                    {{ \Carbon\Carbon::parse($penugasan->created_at)->format('d M Y') }}</p>
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
    </script>
@endpush
