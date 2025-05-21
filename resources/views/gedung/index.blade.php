@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'gedung',
])

@section('content')
    <div class="content">
        <h3>Data Gedung</h3>
        <div class="card p-4">
            <div class="card-header d-flex justify-content-center align-items-center mb-5">
                <div class="card-tools d-flex justify-content-center flex-wrap">
                    <button onclick="modalAction('{{ url('/gedung/create') }}')" 
                            class="btn btn-lg btn-success mb-2">
                        Tambah Data Gedung
                    </button>
                </div>
            </div>
            <div class="card-body">
                {{-- Search --}}
                <div class="row pr-auto">
                    <div class="col-md-12">
                        <div class="form-group row mb-5">
                            <label class="col-2 control-label col-form-label">Cari:</label>
                            <div class="col-10">
                                <input type="text" class="form-control" id="search" placeholder="Cari gedung...">
                                <small class="form-text text-muted">Masukkan nama, kode, atau deskripsi gedung</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card View --}}
                <span class="badge badge-info p-2 mb-3">
                    <i class="fas fa-sort-amount-down-alt mr-1"></i> Diurutkan berdasarkan: Terbaru Ditambahkan
                </span>
                <div class="row g-3" id="gedung-container">
                    <!-- Gedung cards will be loaded here -->
                </div>

                {{-- Pagination --}}
                <div class="row mt-4">
                    <div class="col-md-12 d-flex justify-content-center">
                        <div id="pagination-links"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false"
        aria-hidden="true"></div>
@endsection

@push('styles')
<style>
    .badge.badge-info {
        background-color: #f49a00;
    }
    /* Gedung Card Styling */
    .gedung-card {
        margin-bottom: 0;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        border: 1px solid rgba(0,0,0,0.125);
    }
    
    .gedung-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    }
    
    .gedung-card-img-container {
        width: 100%;
        padding-top: 75%; /* 4:3 Aspect Ratio */
        position: relative;
        overflow: hidden;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
        background-color: #f8f9fa;
    }
    
    .gedung-card-img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .gedung-card-body {
        padding: 15px;
        flex-grow: 1;
    }
    
    .gedung-card-title {
        font-size: 1.1rem;
        font-weight: bold;
        margin-bottom: 10px;
        color: #333;
    }
    
    .gedung-card-text {
        margin-bottom: 5px;
        font-size: 0.9rem;
        color: #555;
    }
    
    .gedung-card-footer {
        padding: 10px 15px;
        background-color: #fef5ed;
        border-top: 1px solid #eee;
        border-bottom-left-radius: 8px;
        border-bottom-right-radius: 8px;
    }
    
    .gedung-card-actions {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
    }
    
    .gedung-kode {
        font-weight: bold;
        color: #ffa200;
    }
    
    .gedung-deskripsi {
        color: #6c757d;
        font-size: 0.85rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    /* Responsive grid settings */
    #gedung-container {
        margin-right: -12px;
        margin-left: -12px;
    }
    
    #gedung-container > [class*="col-"] {
        padding-right: 12px;
        padding-left: 12px;
        margin-bottom: 24px;
    }
    
    /* Button styling */
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }

    /* Pagination styling */
    .pagination {
        margin-top: 20px;
    }
    
    .page-item.active .page-link {
        background-color: #ffa200;
        border-color: #ffa200;
    }
    
    .page-link {
        color: #ffa200;
    }
</style>
@endpush

@push('scripts')
    <script>
        var currentPage = 1;
        var perPage = 12; // 4 cards x 3 rows
        var searchTimeout;

        $(document).ready(function() {
            // Load initial data
            loadGedungCards();

            // Search input event with debounce
            $('#search').on('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    currentPage = 1;
                    loadGedungCards();
                }, 500);
            });
        });

        function loadGedungCards() {
            const searchTerm = $('#search').val();
            
            $.ajax({
                url: "{{ url('gedung') }}",
                type: "GET",
                dataType: "json",
                data: {
                    search: searchTerm,
                    page: currentPage,
                    per_page: perPage
                },
                success: function(response) {
                    const container = $('#gedung-container');
                    container.empty();
                    
                    if(response.data && response.data.length > 0) {
                        response.data.forEach((gedung) => {
                            // Determine image source
                            const fotoGedung = gedung.foto_gedung 
                                ? "{{ asset('storage/uploads/foto_gedung') }}/" + gedung.foto_gedung 
                                : "{{ asset('gedung_default.jpg') }}";
                            
                            const cardHtml = `
                                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                                    <div class="card gedung-card">
                                        <div class="gedung-card-img-container">
                                            <img src="${fotoGedung}" alt="Foto Gedung ${gedung.nama_gedung}" class="gedung-card-img">
                                        </div>
                                        <div class="gedung-card-body">
                                            <h5 class="gedung-card-title">${gedung.nama_gedung}</h5>
                                            <p class="gedung-card-text"><strong>Kode:</strong> <span class="gedung-kode">${gedung.kode_gedung || '-'}</span></p>
                                            <p class="gedung-card-text gedung-deskripsi">${gedung.deskripsi || 'Tidak ada deskripsi'}</p>
                                        </div>
                                        <div class="gedung-card-footer">
                                            <div class="gedung-card-actions">
                                                <button onclick="modalAction('{{ url('/gedung/edit') }}/${gedung.id_gedung}')" 
                                                        class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <form class="form-delete d-inline" action="{{ url('/gedung/delete') }}/${gedung.id_gedung}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            container.append(cardHtml);
                        });
                    } else {
                        container.append('<div class="col-12 text-center py-4"><p class="text-muted">Tidak ada data gedung</p></div>');
                    }

                    // Update pagination
                    updatePagination(response);
                },
                error: function(xhr) {
                    console.error('Error loading gedung:', xhr);
                    alert('Gagal memuat data gedung');
                }
            });
        }

        function updatePagination(response) {
            const paginationContainer = $('#pagination-links');
            paginationContainer.empty();

            if (response.last_page > 1) {
                let paginationHtml = '<ul class="pagination">';

                // Previous button
                if (response.current_page > 1) {
                    paginationHtml += `
                        <li class="page-item">
                            <a class="page-link" href="#" onclick="changePage(${response.current_page - 1})" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    `;
                } else {
                    paginationHtml += `
                        <li class="page-item disabled">
                            <span class="page-link" aria-hidden="true">&laquo;</span>
                        </li>
                    `;
                }

                // Page numbers
                for (let i = 1; i <= response.last_page; i++) {
                    if (i === response.current_page) {
                        paginationHtml += `
                            <li class="page-item active">
                                <span class="page-link">${i}</span>
                            </li>
                        `;
                    } else {
                        paginationHtml += `
                            <li class="page-item">
                                <a class="page-link" href="#" onclick="changePage(${i})">${i}</a>
                            </li>
                        `;
                    }
                }

                // Next button
                if (response.current_page < response.last_page) {
                    paginationHtml += `
                        <li class="page-item">
                            <a class="page-link" href="#" onclick="changePage(${response.current_page + 1})" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    `;
                } else {
                    paginationHtml += `
                        <li class="page-item disabled">
                            <span class="page-link" aria-hidden="true">&raquo;</span>
                        </li>
                    `;
                }

                paginationHtml += '</ul>';
                paginationContainer.append(paginationHtml);
            }
        }

        function changePage(page) {
            currentPage = page;
            loadGedungCards();
            return false;
        }

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
                        url: $(form).attr('action'),
                        data: $(form).serialize(),
                        dataType: "json",
                        success: function(response) {
                            if (response.success) {
                                $('#myModal').modal('hide');
                                Swal.fire({
                                    icon: "success",
                                    title: "Berhasil!",
                                    text: response.message,
                                    timer: 3000,
                                    showConfirmButton: true
                                });
                                loadGedungCards();
                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: "Gagal!",
                                    text: response.message,
                                });
                            }
                        },
                        error: function(xhr) {
                            if (xhr.responseJSON && xhr.responseJSON.msgField) {
                                let errors = xhr.responseJSON.msgField;
                                $.each(errors, function(field, message) {
                                    $('#error-' + field).text(message[0]);
                                });
                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: "Gagal!",
                                    text: xhr.responseJSON.message || 'Terjadi kesalahan',
                                });
                            }
                        }
                    });
                }
            });
        });
    </script>
@endpush