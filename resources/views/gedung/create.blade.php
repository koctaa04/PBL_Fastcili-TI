<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Tambah Gedung</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">

        <form method="POST" action="{{ route('gedung.store') }}" enctype="multipart/form-data" id="form_create">
            @csrf
            <div class="form-group">
                <label>Nama Gedung</label>
                <input type="text" name="nama_gedung" class="form-control" required>
            </div>
            <div class="form-group mt-2">
                <label>Kode Gedung</label>
                <input type="text" name="kode_gedung" class="form-control" required>
            </div>
            <div class="form-group mt-2">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="foto" class="form-label">Foto Gedung</label>
                <input type="file" class="form-control" name="foto_gedung" id="foto" accept="image/*" required>
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

        $('.error-text').text('');

        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: "json",
            beforeSend: function() {
                $('#form_create button[type=submit]').prop('disabled', true).text('Menyimpan...');
            },
            success: function(response) {
                $('#form_create button[type=submit]').prop('disabled', false).text('Simpan');
                if (response.success) {
                    $('#myModal').modal('hide');
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil!",
                        text: response.messages,
                    });
                    location.reload();
                } else {
                    alert('Gagal menyimpan data.');
                }
            },
            error: function(xhr) {
                $('#form_create button[type=submit]').prop('disabled', false).text('Simpan');
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
    });
</script>
