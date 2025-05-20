<div id="modal-master" class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Tambah Laporan Kerusakan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('warning'))
                <div class="alert alert-warning">{{ session('warning') }}</div>
            @endif
            <form method="POST" action="{{ route('laporan.store') }}" enctype="multipart/form-data" id="form_create">
                @csrf
                <div class="form-group">
                    <label for="gedung">Gedung</label>
                    <select name="id_gedung" id="gedung"
                        class="form-control @error('id_gedung') is-invalid @enderror" required>
                        <option value="">Pilih Gedung</option>
                        @foreach ($gedungList as $g)
                            <option value="{{ $g->id_gedung }}">{{ $g->nama_gedung }}</option>
                        @endforeach
                    </select>
                    @error('id_gedung')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="ruangan">Ruangan</label>
                    <select name="id_ruangan" id="ruangan" class="form-control" required disabled>
                        <option value="">Pilih Ruangan</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="fasilitas">Fasilitas</label>
                    <select name="id_fasilitas" id="fasilitas"
                        class="form-control @error('id_fasilitas') is-invalid @enderror" required disabled>
                        <option value="">Pilih Fasilitas</option>
                    </select>
                    @error('id_fasilitas')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <!-- Notifikasi jika sudah ada laporan aktif -->
                    <div id="peringatan-serupa" class="mt-2 text-danger d-none">
                        ⚠️ Fasilitas ini sudah memiliki laporan aktif yang sedang diproses.
                    </div>
                </div>

                <div class="form-group">
                    <label>Deskripsi Kerusakan</label>
                    <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" required></textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="">
                    <label for="foto_kerusakan">Foto Kerusakan</label>
                    <input type="file" name="foto_kerusakan"
                        class="form-control-file @error('foto_kerusakan') is-invalid @enderror" id="foto_kerusakan">
                    @error('foto_kerusakan')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Kirim Laporan</button>
        </div>
    </div>
</div>

<script>
    $(document).on('submit', '#form_create', function(e) {
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

    $(document).ready(function() {
        // Ketika gedung dipilih
        $('#gedung').change(function() {
            let gedungId = $(this).val();
            $('#ruangan').html('<option value="">Loading...</option>').prop('disabled', true);
            $('#fasilitas').html('<option value="">Pilih Fasilitas</option>').prop('disabled',
                true); // reset fasilitas

            if (gedungId) {
                $.ajax({
                    url: '/get-ruangan/' + gedungId,
                    type: 'GET',
                    success: function(data) {
                        let options = '<option value="">Pilih Ruangan</option>';
                        $.each(data, function(i, ruangan) {
                            options += '<option value="' + ruangan.id_ruangan +
                                '">' + ruangan.nama_ruangan + '</option>';
                        });
                        $('#ruangan').html(options).prop('disabled', false);
                    },
                    error: function() {
                        $('#ruangan').html('<option value="">Gagal memuat ruangan</option>')
                            .prop('disabled', true);
                    }
                });
            }
        });

        // Ketika ruangan dipilih
        $('#ruangan').change(function() {
            let ruanganId = $(this).val();
            $('#fasilitas').html('<option value="">Loading...</option>').prop('disabled', true);

            if (ruanganId) {
                $.ajax({
                    url: '/get-fasilitas/' + ruanganId,
                    type: 'GET',
                    success: function(data) {
                        let options = '<option value="">Pilih Fasilitas</option>';
                        $.each(data, function(i, fasilitas) {
                            options += '<option value="' + fasilitas.id_fasilitas +
                                '">' + fasilitas.nama_fasilitas + '</option>';
                        });
                        $('#fasilitas').html(options).prop('disabled', false);
                    },
                    error: function() {
                        $('#fasilitas').html(
                            '<option value="">Gagal memuat fasilitas</option>').prop(
                            'disabled', true);
                    }
                });
            }
        });
        // Cek apakah fasilitas sudah punya laporan aktif
        $('#fasilitas').on('change', function() {
            let idFasilitas = $(this).val();
            $('#peringatan-serupa').addClass('d-none');
            if (idFasilitas) {
                $.get('/api/laporan/check-active/' + idFasilitas, function(res) {
                    if (res.exists) {
                        $('#peringatan-serupa').removeClass('d-none');
                    }
                });
            }
        });
    });
</script>
