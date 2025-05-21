<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Tambah Gedung</h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('gedung.store') }}" enctype="multipart/form-data" id="form_create">
                @csrf
                <div class="form-group">
                    <label for="nama_gedung">Nama Gedung</label>
                    <input type="text" name="nama_gedung" id="nama_gedung" class="form-control" required>
                    <small class="text-danger" id="error-nama_gedung"></small>
                </div>
                <div class="form-group mt-3">
                    <label for="kode_gedung">Kode Gedung</label>
                    <input type="text" name="kode_gedung" id="kode_gedung" class="form-control" required>
                    <small class="text-danger" id="error-kode_gedung"></small>
                </div>
                <div class="form-group mt-3">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3"></textarea>
                    <small class="text-danger" id="error-deskripsi"></small>
                </div>
                <div class="form-group mt-3">
                    <!-- Custom File Input -->
                    <label for="foto_gedung" class="d-block mb-2">Foto Gedung</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="foto_gedung" id="foto_gedung" accept="image/*" required>
                        <label class="custom-file-label bg-warning text-dark text-center w-100" for="foto_gedung" id="file-label">
                            <i class="fas fa-upload mr-2"></i>Pilih File Gedung
                        </label>
                    </div>
                    <small class="text-muted d-block mt-1">Format: JPG, PNG, JPEG (Maks. 2MB)</small>
                    <small class="text-danger" id="error-foto_gedung"></small>
                </div>
                <div class="modal-footer mt-4">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Handle file input change
        $('#foto_gedung').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            if (fileName) {
                $('#file-label').html('<i class="fas fa-file-image mr-2"></i>' + fileName);
            } else {
                $('#file-label').html('<i class="fas fa-upload mr-2"></i>Pilih File Gedung');
            }
        });

        // Reset form when modal is closed
        $('#myModal').on('hidden.bs.modal', function() {
            $('#form_create')[0].reset();
            $('.error-text').text('');
            $('#file-label').html('<i class="fas fa-upload mr-2"></i>Pilih File Gedung');
        });

        // Form submission handler (same as previous)
        $(document).on('submit', '#form_create', function(e) {
            e.preventDefault();
            $('.text-danger').text('');
            const formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                data: formData,
                contentType: false,
                processData: false,
                dataType: "json",
                beforeSend: function() {
                    $('#form_create button[type=submit]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
                },
                success: function(response) {
                    $('#form_create button[type=submit]').prop('disabled', false).html('Simpan');
                    if (response.success) {
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: "success",
                            title: "Berhasil!",
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            loadGedungCards();
                        });
                    }
                },
                error: function(xhr) {
                    $('#form_create button[type=submit]').prop('disabled', false).html('Simpan');
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(field, messages) {
                            $('#error-' + field).text(messages[0]);
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Gagal!",
                            text: xhr.responseJSON.message || 'Terjadi kesalahan saat menyimpan data',
                        });
                    }
                }
            });
        });
    });
</script>