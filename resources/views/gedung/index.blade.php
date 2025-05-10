@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'gedung',
])

@section('content')
<div class="content">
    <h3>Gedung Polinema</h3>
    <button onclick="modalAction('{{ url('/gedung1/create') }}')" class="btn btn-sm btn-primary mt-1">Tambah Data</button>
    <div class="row mt-3">
        @foreach ($gedung as $index => $g)
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                {{-- <img class="card-img-top" src="{{ asset('foto_gedung/gedung_default.jpg') }}" alt="Card image cap"> --}}
                @if ($g->foto_gedung)
                                    <img class="card-img-top" 
                                         src="{{ asset('uploads/foto_gedung/' . $g->foto_gedung) }}" 
                                         alt="Foto Gedung" width="150" height="150">
                                @else
                                    <img class="card-img-top" 
                                         src="{{ asset('gedung_default.jpg') }}" 
                                         alt="Gedung Default" width="120" height="120">
                                @endif
                <div class="card-body">
                    <ul class="list-group list-group-flush text-start mt-3">
                        <li class="list-group-item"><strong>Nama:</strong> {{ $g->nama_gedung }}</li>
                        <li class="list-group-item"><strong>Kode:</strong> {{ $g->kode_gedung }}</li>
                        <li class="list-group-item"><strong>Deskripsi:</strong> {{ $g->deskripsi }}</li>
                    </ul>
                    <div class="d-flex mt-2">
                        <button onclick="modalAction('{{ url('/gedung1/edit/' . $g->id_gedung) }}')" class="btn btn-sm btn-warning mr-2">Edit</button>
                        <form class="form-delete" action="{{ url('/gedung1/delete/' . $g->id_gedung) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
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
