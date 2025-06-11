@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'riwayat_perbaikan',
])

@section('content')
    <div class="content">
        <h3 class="mb-0">Riwayat Penugasan Perbaikan</h3>
        <p class="card-category">Daftar perbaikan yang telah diselesaikan</p>

        {{-- Tabel Riwayat Penugasan --}}
        <div class="card p-4 shadow">
            <div class="form-group row">
                <label for="filter_bulan" class="col-form-label col-sm-2">Filter Bulan</label>
                <div class="col-sm-4">
                    <select id="filter_bulan" class="form-control">
                        <option value="">-- Semua Bulan --</option>
                        @for ($i = 1; $i <= now()->month; $i++)
                            <option value="{{ $i }}">
                                {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
            </div>


            <div class="table-responsive">
                <table class="table table-hover table-striped table-sm" id="table_riwayat">
                    <thead class="bg-light text-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nama Fasilitas</th>
                            <th scope="col">Ruangan</th>
                            <th scope="col">Gedung</th>
                            <th scope="col">Tanggal Selesai</th>
                            <th scope="col">Catatan Teknisi</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
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
    </script>
    <script>
        var dataRiwayat;
        $(document).ready(function() {
            // Inisialisasi DataTable
            dataRiwayat = $('#table_riwayat').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: window.location.href,
                    data: function(d) {
                        d.bulan = $('#filter_bulan').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        className: 'text-center'
                    },
                    {
                        data: 'nama_fasilitas',
                        name: 'nama_fasilitas'
                    },
                    {
                        data: 'nama_ruangan',
                        name: 'nama_ruangan'
                    },
                    {
                        data: 'nama_gedung',
                        name: 'nama_gedung'
                    },
                    {
                        data: 'tanggal_selesai',
                        name: 'tanggal_selesai',
                        className: 'text-center'
                    },
                    {
                        data: 'catatan_teknisi',
                        name: 'catatan_teknisi'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        className: 'text-center'
                    }
                ],
                columnDefs: [{
                    targets: [0, 4, 6],
                    orderable: false,
                    searchable: false
                }],
                language: {
                    emptyTable: "<i class='fas fa-info-circle'></i> Tidak ada riwayat tersedia",
                    zeroRecords: "<i class='fas fa-info-circle'></i> Tidak ditemukan data untuk bulan yang dipilih"
                }
            });

            // Filter berdasarkan bulan
            $('#filter_bulan').on('change', function() {
                dataRiwayat.ajax.reload();
            });
        });
    </script>
@endpush
