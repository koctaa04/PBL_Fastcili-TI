<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Edit Data Ruangan</h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form method="POST" action="{{ url('/ruangan/update/' . $ruangan->id_ruangan) }}" id="form_edit" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label for="id_gedung">Nama Gedung</label>
                    <select class="form-control" name="id_gedung" id="id_gedung" required>
                        @foreach ($gedung as $g)
                            <option value="{{ $g->id_gedung }}" @if ($g->id_gedung == $ruangan->id_gedung) selected @endif>
                                {{ $g->nama_gedung }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-danger" id="error-id_gedung"></small>
                </div>
                <div class="form-group mt-3">
                    <label for="kode_ruangan">Kode Ruangan</label>
                    <input type="text" class="form-control" name="kode_ruangan" id="kode_ruangan" 
                           value="{{ $ruangan->kode_ruangan }}" required>
                    <small class="text-danger" id="error-kode_ruangan"></small>
                </div>
                <div class="form-group mt-3">
                    <label for="nama_ruangan">Nama Ruangan</label>
                    <input type="text" class="form-control" name="nama_ruangan" id="nama_ruangan" 
                           value="{{ $ruangan->nama_ruangan }}" required>
                    <small class="text-danger" id="error-nama_ruangan"></small>
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
    // Reset form when modal is closed
    $('#myModal').on('hidden.bs.modal', function() {
        $('.text-danger').text('');
    });

    $(document).on('submit', '#form_edit', function(e) {
        e.preventDefault();
        $('.text-danger').text(''); // Clear previous errors

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
                form.find('button[type=submit]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
            },
            success: function(response) {
                form.find('button[type=submit]').prop('disabled', false).html('Simpan');
                if (response.success) {
                    $('#myModal').modal('hide');
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil!",
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        loadRuanganCards();
                    });
                }
            },
            error: function(xhr) {
                form.find('button[type=submit]').prop('disabled', false).html('Simpan');
                if (xhr.status === 422) { // Validation error
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, messages) {
                        $('#error-' + field).text(messages[0]);
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal!",
                        text: xhr.responseJSON.message || 'Terjadi kesalahan saat menyimpan data',
                    });
                }
            }
        });
    });
});
</script>