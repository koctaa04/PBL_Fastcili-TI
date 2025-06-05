<div class="modal-dialog" role="document">
    <div class="modal-content">
        <form method="POST" action="{{ url('/perbaikan/update/' . $laporan_kerusakan->id_penugasan) }}" id="form_edit"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Perbaikan</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                @if ($laporan_kerusakan->komentar_sarpras != null)
                    <div class="alert alert-info" role="alert">
                        Keterangan: {{ $laporan_kerusakan->komentar_sarpras }}
                    </div>
                @endif
                <div class="form-group">
                    <label>Catatan Teknisi</label>
                    <textarea name="catatan_teknisi" class="form-control" rows="3">{{ $laporan_kerusakan->catatan_teknisi }}</textarea>
                </div>

                <div class="">
                    <label for="dokumentasi_perbaikan">Dokumentasi Perbaikan</label>
                    <input type="file" name="dokumentasi" id="dokumentasi_perbaikan" class="form-control-file">
                    <small class="text-danger" id="error-dokumentasi"></small>
                    @if ($laporan_kerusakan->dokumentasi != null)
                        <small class="form-text text-muted">*Kosongkan jika tidak ingin mengubah foto.</small>
                    @endif
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
    $('#form_edit').on('submit', function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            beforeSend: function() {
                $('#form_edit button[type=submit]').prop('disabled', true).text('Menyimpan...');
                $('.text-danger').text(''); // Bersihkan error sebelumnya
            },
            success: function(response) {
                $('#form_edit button[type=submit]').prop('disabled', false).text('Simpan');

                if (response.success) {
                    $('#myModal').modal('hide');
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil!",
                        text: response.messages,
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    // Tampilkan error validasi per field jika ada
                    if (response.msgField) {
                        $.each(response.msgField, function(field, messages) {
                            $('#error-' + field).text(messages[0]);
                        });
                    }

                    Swal.fire({
                        icon: "error",
                        title: "Gagal!",
                        text: response.messages || 'Terjadi kesalahan.',
                    });
                }
            },
            error: function(xhr) {
                $('#form_edit button[type=submit]').prop('disabled', false).text('Simpan');

                let message = 'Terjadi kesalahan saat mengirim data.';
                if (xhr.status === 413) {
                    message = 'Ukuran file terlalu besar. Maksimum 2MB.';
                } else if (xhr.responseJSON && xhr.responseJSON.messages) {
                    message = xhr.responseJSON.messages;
                }

                Swal.fire({
                    icon: "error",
                    title: "Gagal!",
                    text: message
                });
            }
        });
    });
</script>
