<div id="modal-master" class="modal-dialog" role="document">
    <div class="modal-content">
        <form method="POST" action="{{ route('users.store') }}" id="form_create">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Level</label>
                    <select class="form-control" name="id_level" id="id_level" required>
                        <option value="">--- Pilih Level ---</option>
                        @foreach ($level as $l)
                            <option value="{{ $l->id_level }}">{{ $l->nama_level }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" class="form-control" name="nama" id="nama" required>
                    <small class="text-danger error-text" id="error-nama"></small>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="text" class="form-control" name="email" id="email" required>
                    <small class="text-danger error-text" id="error-email"></small>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="text" class="form-control" name="password" id="password" required>
                    <small class="text-danger error-text" id="error-password"></small>
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
        // Reset form saat modal ditutup
        $('#myModal').on('hidden.bs.modal', function() {
            $('#form_create')[0].reset();
            $('.error-text').text('');
        });

        $(document).on('submit', '#form_create', function(e) {
            e.preventDefault();
            $('.error-text').text('');

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
                    form.find('button[type=submit]').prop('disabled', true).html(
                        '<i class="fas fa-spinner fa-spin"></i> Menyimpan...'
                    );
                },
                success: function(response) {
                    form.find('button[type=submit]').prop('disabled', false).html('Simpan');
                    if (response.success) {
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: "success",
                            title: "Berhasil!",
                            text: response.message,
                            showConfirmButton: true
                        }).then(() => {
                            if (typeof dataUser !== 'undefined') {
                                dataUser.ajax.reload();
                            }
                        });
                    }
                },
                error: function(xhr) {
                    form.find('button[type=submit]').prop('disabled', false).html('Simpan');
                    let response = xhr.responseJSON;

                    // Tampilkan error field
                    if (response && response.msgField) {
                        $.each(response.msgField, function(field, messages) {
                            $('#error-' + field).text(messages[0]);
                        });
                    }

                    Swal.fire({
                        icon: "error",
                        title: "Gagal!",
                        text: response && response.message
                            ? response.message
                            : "Terjadi kesalahan. Silakan coba lagi."
                    });
                }
            });
        });
    });
</script>

