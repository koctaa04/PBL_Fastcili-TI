@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'prioritas',
])

@section('content')
    <div class="content">
        <h3>Data Prioritas Perbaikan</h3>
        <div class="card p-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-row-bordered" id="table_prioritas">
                        <thead>
                            <tr>
                                <th width="10%">Rank</th>
                                <th width="30%">Laporan</th>
                                <th width="15%">Nilai</th>
                                <th width="15%">Status</th>
                                <th width="20%">Catatan Sarpras</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ranked as $r)
                                @php
                                    $penugasan = $r['penugasan'];
                                    $statusPerbaikan = $penugasan->status_perbaikan ?? null;
                                @endphp
                                <tr>
                                    <td><b><i>#{{ $r['rank'] }}</i></b></td>
                                    <td>{{ $r['deskripsi'] ?? '-' }}</td>
                                    <td>{{ number_format($r['Q'], 4) }}</td>
                                    <td>{{ $statusPerbaikan ?? 'Belum Dikerjakan' }}</td>
                                    <td>{{ $penugasan->komentar_sarpras ?? '-' }}</td>
                                    <td>
                                        @if (!$penugasan)
                                            <button
                                                onclick="modalAction('{{ url('/laporan/penugasan/' . $r['id_laporan']) }}')"
                                                class="btn btn-sm btn-primary me-2">
                                                Tugaskan Teknisi
                                            </button>
                                        @elseif ($r['status'] == 'Selesai')
                                            <span class="text-muted">Sudah Selesai</span>
                                        @elseif ($statusPerbaikan === 'Selesai Dikerjakan')
                                            <button
                                                onclick="modalAction('{{ url('/laporan/verifikasi/' . $r['id_laporan']) }}')"
                                                class="btn btn-sm btn-success">
                                                Verifikasi
                                            </button>
                                        @else
                                            <span class="text-muted">Menunggu perbaikan</span>
                                        @endif
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

        $(document).ready(function() {
            var datalaporan = $('#table_prioritas').DataTable({
                columnDefs: [{
                        targets: [0, 1, 2, 3, 4, 5],
                        className: 'text-center',
                    },{
                        targets: [0, 5],
                        orderable: false,
                        searchable: false,
                    },{
                        targets: [1, 4],
                        orderable: false,
                    }

                ], 
                language: {
                    emptyTable: "<i class='fas fa-info-circle'></i> Tidak ada data prioritas perbaikan yang tersedia",
                    zeroRecords: "<i class='fas fa-info-circle'></i> Tidak ada data prioritas perbaikan seperti keyword yang ingin dicari"
                }
            });
        });
    </script>
@endpush
