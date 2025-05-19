@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'ruangan',
])

@section('content')
    <div class="content">
        <h3>Data Ruangan</h3>
        <div class="card p-4">
            <div class="card-header">
                <div class="row align-items-center justify-content-between">
                    <div class="col-md-auto">
                        <button onclick="modalAction('{{ url('/ruangan/create') }}')" class="btn btn-sm btn-primary">Tambah
                            Data</button>
                    </div>
                    <div class="col-md-auto">
                        <div class="form-group mb-0 d-flex align-items-center">
                            <label for="id_gedung" class="mr-2 mb-0">Filter:</label>
                            <select class="form-control form-control-sm" id="id_gedung" name="id_gedung">
                                <option value="">-- Semua --</option>
                                @foreach ($gedung as $item)
                                    <option value="{{ $item->id_gedung }}"
                                        {{ request('id_gedung') == $item->id_gedung ? 'selected' : '' }}>
                                        {{ $item->nama_gedung }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover table-sm" id="table_ruangan">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama Gedung</th>
                                <th scope="col">Kode Ruangan</th>
                                <th scope="col">Nama Ruangan</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ruangan as $index => $r)
                                <tr>
                                    <th scope="row">{{ $index + 1 }}</th>
                                    <td>{{ $r->gedung->nama_gedung }}</td>
                                    <td>{{ $r->kode_ruangan }}</td>
                                    <td>{{ $r->nama_ruangan }}</td>
                                    <td>
                                        <div class="d-flex">
                                            <button
                                                onclick="modalAction('{{ url('/ruangan/edit/' . $r->id_ruangan . '') }}')"
                                                class="btn btn-sm btn-warning" style="margin-right: 8px">Edit</button>
                                            <form class="form-delete"
                                                action="{{ url('/ruangan/delete/' . $r->id_ruangan . '') }}" method="POST">
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

        $(document).on('submit', '.form-delete', function(e) {
            e.preventDefault();
            let form = this;

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
            $('#id_gedung').on('change', function() {
                var selectedGedung = $(this).val();
                var currentUrl = window.location.href.split('?')[0];
                var newUrl = currentUrl;

                if (selectedGedung !== "") {
                    newUrl += '?id_gedung=' + selectedGedung;
                } else {
                    newUrl = currentUrl;
                }

                window.location.href = newUrl;
            });

            var dataruangan = $('#table_ruangan').DataTable();
        });
    </script>
@endpush
