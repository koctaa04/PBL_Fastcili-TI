@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'prioritas',
])

@section('content')
<div class="content">
    <h3 class="mb-4">Data Prioritas Perbaikan</h3>
    <div class="card px-4">
        <div class="card-header d-flex justify-content-between align-items-center pb-5 pt-5">
            <div class="d-flex align-items-center">
                <div style="width: 580px;">
                    <input type="text" class="form-control rounded-pill" id="search"
                        placeholder="Cari Laporan Prioritas...">
                    <small class="form-text text-muted text-small">Cari berdasarkan deskripsi laporan atau status</small>
                </div>
            </div>
            <span class="badge badge-warning px-3 py-2">
                <i class="fas fa-sort-amount-down-alt mr-1"></i> Diurutkan berdasarkan: Nilai WASPAS
            </span>
            <span class="badge badge-pill badge-danger px-3 py-2">
                <i class="fas fa-exclamation-triangle mr-1"></i> Prioritas Perbaikan
            </span>
        </div>
        <div class="card-body p-0">
            <div id="priority-container">
                @foreach ($ranked as $r)
                @php
                    $penugasan = $r['penugasan'];
                    $statusPerbaikan = $penugasan->status_perbaikan ?? null;
                    $rank = $r['rank'];
                    
                    $borderColor = match($rank) {
                        1 => 'border-left: 4px solid #dc3545;',
                        2 => 'border-left: 4px solid #fd7e14;',
                        3 => 'border-left: 4px solid #ffc107;',
                        default => 'border-left: 4px solid #e9ecef;'
                    };
                    
                    $statusColor = match($statusPerbaikan ?? 'Belum Dikerjakan') {
                        'Belum Dikerjakan' => 'badge-secondary',
                        'Dalam Pengerjaan' => 'badge-primary',
                        'Selesai Dikerjakan' => 'badge-info',
                        'Selesai' => 'badge-success',
                        default => 'badge-secondary'
                    };
                @endphp
                <div class="card priority-card mb-3" style="{{ $borderColor }}">
                    <div class="card-body p-3">
                        <div class="d-flex">
                            <div class="rank-display d-flex flex-column align-items-center justify-content-center mr-3" style="width: 60px;">
                                <span class="rank-number font-weight-bold">#{{ $rank }}</span>
                                <div class="priority-indicator mt-1">
                                    @if($rank <= 3)
                                        <i class="fas fa-exclamation-circle text-danger"></i>
                                    @else
                                        <i class="fas fa-arrow-up text-primary"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-grow-1 d-flex">
                                {{-- @if($fotoPath) --}}
                                <div class="mr-3" style="width: 220px;">
                                    <img src="{{ asset('storage/uploads/laporan_kerusakan/' . $r['foto_kerusakan']) }}"
                                        alt="Foto Kerusakan"
                                        class="img-fluid rounded"
                                        style="height: 140px; width: 100%; object-fit: cover;">
                                </div>
                                {{-- @endif --}}
                                <div class="flex-grow-1 d-flex flex-column">
                                    <div class="mb-3">
                                        <p class="card-text">{{ $r['deskripsi'] ?? '-' }}</p>
                                    </div>
                                    <div class="d-flex flex-wrap align-items-center mb-2">
                                        <span class="badge badge-pill badge-dark px-3 py-2 mr-3 mb-2">
                                            <i class="fas fa-calculator mr-1"></i> Nilai WASPAS: {{ number_format($r['Q'], 4) }}
                                        </span>
                                        <span class="badge badge-pill {{ $statusColor }} px-3 py-2 mr-3 mb-2">
                                            <i class="fas fa-info-circle mr-1"></i> {{ $statusPerbaikan ?? 'Belum Dikerjakan' }}
                                        </span>
                                        @if($penugasan && $penugasan->komentar_sarpras)
                                        <span class="badge badge-pill badge-light border px-3 py-2 mb-2">
                                            <i class="fas fa-clipboard mr-1"></i> Catatan: {{ $penugasan->komentar_sarpras }}
                                        </span>
                                        @endif
                                    </div>
                                    <div class="mt-auto">
                                        <div class="d-flex justify-content-end">
                                            @if(!$penugasan)
                                                <button onclick="modalAction('{{ url('/laporan/penugasan/' . $r['id_laporan']) }}')"
                                                    class="btn btn-danger btn-lg px-4 py-2">
                                                    <i class="fas fa-user-tie mr-1"></i> Tugaskan Teknisi
                                                </button>
                                            @elseif($r['status'] == 'Selesai')
                                                <span class="btn btn-success btn-lg px-4 py-2 disabled">
                                                    <i class="fas fa-check-circle mr-1"></i> Sudah Selesai
                                                </span>
                                            @elseif($statusPerbaikan === 'Selesai Dikerjakan')
                                                <button onclick="modalAction('{{ url('/laporan/verifikasi/' . $r['id_laporan']) }}')"
                                                        class="btn btn-info btn-lg px-4 py-2">
                                                    <i class="fas fa-check-double mr-1"></i> Verifikasi
                                                </button>
                                            @else
                                                <span class="btn btn-secondary btn-lg px-4 py-2 disabled">
                                                    <i class="fas fa-clock mr-1"></i> Menunggu perbaikan
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<div id="myModal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false"
    aria-hidden="true"></div>
@endsection

@push('styles')
<style>
    .priority-card {
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease;
        border-top: none;
        border-right: none;
        border-bottom: none;
    }

    .priority-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .rank-display {
        background-color: #f8f9fa;
        border-radius: 6px;
        padding: 10px;
    }

    .rank-number {
        font-size: 1.5rem;
        color: #343a40;
    }

    .badge {
        font-size: 0.85rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    #priority-container {
        padding: 0 10px;
    }

    .card-text {
        font-size: 1rem;
        color: #495057;
    }
    
    /* Status badge colors */
    .badge-secondary {
        background-color: #6c757d;
    }
    
    .badge-primary {
        background-color: #007bff;
    }
    
    .badge-info {
        background-color: #17a2b8;
    }
    
    .badge-success {
        background-color: #28a745;
    }
    
    /* Button sizing */
    .btn-lg {
        padding: 0.5rem 1.5rem;
        font-size: 1rem;
    }
    
    /* Make badges larger */
    .badge-pill {
        font-size: 0.9rem !important;
        padding: 0.5rem 1rem !important;
    }
    
    /* Image container */
    .img-container {
        width: 220px;
        height: 140px;
        overflow: hidden;
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

    // Array of colors for cards
    const cardColors = [
        'linear-gradient(105deg, #faf8f5 0%, #f6ae43 110%)',
        'linear-gradient(105deg, #faf8f5 0%, #f6ae43e6 110%)',
        'linear-gradient(105deg, #faf8f5 0%, #f6ae43cb 110%)',
        'linear-gradient(105deg, #faf8f5 0%, #f6ae43b7 110%)',
        'linear-gradient(105deg, #faf8f5 0%, #f6ae43a0 110%)',
        'linear-gradient(105deg, #faf8f5 0%, #f6ae438a 110%)',
        'linear-gradient(105deg, #faf8f5 0%, #f6ae436c 110%)',
        'linear-gradient(105deg, #faf8f5 0%, #f6ae435d 110%)',
        'linear-gradient(105deg, #faf8f5 0%, #f6ae4348 110%)',
        'linear-gradient(105deg, #faf8f5 0%, #f6ae432f 110%)'
    ];

    $(document).ready(function() {
        // Apply different background to each card
        $('.priority-card').each(function(index) {
            // Use modulo to cycle through colors if there are more cards than colors
            const colorIndex = index % cardColors.length;
            $(this).css('background', cardColors[colorIndex]);
            
            // Add hover effect that darkens the card slightly
            $(this).hover(
                function() {
                    $(this).css('background', 
                        cardColors[colorIndex].replace('135deg', '145deg') + 
                        ' !important');
                },
                function() {
                    $(this).css('background', 
                        cardColors[colorIndex] + ' !important');
                }
            );
        });

        // Search functionality
        $('#search').on('input', function() {
            const searchTerm = $(this).val().toLowerCase();
            $('.priority-card').each(function() {
                const cardText = $(this).text().toLowerCase();
                if (cardText.includes(searchTerm)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });
</script>
@endpush