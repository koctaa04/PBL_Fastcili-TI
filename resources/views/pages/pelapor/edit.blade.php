@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'dashboard',
])

@section('content')
    <div class="content">
        <div class="card">
            <form method="POST" action="{{ route('pelapor.update', ['id' => $laporan->id_laporan]) }}" id="form_edit"
                enctype="multipart/form-data">

                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Level</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                </div>
                <div class="modal-body">
                    <div class="alert alert-info" role="alert">
                        Keterangan: {{ $laporan->keterangan }}
                    </div>
                    <div class="form-group">
                        <label>Deskripsi Kerusakan</label>
                        <textarea name="deskripsi" class="form-control" required>{{ $laporan->deskripsi }}</textarea>
                        <small class="text-danger error-text" id="error-deskripsi"></small>
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
                    <small>Kosongkan jika tidak ingin mengubah foto</small>
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
            $(document).on('submit', '#form_edit', function(e) {
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
                        $('#form_edit button[type=submit]').prop('disabled', true).text(
                            'Menyimpan...');
                    },
                    success: function(response) {
                        $('#form_edit button[type=submit]').prop('disabled', false).text(
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
                        $('#form_edit button[type=submit]').prop('disabled', false).text(
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
                                text: response.messages,
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
