<div class="modal-dialog" role="document">
    <div class="modal-content">
        <form method="POST" action="{{ url('/fasilitas/update/' . $fasilitas->id_fasilitas) }}" id="form_edit">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Fasilitas</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Fasilitas</label>
                    <input type="text" class="form-control" name="nama_fasilitas" value="{{ $fasilitas->nama_fasilitas }}"
                        required>
                </div>
                <div class="form-group">
                    <label>Jumlah</label>
                    <input type="text" class="form-control" name="jumlah" value="{{ $fasilitas->jumlah }}"
                        required>
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
    $(document).on('submit', '#form_edit', function(e) {
        e.preventDefault();

        $('.error-text').text('');

        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: "json",
            beforeSend: function() {
                $('#form_edit button[type=submit]').prop('disabled', true).text('Menyimpan...');
            },
            success: function(response) {
                $('#form_edit button[type=submit]').prop('disabled', false).text('Simpan');
                if (response.success) {
                    $('#myModal').modal('hide');
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil!",
                        text: response.message,
                        timer: 3000,
                        showConfirmButton: true
                    });
                    loadFasilitasCards();
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal!",
                        text: response.message,
                    });
                }
            },
            error: function(xhr) {
                $('#form_edit button[type=submit]').prop('disabled', false).text('Simpan');
                if (xhr.responseJSON && xhr.responseJSON.msgField) {
                    let errors = xhr.responseJSON.msgField;
                    $.each(errors, function(field, message) {
                        $('#error-' + field).text(message[0]);
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal!",
                        text: response.message,
                    });
                }
            }
        });
    });
</script>
