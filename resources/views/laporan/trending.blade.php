@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'verifikasi_laporan',
])

@section('content')
    <div class="content">
        <h3>Laporan Kerusakan Trending</h3>
        <div class="card px-4">
            <div class="card-header">

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-row-bordered" id="table_trending">
                        <thead>
                            <tr>
                                <th width="7%">Rank</th>
                                <th width="20%">Foto Kerusakan</th>
                                <th width="10%">Fasilitas</th>
                                <th width="20%">Deskripsi</th>
                                <th width="15%">Total Pelapor</th>
                                <th width="15%">Skor Trending</th>
                                <th width="8%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $i => $laporan)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>
                                        <img src="{{ asset('storage/uploads/laporan_kerusakan/' . $laporan['laporan']->foto_kerusakan) }}"
                                            alt="Foto Gedung" class="card-img-top img-fluid"
                                            style="height: 120px; object-fit: cover;">
                                    </td>
                                    <td>{{ $laporan['laporan']->fasilitas->nama_fasilitas ?? '-' }}</td>
                                    <td>{{ $laporan['laporan']->deskripsi ?? '-' }}</td>
                                    <td>{{ $laporan['laporan']->pelaporLaporan->count() }}</td>
                                    <td>{{ $laporan['skor'] }}</td>
                                    <td>
                                        <button class="btn btn-primary btn-sm"
                                            onclick="modalAction('{{ url('/lapor_kerusakan/penilaian/' . $laporan['laporan']->id_laporan) }}')">
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
            var datalaporan = $('#table_trending').DataTable({
                columnDefs: [{
                        targets: [0, 1, 2, 3, 4, 5, 6],
                        className: 'text-center',
                    },{
                        targets: [0, 1, 6],
                        orderable: false,
                        searchable: false,
                    }
                ], 
                language: {
                    emptyTable: "<i class='fas fa-info-circle'></i> Tidak ada data laporan trending yang tersedia",
                    zeroRecords: "<i class='fas fa-info-circle'></i> Tidak ada data laporan trending seperti keyword yang ingin dicari"
                }
            });
        });
    </script>
@endpush
