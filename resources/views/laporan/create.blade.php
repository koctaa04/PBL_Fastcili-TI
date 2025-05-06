<div id="modal-master" class="modal-dialog" role="document">
    <div class="modal-content">
        <form method="POST" action="{{ route('laporan.store') }}" enctype="multipart/form-data" id="form_create">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Laporan Kerusakan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label for="fasilitas">Fasilitas</label>
                    <select name="id_fasilitas" class="form-control" id="fasilitas">
                        <option value="">Pilih Fasilitas</option>
                        @foreach ($fasilitas as $f)
                            <option value="{{ $f->id_fasilitas }}">{{ $f->nama_fasilitas }}</option>
                        @endforeach
                    </select>
                    <small class="text-danger error-text" id="error-id_fasilitas"></small>
                </div>
                <div class="form-group">
                    <label>Deskripsi Kerusakan</label>
                    <textarea name="deskripsi" class="form-control" required></textarea>
                    <small class="text-danger error-text" id="error-deskripsi"></small>
                </div>
                <div class="form-group">
                    <label for="foto_kerusakan">Foto Kerusakan</label>
                    <input type="file" name="foto_kerusakan" id="foto_kerusakan" class="form-control-file">
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
