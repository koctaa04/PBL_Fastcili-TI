@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'fasilitas',
])

@section('content')
    <div class="content">
        <h3>Data Fasilitas</h3>
        <div class="card p-4">
            <div class="card-header">
                <div class="row align-items-center justify-content-between">
                    <div class="col-md-auto">
                        <button onclick="modalAction('{{ url('/fasilitas/create') }}')"
                            class="btn btn-sm btn-primary mt-1">Tambah
                            Data</button>
                    </div>
                    <div class="col-md-auto">
                        <div class="form-group mb-0 d-flex align-items-center">
                            <label for="id_ruangan" class="mr-2 mb-0">Filter:</label>
                            <select class="form-control form-control-sm" id="id_ruangan" name="id_ruangan">
                                <option value="">-- Semua --</option>
                                @foreach ($ruangan as $item)
                                    <option value="{{ $item->id_ruangan }}"
                                        {{ request('id_ruangan') == $item->id_ruangan ? 'selected' : '' }}>
                                        {{ $item->nama_ruangan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover table-sm" id="table_fasilitas">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama Gedung</th>
                                <th scope="col">Nama Ruangan</th>
                                <th scope="col">Nama Fasilitas</th>
                                <th scope="col">Jumlah</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($fasilitas as $index => $f)
                                <tr>
                                    <th scope="row">{{ $index + 1 }}</th>
                                    <td>{{ $f->ruangan->gedung->nama_gedung }}</td>
                                    <td>{{ $f->ruangan->nama_ruangan }}</td>
                                    <td>{{ $f->nama_fasilitas }}</td>
                                    <td>{{ $f->jumlah }}</td>
                                    <td>
                                        <div class="d-flex">
                                            <button
                                                onclick="modalAction('{{ url('/fasilitas/edit/' . $f->id_fasilitas . '') }}')"
                                                class="btn btn-sm btn-warning" style="margin-right: 8px">Edit</button>
                                            <form class="form-delete"
                                                action="{{ url('/fasilitas/delete/' . $f->id_fasilitas . '') }}"
                                                method="POST">
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
        var dataruangan;

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

        $(document).ready(function() {
            $('#id_ruangan').on('change', function() {
                var selectedRuangan = $(this).val();
                var currentUrl = window.location.href.split('?')[0];
                var newUrl = currentUrl;

                if (selectedRuangan !== "") {
                    newUrl += '?id_ruangan=' + selectedRuangan;
                } else {
                    newUrl = currentUrl;
                }

                window.location.href = newUrl;
            });

            var datafasilitas = $('#table_fasilitas').DataTable();
        });
    </script>
@endpush
