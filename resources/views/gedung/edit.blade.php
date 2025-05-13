
    <div class="modal-content">
        <form method="POST" action="{{ route('gedung.update', ['id' => $gedung->id_gedung]) }}" id="form_edit" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Gedung</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Kode Gedung</label>
                    <input type="text" class="form-control" name="kode_gedung" value="{{ $gedung->kode_gedung }}" required disabled>
                    <span class="text-danger error-text" id="error-kode_gedung"></span>
                </div>
                <div class="form-group">
                    <label>Nama Gedung</label>
                    <input type="text" class="form-control" name="nama_gedung" value="{{ $gedung->nama_gedung }}" required>
                    <span class="text-danger error-text" id="error-nama_gedung"></span>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea class="form-control" name="deskripsi">{{ $gedung->deskripsi }}</textarea>
                    <span class="text-danger error-text" id="error-deskripsi"></span>
                </div>
                <div class="mb-3">
                    <label for="foto" class="form-label">Foto Gedung</label>
                    <input type="file" class="form-control" name="foto_gedung" id="foto" accept="image/*" >
                    <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah foto.</small>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>


<script>
$(document).on('submit', '#form_edit', function(e) {
    e.preventDefault();

    $('.error-text').text(''); // reset error

    var form = $(this);
    var formData = new FormData(this);

    $.ajax({
        type: "POST",
        url: form.attr('action'),
        data: formData,
        dataType: "json",
        contentType: false,
        processData: false,
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
                    text: response.messages,
                });
                location.reload();
            } else {
                alert('Gagal menyimpan data.');
            }
        },
        error: function(xhr) {
            form.find('button[type=submit]').prop('disabled', false).text('Simpan');
            if (xhr.responseJSON && xhr.responseJSON.msgField) {
                let errors = xhr.responseJSON.msgField;
                $.each(errors, function(field, messages) {
                    $('#error-' + field).text(messages[0]);
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Gagal!",
                    text: "Terjadi kesalahan saat memproses data.",
                });
            }
        }
    });
});
</script>
