@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'dashboard',
])

@section('content')
    <div class="content">
        <div class="card">
            <form method="POST" action="{{ route('pelapor.store') }}" enctype="multipart/form-data" id="form_create">
                @csrf
                <div class="modal-body">
                    <!-- Dropdown Gedung -->
                    <div class="form-group">
                        <label for="gedung">Gedung</label>
                        <select name="id_gedung" id="gedung" class="form-control" required>
                            <option value="">Pilih Gedung</option>
                            @foreach ($gedungList as $g)
                                <option value="{{ $g->id_gedung }}">{{ $g->nama_gedung }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Dropdown Ruangan (isi via AJAX) -->
                    <div class="form-group">
                        <label for="ruangan">Ruangan</label>
                        <select name="id_ruangan" id="ruangan" class="form-control" disabled required>
                            <option value="">Pilih Ruangan</option>
                        </select>
                    </div>

                    <!-- Dropdown Fasilitas (isi via AJAX) -->
                    <div class="form-group">
                        <label for="fasilitas">Fasilitas</label>
                        <select name="id_fasilitas" id="fasilitas" class="form-control" disabled required>
                            <option value="">Pilih Fasilitas</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi Kerusakan</label>
                        <textarea name="deskripsi" class="form-control" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Metode Upload Foto</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="foto_method" id="method_file"
                                value="file" checked>
                            <label class="form-check-label" for="method_file">Upload File</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="foto_method" id="method_camera"
                                value="camera">
                            <label class="form-check-label" for="method_camera">Ambil dari Kamera</label>
                        </div>
                    </div>
                    <div class="form-group" id="upload_file_group">
                        <label for="foto_kerusakan">Foto Kerusakan (Upload File)</label>
                        <input type="file" name="foto_kerusakan" class="form-control d-block mt-2" id="foto_kerusakan">
                    </div>
                    <div class="form-group d-none" id="camera_group">
                        <label>Foto Kerusakan (Kamera)</label>
                        <div id="my_camera"></div>
                        <br>
                        <input type="button" class="btn btn-sm btn-success" value="Ambil Foto" onClick="take_snapshot()">
                        <input type="hidden" name="image" class="image-tag">
                        <div id="results" class="mt-2"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#gedung').change(function() {
                let gedungId = $(this).val();
                $('#ruangan').html('<option value="">Loading...</option>').prop('disabled', true);
                $('#fasilitas').html('<option value="">Pilih Fasilitas</option>').prop('disabled', true);
                if (gedungId) {
                    $.ajax({
                        url: '/lapor_kerusakan/get-ruangan/' + gedungId,
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

            $('#ruangan').change(function() {
                let ruanganId = $(this).val();
                $('#fasilitas').html('<option value="">Loading...</option>').prop('disabled', true);
                if (ruanganId) {
                    $.ajax({
                        url: '/lapor_kerusakan/get-fasilitas/' + ruanganId,
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

            // Toggle metode upload foto
            $('input[name="foto_method"]').change(function() {
                if ($(this).val() === 'file') {
                    $('#upload_file_group').removeClass('d-none');
                    $('#camera_group').addClass('d-none');
                    Webcam.reset();
                } else {
                    $('#upload_file_group').addClass('d-none');
                    $('#camera_group').removeClass('d-none');
                    setTimeout(function() {
                        Webcam.set({
                            width: 320,
                            height: 240,
                            image_format: 'jpeg',
                            jpeg_quality: 90
                        });
                        Webcam.attach('#my_camera');
                    }, 300);
                }
            });

            $(document).on('submit', '#form_create', function(e) {
                e.preventDefault();
                $('.error-text').text('');
                const formData = new FormData(this);

                if ($('input[name="foto_method"]:checked').val() === 'camera') {
                    let data_uri = $(".image-tag").val();
                    if (data_uri) {
                        formData.append('foto_kerusakan_camera', data_uri);
                    }
                }

                $.ajax({
                    type: "POST",
                    url: $(this).attr('action'),
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: "json",
                    beforeSend: function() {
                        $('#form_create button[type=submit]').prop('disabled', true).text(
                            'Menyimpan...');
                    },
                    success: function(response) {
                        $('#form_create button[type=submit]').prop('disabled', false).text(
                            'Simpan');
                        if (response.success) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: "success",
                                title: "Berhasil!",
                                text: response.messages,
                            });
                            window.location.href = '/pelapor';
                        } else {
                            alert('Gagal menyimpan data.');
                        }
                    },
                    error: function(xhr) {
                        $('#form_create button[type=submit]').prop('disabled', false).text(
                            'Simpan');
                        if (xhr.responseJSON && xhr.responseJSON.msgField) {
                            let errors = xhr.responseJSON.msgField;
                            $.each(errors, function(field, messages) {
                                $('#error-' + field).text(messages[0]);
                            });
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Gagal!",
                                text: xhr.responseJSON ? xhr.responseJSON.messages :
                                    'Terjadi kesalahan',
                            });
                        }
                    }
                });
            });
        });

        function take_snapshot() {
            Webcam.snap(function(data_uri) {
                $(".image-tag").val(data_uri);
                document.getElementById('results').innerHTML = '<img src="' + data_uri + '"/>';
            });
        }
    </script>
@endpush
