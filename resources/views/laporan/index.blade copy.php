@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'lapor_kerusakan',
])

@section('content')
<div class="content">
    <h3>Daftar Laporan Kerusakan</h3>
    <div class="card p-4">
        <div class="card-header">
            <button onclick="modalAction('{{ url('/lapor_kerusakan/create') }}')" class="btn btn-sm btn-primary mt-1">
                Tambah Data
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover table-sm" id="table_laporan">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Foto Kerusakan</th>
                            <th>Nama Fasilitas</th>
                            <th>Deskripsi</th>
                            <th>Tanggal Lapor</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach ($laporan_kerusakan as $i => $lapor)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>
                                <img src="{{ asset('storage/uploads/laporan_kerusakan/' . $lapor->foto_kerusakan) }}"
                                     alt="Foto Kerusakan"
                                     width="120"
                                     onerror="this.onerror=null;this.src='{{ asset('images/fasilitas-rusak.jpeg') }}';">
                            </td>
                            <td>{{ $lapor->fasilitas->nama_fasilitas ?? '-' }}</td>
                            <td>{{ $lapor->deskripsi }}</td>
                            <td>{{ $lapor->tanggal_lapor }}</td>
                            <td>{{ $lapor->status->nama_status ?? '-' }}</td>
                            <td>{{ $lapor->keterangan ?? '-' }}</td>
                            <td>
                                <div class="d-flex">
                                    @if ($lapor->id_status == 5)
                                    <button onclick="modalAction('{{ url('/lapor_kerusakan/edit/' . $lapor->id_laporan) }}')"
                                            class="btn btn-sm btn-warning me-2">
                                        Edit
                                    </button>
                                    @else
                                    <button class="btn btn-sm btn-secondary" disabled>Edit</button>
                                    @endif
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
        $('#myModal').load(url, function () {
            $('#myModal').modal('show');
        });
    }

    $(document).on('submit', '.form-delete', function (e) {
        e.preventDefault();
        const form = this;

        Swal.fire({
            title: 'Apakah Anda yakin ingin menghapus data ini?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: $(form).attr('action'),
                    data: $(form).serialize(),
                    dataType: "json",
                    success: function (response) {
                        if (response.success) {
                            $('#myModal').modal('hide');
                            Swal.fire("Berhasil!", response.messages, "success");
                            location.reload();
                        } else {
                            Swal.fire("Gagal!", response.messages, "error");
                        }
                    },
                    error: function (xhr) {
                        Swal.fire("Gagal!", "Terjadi kesalahan sistem.", "error");
                    }
                });
            }
        });
    });

    $(document).ready(function () {
        $('#table_laporan').DataTable();
    });
</script>
@endpush
