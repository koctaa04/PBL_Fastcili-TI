<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <form id="form_penugasan" method="POST" action="{{ url('/penugasan-teknisi') }}">
            @csrf
            <input type="hidden" name="id_laporan" value="{{ $laporan->id_laporan }}">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Penugasan Teknisi</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="id_user" class="form-label">Pilih Teknisi</label>
                        <select name="id_user" class="form-control" required>
                            @foreach ($teknisi as $t)
                                <option value="{{ $t->id_user }}">{{ $t->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mt-3">
                        <strong>Detail Laporan:</strong>
                        <p>Deskripsi: {{ $laporan->deskripsi }}</p>
                        <p>Ruangan: {{ $laporan->fasilitas->ruangan->nama_ruangan ?? '-' }}</p>
                        <p>Gedung: {{ $laporan->fasilitas->ruangan->gedung->nama_gedung ?? '-' }}</p>
                        <p>Fasilitas: {{ $laporan->fasilitas->nama_fasilitas ?? '-' }}</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Tugaskan</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).on('submit', '#form_penugasan', function(e) {
        e.preventDefault();

        $('.error-text').text('');
        const formData = new FormData(this);

        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            beforeSend: function() {
                $('#form_penugasan button[type=submit]').prop('disabled', true).text('Menyimpan...');
            },
            success: function(response) {
                $('#form_penugasan button[type=submit]').prop('disabled', false).text('Okay');
                if (response.success) {
                    $('#myModal').modal('hide');
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil!",
                        text: response.messages,
                    }).then(() => {
                        // Redirect kembali ke halaman MABAC atau reload halaman
                        // window.location.href = '/mabac';
                        location.reload();
                    });
                } else {
                    alert('Gagal menyimpan data.');
                }
            },
            error: function(xhr) {
                $('#form_penugasan button[type=submit]').prop('disabled', false).text('Okay');
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, messages) {
                        $('#error-' + field).text(messages[0]);
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal!",
                        text: "Terjadi kesalahan saat menyimpan data.",
                    });
                }
            }
        });
    });
</script>
