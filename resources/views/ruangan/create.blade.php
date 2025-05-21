<div id="modal-master" class="modal-dialog" role="document">
    <div class="modal-content">
        <form method="POST" action="{{ route('ruangan.store') }}" id="form_create">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Ruangan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Gedung</label>
                    <select class="form-control" name="id_gedung" id="id_gedung" required>
                        <option value="">--- Pilih Gedung ---</option>
                        @foreach ($gedung as $g)
                            <option value="{{ $g->id_gedung }}">{{ $g->nama_gedung }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Kode Ruangan</label>
                    <input type="text" class="form-control" name="kode_ruangan" id="kode_ruangan" required>
                    <small class="text-danger error-text" id="error-kode_ruangan"></small>
                </div>
                <div class="form-group">
                    <label>Nama Ruangan</label>
                    <input type="text" class="form-control" name="nama_ruangan" id="nama_ruangan" required>
                    <small class="text-danger error-text" id="error-nama_ruangan"></small>
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
        // Reset form ketika modal ditutup
        $('#myModal').on('hidden.bs.modal', function() {
            $('#form_create')[0].reset();
            $('.error-text').text('');
        });

        $(document).on('submit', '#form_create', function(e) {
            e.preventDefault();
            $('.error-text').text(''); // Reset error messages

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
                            loadRuanganCards(); // Memuat ulang data ruangan
                        });
                    }
                },
                error: function(xhr) {
                    form.find('button[type=submit]').prop('disabled', false).html('Simpan');
                    if (xhr.status === 422) { // Validation error
                        let errors = xhr.responseJSON.msgField;
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
