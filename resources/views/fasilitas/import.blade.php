<div id="modal-master" class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="{{ url('/fasilitas/import_ajax') }}" method="POST" id="form-import" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Import Data Fasilitas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Download Template</label>
                    <a href="{{ asset('template_user.xlsx') }}" class="btn btn-info btn-sm" download>
                        <i class="fa fa-file-excel"></i> Download
                    </a>
                    <small id="error-level_id" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group mt-3">
                    <!-- Custom File Input -->
                    <label for="file_fasilitas" class="d-block mb-2">File Import Fasiltas</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="file_fasilitas" id="file_fasilitas"
                            required>
                        <label class="custom-file-label bg-warning text-dark text-center w-100" for="file_fasilitas"
                            id="file-label">
                            <i class="fas fa-upload mr-2"></i>Pilih File (.xlsx)
                        </label>
                    </div>
                    <small class="text-muted d-block mt-1">Format: Excel (.xlsx)</small>
                    <small class="text-danger" id="error-file_fasilitas"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-danger">Batal</button>
                <button type="submit" class="btn btn-primary btn-success">Upload</button>
            </div>
        </form>
    </div>
</div>
<script>
    // Handle file input change
    $('#file_fasilitas').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        if (fileName) {
            $('#file-label').html('<i class="fas fa-file-image mr-2"></i>' + fileName);
        } else {
            $('#file-label').html('<i class="fas fa-upload mr-2"></i>Pilih File Gedung');
        }
    });
    $(document).ready(function() {
        // Submit form import
        $(document).on('submit', '#form-import', function(e) {
            e.preventDefault();

            var form = this;
            var fileInput = $('#file_fasilitas')[0];
            var file = fileInput.files[0];

            $('.error-text').text(''); // reset error text

            if (!file.name.endsWith('.xlsx')) {
                $('#error-file_fasilitas').text('Hanya file Excel (.xlsx) yang diperbolehkan.');
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Format file tidak valid. Harus berupa .xlsx'
                });
                return;
            }

            // Siapkan FormData
            var formData = new FormData(form);

            // Kirim AJAX
            $.ajax({
                type: form.method,
                url: form.action,
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#form-import button[type=submit]').prop('disabled', true).text(
                        'Mengupload...');
                },
                success: function(response) {
                    $('#form-import button[type=submit]').prop('disabled', false).text(
                        'Upload');

                    if (response.status || response.success) {
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message || response.messages,
                        });
                        loadFasilitasCards();;
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: response.message ||
                                'Terjadi kesalahan saat mengupload file',
                        });

                        if (response.msgField) {
                            $.each(response.msgField, function(field, messages) {
                                $('#error-' + field).text(messages[0]);
                            });
                        }
                    }
                },
                error: function(xhr) {
                    $('#form-import button[type=submit]').prop('disabled', false).text(
                        'Upload');

                    let message = 'Terjadi kesalahan saat mengupload file';
                    let listErrors = '';

                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }

                        if (xhr.responseJSON.errors && Array.isArray(xhr.responseJSON
                                .errors)) {

                            xhr.responseJSON.errors.forEach(function(err) {
                                err;
                            });
                        }
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        html: message +
                            listErrors // pakai `html` bukan `text` untuk bisa tampil <ul>
                    });

                    // Tampilkan error per field
                    if (xhr.responseJSON && xhr.responseJSON.msgField) {
                        $.each(xhr.responseJSON.msgField, function(field, messages) {
                            $('#error-' + field).text(messages[0]);
                        });
                    }
                }

            });
        });
    });
</script>
