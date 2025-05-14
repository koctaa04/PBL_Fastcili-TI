@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'user',
])

@section('content')
    <div class="content">
        <h3>Data User</h3>
        <div class="card p-4">
            <div class="card-header">
                <button onclick="modalAction('{{ url('/users/create') }}')" class="btn btn-sm btn-primary mt-1">Tambah
                    Data</button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-row-bordered" id="table_ruangan">
                        <thead>
                            <tr class="text-center">
                                <th scope="col">#</th>
                                <th scope="col">Nama Level</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Akses</th>
                                <th scope="col">Email</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $index => $u)
                                <tr>
                                    <th scope="row">{{ $index + 1 }}</th>
                                    <td>{{ $u->level->nama_level }}</td>
                                    <td>{{ $u->nama }}</td>
                                    <td class="text-center">
                                        @if ($u->akses == 1)
                                            <i class="fas fa-user-check text-success"></i>
                                        @else
                                            <i class="fas fa-user-times text-danger"></i>
                                        @endif
                                    </td>
                                    <td>{{ $u->email }}</td>
                                    <td>
                                        <div class="d-flex">
                                            <button
                                                onclick="modalAction('{{ url('/users/edit/' . $u->id_user . '') }}')"
                                                class="btn btn-sm btn-warning" style="margin-right: 8px">Edit</button>
                                            <form class="form-delete"
                                                action="{{ url('/users/delete/' . $u->id_user . '') }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                            <button onclick="toggleAccess('{{ url('/users/toggle-access/' . $u->id_user . '') }}')" 
                                                class="btn btn-sm {{ $u->akses ? 'btn-secondary' : 'btn-success' }} ml-2">
                                                {{ $u->akses ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
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
        
        function toggleAccess(url) {
            // Extract ID from URL
            const id = url.split('/').pop();
            const currentAccess = $(`button[onclick*="${id}"]`).hasClass('btn-success') ? 0 : 1;
            
            Swal.fire({
                title: 'Konfirmasi',
                text: `Anda yakin ingin ${currentAccess ? 'menonaktifkan' : 'mengaktifkan'} akses ini?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, lanjutkan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'POST'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sukses!',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: response.message
                                });
                            }
                        },
                        error: function(xhr) {
                            let errorMsg = 'Terjadi kesalahan saat memproses permintaan';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: errorMsg
                            });
                        }
                    });
                }
            });
        }

        $(document).on('submit', '.form-delete', function(e) {
            e.preventDefault();
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