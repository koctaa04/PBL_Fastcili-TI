@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'gedung',
])

@section('content')
    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Gedung Polinema</h3>
            <button onclick="modalAction('{{ url('/gedung/create') }}')" class="btn btn-sm btn-primary">
                Tambah Data
            </button>
        </div>

        <div class="row mt-4">
            @foreach ($gedung as $g)
                <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                    <div class="card h-100 d-flex flex-column">
                        {{-- Gambar gedung --}}
                        <img src="{{ asset('storage/uploads/gedung/' . $g->foto_gedung) }}" alt="Foto Gedung"
                            class="card-img-top img-fluid" style="height: 120px; object-fit: cover;"
                            onerror="this.onerror=null;this.src='{{ asset('gedung_default.jpg') }}';">

                        {{-- Konten Card --}}
                        <div class="card-body d-flex flex-column justify-content-between pt-0">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item border-0 px-0">
                                    <h6 class="mb-1 fw-bold">{{ $g->nama_gedung }}</h6>
                                </li>
                                <li class="list-group-item border-0 px-0">
                                    <strong>Kode:</strong> {{ $g->kode_gedung }}
                                </li>
                                <li class="list-group-item border-0 px-0">
                                    <strong>Deskripsi:</strong>
                                    <p class="mb-0">{{ $g->deskripsi }}</p>
                                </li>
                            </ul>

                            {{-- Tombol Edit dan Delete --}}
                            <div class="d-flex mt-auto">
                                <button onclick="modalAction('{{ url('/gedung/edit/' . $g->id_gedung) }}')"
                                    class="btn btn-sm btn-warning flex-fill mr-2">
                                    Edit
                                </button>
                                <form class="form-delete flex-fill" action="{{ url('/gedung/delete/' . $g->id_gedung) }}"
                                    method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger w-100">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" id="modal-content-container">
            <!-- Konten dari create/edit akan dimuat di sini lewat AJAX -->
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function modalAction(url) {
            $.get(url, function(res) {
                $('#modal-content-container').html(res);
                $('#myModal').modal('show');
            }).fail(function() {
                alert('Gagal memuat data.');
            });
        }

        // Konfirmasi sebelum hapus
        $(document).on('submit', '.form-delete', function(e) {
            e.preventDefault();
            let form = this;

            Swal.fire({
                title: "Yakin ingin menghapus?",
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: $(form).attr('action'),
                        method: 'POST',
                        data: $(form).serialize(),
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Berhasil', response.message, 'success');
                                location.reload();
                            } else {
                                Swal.fire('Gagal', response.message, 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Gagal', 'Terjadi kesalahan saat menghapus.', 'error');
                        }
                    });
                }
            });
        });
    </script>
@endpush
