@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'waspas',
])

@section('content')
    <div class="content">
        <h3>Data Prioritas Perbaikan</h3>
        <div class="card p-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover table-sm" id="table_prioritas">
                        <thead>
                            <tr>
                                <th scope="col">Rank</th>
                                <th scope="col">Laporan</th>
                                <th scope="col">Nilai</th>
                                <th scope="col">Status</th>
                                <th scope="col">Catatan Sarpras</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ranked as $r)
                                @php
                                    // dd($r);
                                    $penugasan = $r['penugasan'];
                                    $statusPerbaikan = $penugasan->status_perbaikan ?? null;
                                @endphp
                                <tr>
                                    <th scope="row">{{ $r['rank'] }}</th>
                                    <td>{{ $r['deskripsi'] }}</td>
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
            var datalaporan = $('#table_prioritas').DataTable();
        });
    </script>
@endpush
