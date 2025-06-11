@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'dashboard',
])

@section('content')
    <div class="content">
        <div class="row justify-content-center"> {{-- Centering the content column --}}
            <div class="col-lg-8 col-md-10 col-12"> {{-- Wider column for better display of history --}}
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Riwayat Notifikasi</h4>
                    </div>
                    <div class="card-body">
                        @forelse ($notifications as $notification)
                            <div class="card mb-3 shadow-sm {{ is_null($notification->read_at) ? 'border-primary border-2' : 'border' }}">
                                <div class="card-body d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                                    {{-- Notification content area --}}
                                    <div class="flex-grow-1 mb-2 mb-md-0 me-md-3">
                                        <h5 class="card-title fw-bold text-dark mb-1">
                                            @if (is_null($notification->read_at))
                                                <span class="badge bg-primary me-2">BARU</span> {{-- 'NEW' badge for unread --}}
                                            @endif
                                            {{-- Displaying notification type or facility --}}
                                            {{ $notification->data['fasilitas'] ?? $notification->data['tipe'] ?? 'Notifikasi Baru' }}
                                        </h5>
                                        <p class="card-text text-muted mb-1">
                                            {{-- Displaying main notification data or status --}}
                                            {{ $notification->data['data'] ?? $notification->data['status'] ?? $notification->data['tipe'] ?? '-' }}
                                        </p>
                                    </div>

                                    {{-- Action buttons area --}}
                                    <div class="d-flex flex-column flex-md-row gap-2">
                                        <a href="{{ $notification->data['link'] ?? '#' }}" class="btn btn-info btn-sm">Lihat Detail</a>
                                        @if (is_null($notification->read_at))
                                            <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-secondary btn-sm">Tandai Dibaca</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-muted">Tidak ada notifikasi.</p> {{-- Message if no notifications --}}
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="true" data-keyboard="false"
        aria-hidden="true"></div>
@endsection

@push('scripts')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }
    </script>
@endpush
