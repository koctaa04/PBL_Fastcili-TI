<div class="modal-dialog" role="document">
    <div class="modal-content">
        <form method="POST" action="{{ url('/users/update/' . $users->id_user) }}" id="form_edit">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Data User</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Level</label>
                    <select class="form-control" name="id_level" id="id_level" required>
                        @foreach ($level as $l)
                            <option value="{{ $l->id_level }}" @if ($l->id_level == $users->id_level) selected @endif>
                                {{ $l->nama_level }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" class="form-control" name="nama" value="{{ $users->nama }}"
                        required>
                    <small class="text-danger error-text" id="error-nama"></small>

                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="text" class="form-control" name="email" value="{{ $users->email }}"
                        required>
                    <small class="text-danger error-text" id="error-email"></small>

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

        $('.error-text').text(''); // Hapus pesan error sebelumnya

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
                    }).then(() => {
                        dataUser.ajax.reload();
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal!",
                        text: response.message || 'Gagal menyimpan data.',
                    });
                }
            },
            error: function(xhr) {
                form.find('button[type=submit]').prop('disabled', false).text('Simpan');

                if (xhr.status === 422 && xhr.responseJSON?.msgField) {
                    let errors = xhr.responseJSON.msgField;
                    $.each(errors, function(field, messages) {
                        $('#error-' + field).text(messages[0]);
                    });
                    Swal.fire({
                        icon: "error",
                        title: "Validasi Gagal!",
                        text: xhr.responseJSON.message || 'Silakan periksa input Anda.',
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Terjadi Kesalahan!",
                        text: xhr.responseJSON?.message || 'Terjadi kesalahan saat memproses data.',
                    });
                }
            }
        });
    });
</script>
