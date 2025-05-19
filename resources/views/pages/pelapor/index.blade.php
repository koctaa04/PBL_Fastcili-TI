@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'dashboard',
])

@section('content')
    <div class="content">
        <div class="container">
            @if ($status->rating_pengguna == null)
                <div class="card shadow rounded p-4">
                    <h3 class="mb-4 fw-bold">Status Laporan Anda</h3>
                    <div class="row g-0">
                        <div class="col-md-4 d-flex align-items-center justify-content-center p-3 rounded-start">
                            <img src="{{ asset('storage/uploads/laporan_kerusakan/' . $status->foto_kerusakan) }}"
                                onerror="this.onerror=null;this.src='{{ asset('images/fasilitas-rusak.jpeg') }}';"
                                alt="Foto Kerusakan" class="img-fluid rounded">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body p-4">
                                <h5 class="card-title mb-4">Laporan Kerusakan Fasilitas</h5>

                                <p class="mb-2"><strong>Fasilitas:</strong> {{ $status->fasilitas->nama_fasilitas }}</p>
                                <p class="mb-2"><strong>Tanggal Lapor:</strong>
                                    {{ \Carbon\Carbon::parse($status->tanggal_lapor)->format('d M Y') }}</p>
                                <p class="mb-2">
                                    <strong>Status:</strong>
                                    @php
                                        $statusColor = match ($status->id_status) {
                                            1 => 'bg-secondary',
                                            2 => 'bg-primary',
                                            3 => 'bg-info',
                                            4 => 'bg-success text-white',
                                            5 => 'bg-danger text-white',
                                            default => 'bg-dark',
                                        };
                                    @endphp
                                    <span class="badge {{ $statusColor }}">{{ $status->status->nama_status }}</span>
                                </p>
                                <p class="mb-0"><strong>Deskripsi:</strong> {{ $status->deskripsi }}</p>
                                @if ($status->id_status == 3 || $status->id_status == 4)
                                    <p class="mb-0"><strong>Teknisi:</strong> {{ $status->penugasan->user->nama ?? '-' }}
                                    </p>
                                @endif
                                <div class="d-flex justify-content-end mt-4">
                                    @if ($status->id_status == 5)
                                        <a href="{{ route('pelapor.edit', ['id' => $status->id_laporan]) }}"
                                            class="btn btn-warning btn-sm me-2">
                                            Edit
                                        </a>
                                    @elseif ($status->id_status == 4)
                                        <button
                                            onclick="modalAction('{{ route('pelapor.rate', ['id' => $status->id_laporan]) }}')"
                                            class="btn btn-primary btn-sm me-2">
                                            Beri Nilai
                                        </button>
                                    @elseif ($status->id_status == 1 || $status->id_status == 5)
                                        <form class="form-delete"
                                            action="{{ url('/lapor_kerusakan/delete/' . $status->id_laporan) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                Batalkan Laporan
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="jumbotron bg-white shadow-sm rounded p-4">
                    <h2 class="display-5 fw-bold">Selamat Datang di Sistem Laporan Fasilitas Kampus</h2>
                    <p class="lead mt-3">Sampaikan laporan kerusakan fasilitas kampus dengan mudah dan cepat. Kami akan
                        menindaklanjuti laporan Anda secepat mungkin untuk kenyamanan bersama.</p>
                    <hr class="my-4">
                    <p>Pastikan laporan berisi informasi yang jelas dan disertai foto kerusakan agar proses perbaikan
                        dapat
                        segera dilakukan.</p>
                    <a class="btn btn-primary mt-3" href="{{ url('lapor_kerusakan/mhs/create') }}">
                        <i class="bi bi-plus-circle me-2"></i> Laporkan Kerusakan Fasilitas
                    </a>
                </div>
            @endif
            @if ($laporan->count() > 0)
                <div class="card p-4">
                    <div class="card-header">
                        <h3>Riwayat Laporan</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover table-sm" id="table_level">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nama Fasilitas</th>
                                        <th scope="col">Deskripsi</th>
                                        <th scope="col">Tanggal lapor</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Keterangan</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($laporan as $lapor => $l)
                                        <tr>
                                            <th scope="row">{{ $lapor + 1 }}</th>
                                            <td>{{ $l->fasilitas->nama_fasilitas }}</td>
                                            <td>{{ $l->deskripsi }}</td>
                                            <td>{{ \Carbon\Carbon::parse($l->tanggal_lapor)->format('d M Y') }}</td>
                                            <td>{{ $l->status->nama_status }}</td>
                                            <td>{{ $l->keterangan ?? '-' }}</td>
                                            <td>
                                                <button
                                                    onclick="modalAction('{{ route('pelapor.detail', ['id' => $l->id_laporan]) }}')"
                                                    class="btn btn-sm btn-warning" style="margin-right: 8px">detail</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
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
            e.preventDefault();
            let form = this;
            let url = $(this).data('url');

            Swal.fire({
                title: 'Apakah Anda yakin ingin membatalkan laporan?',
                text: "Laporan yang telah dibatalkan tidak dapat dikembalikan!",
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
