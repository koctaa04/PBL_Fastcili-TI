@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'laporan',
])

@section('content')
    <div class="content">
        <h3>Data Level</h3>
        <div class="card p-4">
            <div class="card-header">
                <button onclick="modalAction('{{ url('/lapor_kerusakan/create') }}')" class="btn btn-sm btn-primary mt-1">Tambah
                    Data</button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover table-sm" id="table_level">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Foto Kerusakan</th>
                                <th scope="col">Nama Fasilitas</th>
                                <th scope="col">Deskripsi</th>
                                <th scope="col">Tanggal lapor</th>
                                <th scope="col">Status</th>
                                <th scope="col">Keterangan</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($laporan_kerusakan as $lapor => $l)
                                <tr>
                                    <th scope="row">{{ $lapor + 1 }}</th>
                                    <td>
                                        <img src="{{ asset('storage/foto_kerusakan/' . $l->foto_kerusakan) }}"
                                             alt="Foto Kerusakan"
                                             width="200"
                                             onerror="this.onerror=null;this.src='{{ asset('images/fasilitas-rusak.jpeg') }}';">
                                    </td>
                                    
                                    <td>{{ $l->fasilitas->nama_fasilitas }}</td>
                                    <td>{{ $l->deskripsi }}</td>
                                    <td>{{ $l->tanggal_lapor }}</td>
                                    <td>{{ $l->status->nama_status }}</td>
                                    <td>{{ $l->keterangan ?? '-' }}</td>
                                    <td>
                                        <div class="d-flex">
                                            <button onclick="modalAction('{{ url('/laporan_kerusakan/edit/' . $l->id_laporan . '') }}')"
                                                class="btn btn-sm btn-warning" style="margin-right: 8px">Edit</button>
                                            <form class="form-delete"
                                                action="{{ url('/laporan_kerusakan/delete/' . $l->id_laporan . '') }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </div>
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
        var dataLaporan;

        $(document).on('submit', '.form-delete', function(e) {
            e.preventDefault(); // Cegah submit form langsung
            let form = this;
            let url = $(this).data('url');

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
                        url: $(this).attr('action'),
                        data: $(this).serialize(),
                        dataType: "json",
                        success: function(response) {
                            if (response.success) {
                                $('#myModal').modal('hide');
                                Swal.fire({
                                    icon: "success",
                                    title: "Berhasil!",
                                    text: response.messages,
                                });
                                location.reload();
                            } else {
                                alert('Gagal menghapus data.');
                            }
                        },
                        error: function(xhr) {
                            if (xhr.responseJSON && xhr.responseJSON.msgField) {
                                let errors = xhr.responseJSON.msgField;
                                $.each(errors, function(field, messages) {
                                    $('#error-' + field).text(messages[0]);
                                });
                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: "Gagal!",
                                    text: response.messages,
                                });
                            }
                        }
                    });
                }
            });
        });
    </script>
@endpush
