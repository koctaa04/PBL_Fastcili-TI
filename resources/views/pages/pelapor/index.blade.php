@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'dashboard',
])

@section('content')
    <div class="content">
        <div class="container">
            @if ($statusList->count() > 0)
                <h3 class="mb-4 fw-bold">Status Laporan Anda</h3>
                @foreach ($statusList as $status)
                    <div class="card shadow-lg border-0 rounded-4 mb-4">
                        <div class="row g-0">
                            <div class="col-md-4 d-flex align-items-center justify-content-center bg-light p-3 rounded-start">
                                <img src="{{ asset('storage/uploads/laporan_kerusakan/' . $status->laporan->foto_kerusakan) }}"
                                    onerror="this.onerror=null;this.src='{{ asset('foto_kerusakan.jpg') }}';"
                                    alt="Foto Kerusakan" class="img-fluid rounded-3">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body p-4">
                                    <h5 class="card-title mb-3 text-dark fw-bold">Laporan Kerusakan Fasilitas</h5>

                                    <p class="mb-2"><strong>Fasilitas:</strong>
                                        {{ $status->laporan->fasilitas->nama_fasilitas }}</p>
                                    <p class="mb-2"><strong>Tanggal Lapor:</strong>
                                        {{ $status->created_at->translatedFormat('l, d F Y') }}</p>
                                    <p class="mb-2">
                                        <strong>Status:</strong>
                                        @php
                                            $statusColor = match ($status->laporan->id_status) {
                                                1 => 'bg-secondary text-white',
                                                2 => 'bg-info text-white',
                                                3 => 'bg-warning text-white',
                                                4 => 'bg-success text-white',
                                                default => 'bg-dark',
                                            };
                                        @endphp
                                        <span class="badge {{ $statusColor }} px-3 py-2 fs-6">
                                            {{ $status->laporan->status->nama_status }}
                                        </span>
                                    </p>
                                    <p class="mb-2"><strong>Deskripsi:</strong> {{ $status->deskripsi_tambahan }}</p>
                                    @if ($status->laporan->id_status == 3 || $status->laporan->id_status == 4)
                                        <p class="mb-2"><strong>Teknisi:</strong>
                                            {{ $status->laporan->penugasan->user->nama ?? '-' }}
                                        </p>
                                    @endif

                                    <div class="d-flex justify-content-end mt-4 gap-2 flex-wrap">
                                        @if ($status->laporan->id_status == 5)
                                            <a href="{{ route('pelapor.edit', ['id' => $status->id]) }}"
                                                class="btn btn-warning btn-sm">
                                                Edit
                                            </a>
                                        @elseif ($status->laporan->id_status == 4)
                                            <button
                                                onclick="modalAction('{{ route('pelapor.rate', ['id' => $status->id_laporan]) }}')"
                                                class="btn btn-primary btn-sm">
                                                Beri Nilai
                                            </button>
                                        @elseif ($status->laporan->id_status == 1 || $status->laporan->id_status == 5)
                                            <form class="form-delete d-inline-block"
                                                action="{{ route('pelapor.delete', ['id' => $status->id]) }}"
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
                @endforeach
            @else
                <div class="jumbotron bg-white shadow rounded-4 p-5 text-center border">
                    <h2 class="display-5 fw-bold text-primary">Selamat Datang di Sistem Laporan Fasilitas Kampus</h2>
                    <p class="lead mt-3 text-muted">Sampaikan laporan kerusakan fasilitas kampus dengan mudah dan cepat. Kami akan
                        menindaklanjuti laporan Anda secepat mungkin untuk kenyamanan bersama.</p>
                    <hr class="my-4">
                    <p class="text-muted">Pastikan laporan berisi informasi yang jelas dan disertai foto kerusakan agar proses perbaikan
                        dapat segera dilakukan.</p>
                    <a class="btn btn-lg btn-primary mt-3" href="{{ route('pelapor.create') }}">
                        <i class="bi bi-plus-circle me-2"></i> Laporkan Kerusakan Fasilitas
                    </a>
                </div>
            @endif

            @if ($laporanAuth->count() > 0)
                <div class="card shadow-lg border-0 rounded-4 mt-5">
                    <div class="card-header bg-warning text-white rounded-top-4">
                        <h3 class="mb-3">Riwayat Laporan</h3>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle table-striped table-bordered" id="table_level">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="text-center">#</th>
                                        <th scope="col">Nama Fasilitas</th>
                                        <th scope="col">Deskripsi</th>
                                        <th scope="col">Tanggal Lapor</th>
                                        <th scope="col">Status</th>
                                        <th scope="col" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($laporanAuth as $lapor => $l)
                                        <tr>
                                            <th scope="row" class="text-center">{{ $lapor + 1 }}</th>
                                            <td>{{ $l->laporan->fasilitas->nama_fasilitas }}</td>
                                            <td>{{ $l->deskripsi_tambahan }}</td>
                                            <td>{{ $l->created_at->translatedFormat('l, d F Y') }}</td>
                                            <td>
                                                @php
                                                    $statusColor = match ($l->laporan->id_status) {
                                                        1 => 'bg-secondary text-white',
                                                        2 => 'bg-info text-white',
                                                        3 => 'bg-warning text-white',
                                                        4 => 'bg-success text-white',
                                                        default => 'bg-dark',
                                                    };
                                                @endphp
                                                <span class="badge {{ $statusColor }} px-3 py-2">
                                                    {{ $l->laporan->status->nama_status }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <button
                                                    onclick="modalAction('{{ route('pelapor.detail', ['id' => $l->id]) }}')"
                                                    class="btn btn-sm btn-info text-white">
                                                    Detail
                                                </button>
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

    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="true" data-keyboard="false"
        aria-hidden="true"></div>
@endsection

@push('styles')
    <style>
        /* Card Hover Effect */
        .card.shadow-lg:hover {
            transform: translateY(-3px);
            transition: all 0.3s ease-in-out;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        /* Smooth transition for buttons & badges */
        .btn, .badge {
            transition: all 0.2s ease-in-out;
        }

        .btn:hover {
            opacity: 0.9;
        }

        /* Rounded image for card */
        .card img.img-fluid.rounded-3 {
            border-radius: 0.75rem;
        }

        /* Jumbotron border & subtle background */
        .jumbotron {
            background-color: #f9fafc;
            border: 1px solid #e3e6f0;
        }

        /* Table header styling */
        .table thead th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.875rem;
            letter-spacing: 0.5px;
        }

        /* Table row hover */
        .table-hover tbody tr:hover {
            background-color: #f1f5f9;
        }

        /* Responsive padding for card body */
        .card-body {
            padding: 1.5rem;
        }

        /* Badge styling */
        .badge {
            font-size: 0.85rem;
            border-radius: 0.5rem;
            padding: 0.5em 0.75em;
        }

        /* Button small */
        .btn-sm {
            padding: 0.4rem 0.75rem;
            font-size: 0.8rem;
            border-radius: 0.4rem;
        }
    </style>
@endpush


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
