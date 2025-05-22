@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'verifikasi_laporan',
])

@section('content')
    <div class="content">
        <h3>VerifikasiLaporan Trending</h3>
        <div class="card p-4">
            <div class="card-header">

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="table_trending">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Foto Kerusakan</th>
                                <th>Fasilitas</th>
                                <th>Deskripsi</th>
                                <th>Total Pelapor</th>
                                <th>Skor Trending</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $i => $laporan)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>
                                        <img src="{{ asset('storage/uploads/laporan_kerusakan/' . $laporan->laporan->foto_kerusakan) }}"
                                            alt="Foto Gedung" class="card-img-top img-fluid"
                                            style="height: 120px; object-fit: cover;">
                                    </td>
                                    <td>{{ $laporan->laporan->fasilitas->nama_fasilitas ?? '-' }}</td>
                                    <td>{{ $laporan->laporan->deskripsi ?? '-' }}</td>
                                    <td>{{ $laporan->total }}</td>
                                    <td>{{ $laporan->skor_trending }}</td>
                                    <td>
                                        <!-- Tombol untuk memunculkan modal -->
                                        <button class="btn btn-primary btn-sm"
                                            onclick="modalAction('{{ url('/lapor_kerusakan/penilaian/' . $laporan->id_laporan) }}')">
                                            <i class="fas fa-star"></i> Beri Nilai
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
        var datalaporan;

        $(document).ready(function() {
            var datalaporan = $('#table_trending').DataTable();
        });
    </script>
@endpush
