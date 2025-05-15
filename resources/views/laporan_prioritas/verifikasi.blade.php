<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <form id="formVerifikasi" method="POST" action="{{ url('/verifikasi-perbaikan') }}">
            @csrf
            <input type="hidden" name="id_laporan" value="{{ $laporan->id_laporan }}">
            <input type="hidden" name="id_penugasan" value="{{ $laporan->penugasan->id_penugasan ?? '' }}">

            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Verifikasi Perbaikan</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div>
                    <strong>Status Perbaikan:</strong> {{ $laporan->penugasan->status_perbaikan ?? '-' }}<br>
                    <strong>Teknisi:</strong> {{ $laporan->penugasan->user->nama ?? '-' }}
                </div>
                <div class="mt-3">
                    <label for="keterangan">Keterangan (jika menolak):</label>
                    <textarea name="keterangan" class="form-control" rows="3"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" name="verifikasi" value="setuju" class="btn btn-success">Setuju
                    (Selesai)</button>
                <button type="submit" name="verifikasi" value="tolak" class="btn btn-danger">Tolak</button>
            </div>
        </form>
    </div>
</div>

<script>
    let clickedVerifikasi = null;

    $(document).on('click', 'button[name="verifikasi"]', function() {
        clickedVerifikasi = $(this).val(); // simpan nilai "setuju" atau "tolak"
    });

    $(document).on('submit', '#formVerifikasi', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        // Tambahkan secara eksplisit value dari tombol yang diklik
        formData.append('verifikasi', clickedVerifikasi);

        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            beforeSend: function() {
                $('#formVerifikasi button[type=submit]').prop('disabled', true).text(
                'Menyimpan...');
            },
            success: function(response) {
                $('#formVerifikasi button[type=submit]').prop('disabled', false).text('Okay');
                if (response.success) {
                    $('#myModal').modal('hide');
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil!",
                        text: response.messages,
                    }).then(() => {
                        window.location.href = '/mabac';
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal!",
                        text: response.messages,
                    });
                }
            },
            error: function(xhr) {
                $('#formVerifikasi button[type=submit]').prop('disabled', false).text('Okay');
                Swal.fire({
                    icon: "error",
                    title: "Error!",
                    text: "Terjadi kesalahan saat menyimpan data.",
                });
            }
        });
    });
</script>
