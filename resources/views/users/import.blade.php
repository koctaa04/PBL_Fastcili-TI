<div id="modal-master" class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="{{ url('/users/import_ajax') }}" method="POST" id="form-import" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Import Data User</h5>
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
                <div class="form-group">
                    <div class="custom-file">
                        <input type="file" name="file_user" id="file_user" class="custom-file-input" required>
                        <label class="custom-file-label btn-warning text-dark" for="file_user"
                            style="cursor: pointer; width: 100%; border-radius: .25rem;">
                            <i class="fa fa-folder-open"></i> Pilih File Excel (.xlsx)
                        </label>
                    </div>
                    <small id="error-file_user" class="error-text form-text text-danger"></small>
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
    $(document).ready(function() {
        // Submit form import
        $(document).on('submit', '#form-import', function(e) {
            e.preventDefault();

            var form = this;
            var fileInput = $('#file_user')[0];
            var file = fileInput.files[0];

            $('.error-text').text(''); // reset error text

            if (!file.name.endsWith('.xlsx')) {
                $('#error-file_user').text('Hanya file Excel (.xlsx) yang diperbolehkan.');
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
                        dataUser.ajax.reload();
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
