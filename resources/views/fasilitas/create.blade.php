<div id="modal-master" class="modal-dialog" role="document">
    <div class="modal-content">
        <form method="POST" action="{{ route('fasilitas.store') }}" id="form_create">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Fasilitas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="gedung">Gedung</label>
                    <select name="id_gedung" id="gedung" class="form-control" required>
                        <option value="">Pilih Gedung</option>
                        @foreach ($gedung as $g)
                            <option value="{{ $g->id_gedung }}">{{ $g->nama_gedung }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="ruangan">Ruangan</label>
                    <select name="id_ruangan" id="ruangan" class="form-control" disabled required>
                        <option value="">Pilih Ruangan</option>
                        @foreach ($ruangan as $r)
                            <option value="{{ $r->id_ruangan }}">{{ $r->nama_ruangan }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Nama Fasilitas</label>
                    <input type="text" class="form-control" name="nama_fasilitas" id="nama_fasilitas" required>
                    <small class="text-danger error-text" id="error-nama_fasilitas"></small>
                </div>
                <div class="form-group">
                    <label>Jumlah</label>
                    <input type="text" class="form-control" name="jumlah" id="jumlah" required>
                    <small class="text-danger error-text" id="error-jumlah"></small>
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
                        text: response.message,
                        showConfirmButton: true
                    });
                    loadFasilitasCards();
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal!",
                        text: response.message,
                    });
                }
            },
            error: function(xhr) {
                $('#form_create button[type=submit]').prop('disabled', false).text('Simpan');
                if (xhr.responseJSON && xhr.responseJSON.msgField) {
                    let errors = xhr.responseJSON.msgField;
                    $.each(errors, function(field, message) {
                        $('#error-' + field).text(message[0]);
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal!",
                        text: response.message,
                    });
                }
            }
        });
    });

    $(document).ready(function() {
        $('#gedung').change(function() {
            let gedungId = $(this).val();
            $('#ruangan').html('<option value="">Memuat...</option>').prop('disabled', true);

            if (gedungId) {
                $.ajax({
                    url: "{{ route('fasilitas.getRuangan', '') }}/" + gedungId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        console.log('Response:', data); // Untuk debugging
                        let options = '<option value="">Pilih Ruangan</option>';

                        if (data && data.length > 0) {
                            $.each(data, function(i, ruangan) {
                                options += '<option value="' + ruangan.id_ruangan +
                                    '">' +
                                    ruangan.nama_ruangan + '</option>';
                            });
                        } else {
                            options = '<option value="">Tidak ada ruangan</option>';
                        }

                        $('#ruangan').html(options).prop('disabled', false);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', status, error); // Untuk debugging
                        $('#ruangan').html('<option value="">Gagal memuat ruangan</option>')
                            .prop('disabled', true);
                    }
                });
            } else {
                $('#ruangan').html('<option value="">Pilih Gedung terlebih dahulu</option>')
                    .prop('disabled', true);
            }
        });
    });
</script>