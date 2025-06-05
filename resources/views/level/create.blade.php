<div id="modal-master" class="modal-dialog" role="document">
    <div class="modal-content">
        <form method="POST" action="{{ route('level.store') }}" id="form_create">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Level</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Kode Level</label>
                    <input type="text" class="form-control" name="kode_level" id="kode_level" required>
                    <small class="text-danger error-text" id="error-kode_level"></small>
                </div>
                <div class="form-group">
                    <label>Nama Level</label>
                    <input type="text" class="form-control" name="nama_level" id="nama_level" required>
                    <small class="text-danger error-text" id="error-nama_level"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).on('submit', '#form_create', function(e) {
        e.preventDefault();

        $('.error-text').text(''); // Hapus error sebelumnya

        let form = $(this);

        $.ajax({
            type: "POST",
            url: form.attr('action'),
            data: form.serialize(),
            dataType: "json",
            beforeSend: function() {
                form.find('button[type=submit]').prop('disabled', true).text('Menyimpan...');
            },
            success: function(response) {
                form.find('button[type=submit]').prop('disabled', false).text('Simpan');
                if (response.success) {
                    $('#myModal').modal('hide');
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil!",
                        text: response.message,
                        showConfirmButton: true
                    }).then(() => {
                        dataLevel.ajax.reload(); // Reload datatable
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal!",
                        text: response.message || 'Terjadi kesalahan saat menyimpan data.',
                    });
                }
            },
            error: function(xhr) {
                form.find('button[type=submit]').prop('disabled', false).text('Simpan');
                if (xhr.status === 422 && xhr.responseJSON?.msgField) {
                    let errors = xhr.responseJSON.msgField;
                    $.each(errors, function(field, message) {
                        $('#error-' + field).text(message[0]);
                    });
                    Swal.fire({
                        icon: "error",
                        title: "Validasi Gagal!",
                        text: xhr.responseJSON.message || 'Periksa kembali input Anda.',
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal!",
                        text: xhr.responseJSON?.message || 'Terjadi kesalahan saat memproses data.',
                    });
                }
            }
        });
    });
</script>
