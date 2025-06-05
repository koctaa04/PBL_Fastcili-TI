@extends('layouts.app', [
'class' => '',
'elementActive' => 'verifikasi_laporan',
])

@section('content')
<div class="content">
    <h3 class="mb-4">Laporan Kerusakan Trending</h3>
    <div class="card px-4">
        <div class="card-header d-flex justify-content-between align-items-center pb-5 pt-5">
            <div class="d-flex align-items-center">
                <div style="width: 580px;">
                    <input type="text" class="form-control rounded-pill" id="search"
                        placeholder="Cari Laporan..." value="{{ $search }}">
                    <small class="form-text text-muted text-small">Cari berdasarkan fasilitas atau deskripsi laporan</small>
                </div>
            </div>
            <span class="badge badge-warning px-3 py-2">
                <i class="fas fa-sort-amount-down-alt mr-1"></i> Diurutkan berdasarkan: Skor Laporan
            </span>
            <span class="badge badge-pill badge-danger px-3 py-2">
                <i class="fas fa-fire mr-1"></i> Top 10 Laporan
            </span>
        </div>
        <div class="card-body p-0">
            <div id="trending-container">
                @foreach ($data as $laporan)
                @php
                $rank = $laporan['rank']; // Menggunakan rank yang sudah di-set di controller
                $borderColor = match($rank) {
                1 => 'border-left: 4px solid #ffc107;',
                2 => 'border-left: 4px solid #6c757d;',
                3 => 'border-left: 4px solid #cd7f32;',
                default => 'border-left: 4px solid #e9ecef;'
                };
                @endphp
                <div class="card trending-card mb-3" style="{{ $borderColor }}">
                    <div class="card-body p-3">
                        <div class="d-flex">
                            <div class="rank-display d-flex flex-column align-items-center justify-content-center mr-3" style="width: 60px;">
                                <span class="rank-number font-weight-bold">#{{ $rank }}</span>
                                <div class="trend-indicator mt-1">
                                    @if($rank <= 3)
                                        <i class="fas fa-fire text-danger"></i>
                                        @else
                                        <i class="fas fa-chart-line text-primary"></i>
                                        @endif
                                </div>
                            </div>
                            <div class="flex-grow-1 d-flex">
                                <div class="mr-3">
                                    <img src="{{ asset('storage/uploads/laporan_kerusakan/' . $laporan['laporan']->foto_kerusakan) }}"
                                        alt="Foto Kerusakan"
                                        class="img-fluid rounded"
                                        style="height: 180px; width: 250px; object-fit: cover;">
                                </div>
                                <div class="flex-grow-1 d-flex flex-column">
                                    <div>
                                        <h4 class="card-title font-weight-bold mb-2">{{ $laporan['laporan']->fasilitas->nama_fasilitas ?? '-' }}</h4>
                                        <p class="card-text text-muted mb-3" style="font-size: 0.9rem;">{{ $laporan['laporan']->deskripsi ?? '-' }}</p>
                                    </div>
                                    <div class="mt-auto">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="badge badge-pill badge-primary px-3 py-2 mr-2">
                                                    <i class="fas fa-users mr-1"></i> Pelapor: {{ $laporan['total_pelapor'] }}
                                                </span>
                                                <span class="badge badge-pill badge-warning px-3 py-2">
                                                    <i class="fas fa-star mr-1"></i> Skor: {{ $laporan['skor'] }}
                                                </span>
                                            </div>
                                            <button class="btn btn-danger btn-lg"
                                                onclick="modalAction('{{ url('/lapor_kerusakan/penilaian/' . $laporan['laporan']->id_laporan) }}')">
                                                <i class="fas fa-star mr-1"></i> Beri Nilai
                                            </button>
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
    .trending-card {
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease;
        border-top: none;
        border-right: none;
        border-bottom: none;
    }

    .trending-card:hover {
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

    #trending-container {
        padding: 0 10px;
    }

    .card-title {
        font-size: 1.1rem;
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
        $('.trending-card').each(function(index) {
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

        // Search input event with debounce
        var searchTimeout;
        $('#search').on('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                updateResults();
            }, 500);
        });

        function updateResults() {
            const sort = $('#sort').val();
            const search = $('#search').val();

            let params = new URLSearchParams();
            if (sort) params.append('sort', sort);
            if (search) params.append('search', search);

            window.location.href = "{{ route('trending.index') }}?" + params.toString();
        }
    });
</script>
@endpush