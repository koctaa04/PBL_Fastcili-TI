<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <form method="POST" action="{{ route('fasilitas.store') }}" id="form_create">
            @csrf
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold">Tambah Data Fasilitas</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body py-4">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="gedung" class="font-weight-bold">Gedung <span class="text-danger">*</span></label>
                        <select name="id_gedung" id="gedung" class="form-control " required>
                            <option value="">Pilih Gedung</option>
                            @foreach ($gedung as $g)
                                <option value="{{ $g->id_gedung }}">{{ $g->nama_gedung }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Pilih gedung tempat fasilitas berada</small>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="ruangan" ><b>Ruangan <span class="text-danger">*</span></b> (Pilih Gedung terlebih dahulu)</label>
                        <select name="id_ruangan" id="ruangan" class="form-control " required>
                            <option value="">Pilih Ruangan</option>
                            @foreach ($ruangan as $r)
                                <option value="{{ $r->id_ruangan }}">{{ $r->nama_ruangan }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Pilih ruangan tempat fasilitas berada</small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="kode_fasilitas" class="font-weight-bold">Kode Fasilitas <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="kode_fasilitas" id="kode_fasilitas" required>
                        <small class="form-text text-muted">Masukkan kode unik fasilitas</small>
                        <small class="text-danger error-text" id="error-kode_fasilitas"></small>
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label for="nama_fasilitas" class="font-weight-bold">Nama Fasilitas <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama_fasilitas" id="nama_fasilitas" required>
                        <small class="form-text text-muted">Masukkan nama fasilitas</small>
                        <small class="text-danger error-text" id="error-nama_fasilitas"></small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="jumlah" class="font-weight-bold">Jumlah <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="jumlah" id="jumlah" min="1" required>
                        <small class="form-text text-muted">Masukkan jumlah fasilitas yang tersedia</small>
                        <small class="text-danger error-text" id="error-jumlah"></small>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Tutup
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let isSubmitting = false;

    $(document).on('submit', '#form_create', function(e) {
        e.preventDefault();

        if (isSubmitting) return;
        isSubmitting = true;

        $('.error-text').text('');

        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: "json",
            beforeSend: function() {
                $('#form_create button[type=submit]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...');
            },
            success: function(response) {
                isSubmitting = false;
                $('#form_create button[type=submit]').prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Simpan');
                if (response.success) {
                    $('#modal-master').modal('hide'); 
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil!",
                        text: response.message,
                        showConfirmButton: true
                    }).then(() => {
                        location.reload(); // Reload halaman setelah modal ditutup
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal!",
                        text: response.message,
                    });
                }
            },
            error: function(xhr) {
                isSubmitting = false;
                $('#form_create button[type=submit]').prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Simpan');
                if (xhr.responseJSON && xhr.responseJSON.msgField) {
                    let errors = xhr.responseJSON.msgField;
                    $.each(errors, function(field, message) {
                        $('#error-' + field).text(message[0]);
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal!",
                        title: "Gagal!",
                        text: "Terjadi kesalahan saat menyimpan data. Silakan coba lagi.", 
                    });
                }
            }
        });
    });

    $(document).ready(function() {
        // Inisialisasi select2 jika tersedia
        // if ($.fn.select2) {
        //     $('.select2').select2({
        //         placeholder: "Pilih opsi",
        //         allowClear: true
        //     });
        // }

        $('#gedung').change(function() {
            let gedungId = $(this).val();
            $('#ruangan').html('<option value="">Memuat...</option>').prop('disabled', true);

            if (gedungId) {
                $.ajax({
                    url: "{{ route('fasilitas.getRuangan', '') }}/" + gedungId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        console.log('Response:', data); 
                        let options = '<option value="">Pilih Ruangan</option>';

                        if (data && data.length > 0) {
                            $.each(data, function(i, ruangan) {
                                options += '<option value="' + ruangan.id_ruangan +
                                    '">' +
                                    ruangan.nama_ruangan + '</option>';
                            });
                        } else {
                            options = '<option value="">Tidak ada ruangan</option>';
                        }

                        $('#ruangan').html(options).prop('disabled', false);
                        // if ($.fn.select2) {
                        //     $('#ruangan').trigger('change.select2');
                        // }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', status, error); 
                        $('#ruangan').html('<option value="">Gagal memuat ruangan</option>')
                            .prop('disabled', true);
                    }
                });
            } else {
                $('#ruangan').html('<option value="">Pilih Gedung terlebih dahulu</option>')
                    .prop('disabled', true);
            }
        });

        // Event ketika modal ditutup
        $('#modal-master').on('hidden.bs.modal', function () {
            // location.reload(); // Reload halaman ketika modal ditutup
            $('#ruangan').html(options).prop('disabled', false);
        });

        $('#modal-master').on('show.bs.modal', function () {
            $('#gedung').val(''); 
            // $('#ruangan').html('<option value="">Pilih Ruangan</option>').prop('disabled', false);
            $('#ruangan').html(options).prop('disabled', false);
            $('.error-text').text('');
        });
    });
</script>