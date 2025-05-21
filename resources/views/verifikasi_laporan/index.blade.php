@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'verifikasi_laporan',
])

@section('content')
    <div class="content">
        <h3>Data Laporan Masuk</h3>
        <div class="card p-4">
            <div class="card-header">
                {{-- <button onclick="modalAction('{{ url('/laporan/create') }}')" class="btn btn-sm btn-primary mt-1">Tambah
                    Data</button> --}}
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover table-sm" id="table_laporan">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Laporan</th>
                                <th scope="col">Pelapor</th>
                                <th scope="col">Status</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @foreach ($laporan as $index => $l)
                                <tr>
                                    <th scope="row">{{ $index + 1 }}</th>
                                    <td>{{ $l->deskripsi }}</td>
                                    <td>{{ $l->user->nama }}</td>
                                    <td>{{ $l->status->nama_status }}</td>
                                    <td>
                                        <div class="d-flex">
                                            <button
                                                onclick="modalAction('{{ url('/verifikasi/true/' . $l->id_laporan . '') }}')"
                                                class="btn btn-sm btn-success" style="margin-right: 8px">Verifikasi</button>
                                            <button
                                                onclick="modalAction('{{ url('/verifikasi/false/' . $l->id_laporan . '') }}')"
                                                class="btn btn-sm btn-danger" style="margin-right: 8px">Tolak</button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach --}}
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
            var datalaporan = $('#table_laporan').DataTable();
        });
    </script>
@endpush
