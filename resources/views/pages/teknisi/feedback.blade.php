<div id="modal-master" class="modal-dialog" role="document">
    <div class="modal-content">
        <form method="POST" enctype="multipart/form-data"
            action="{{ route('teknisi.feedbacksimpan', ['id' => $penugasan->id_penugasan]) }}" id="form_create">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Dokumentasi Perbaikan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group mt-3">
                    <label for="dokumentasi">Dokumentasi</label>
                    <input type="file" name="dokumentasi" id="dokumentasi" class="form-control" required>
                </div>

                <div class="form-group mt-3">
                    <label for="catatan_teknisi">Catatan</label>
                    <input type="text" name="catatan_teknisi" id="catatan_teknisi" class="form-control" required>
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
    $(document).ready(function() {
        $('#form_create').on('submit', function(e) {
            e.preventDefault();
            $('.error-text').text('');

            const formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                data: formData,
                dataType: "json",
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#form_create button[type=submit]').prop('disabled', true).text(
                        'Menyimpan...');
                },
                success: function(response) {
                    $('#form_create button[type=submit]').prop('disabled', false).text(
                        'Simpan');
                    if (response.success) {
                        $('#modal-master').modal('hide');
                        Swal.fire({
                            icon: "success",
                            title: "Berhasil!",
                            text: response.message,
                        });
                        location.reload();
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Gagal!",
                            text: "Terjadi kesalahan saat menyimpan.",
                        });
                    }
                },
                error: function(xhr) {
                    $('#form_create button[type=submit]').prop('disabled', false).text(
                        'Simpan');
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function(field, messages) {
                            $('#error-' + field).text(messages[0]);
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Gagal!",
                            text: "Kesalahan server.",
                        });
                    }
                }
            });
        });
    });
</script>
