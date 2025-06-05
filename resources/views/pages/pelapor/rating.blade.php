<style>
    .rating {
        direction: rtl;
        unicode-bidi: bidi-override;
        display: inline-flex;
    }

    .rating input[type="radio"] {
        display: none;
    }

    .rating label {
        font-size: 2rem;
        color: #ddd;
        cursor: pointer;
    }

    .rating input[type="radio"]:checked~label {
        color: gold;
    }

    .rating label:hover,
    .rating label:hover~label {
        color: orange;
    }
</style>


<div id="modal-master" class="modal-dialog" role="document">
    <div class="modal-content">
        <form method="POST" action="{{ route('pelapor.rating', ['id' => $laporan->id_user]) }}" id="form_create">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Beri Rating dan Ulasan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <style>
                .rating {
                    direction: rtl;
                    unicode-bidi: bidi-override;
                    display: inline-flex;
                    font-size: 1.8rem;
                }

                .rating input[type="radio"] {
                    display: none;
                }

                .rating label {
                    color: #ccc;
                    cursor: pointer;
                    padding: 0 4px;
                    transition: color 0.2s;
                }

                .rating input[type="radio"]:checked~label {
                    color: gold;
                }

                .rating label:hover,
                .rating label:hover~label {
                    color: orange;
                }
            </style>

            <div class="modal-body">
                <label for="rating_pengguna">Berikan Rating:</label>
                <div class="form-group">
                    <div class="rating">
                        @for ($i = 5; $i >= 1; $i--)
                            <input type="radio" id="star{{ $i }}" name="rating_pengguna"
                                value="{{ $i }}" {{ old('rating_pengguna') == $i ? 'checked' : '' }} />
                            <label for="star{{ $i }}" title="{{ $i }} stars">★</label>
                        @endfor
                    </div>
                    @error('rating_pengguna')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mt-3">
                    <label for="feedback_pengguna">Ulasan</label>
                    <textarea class="form-control" name="feedback_pengguna" id="feedback_pengguna" rows="3" required>{{ old('feedback_pengguna') }}</textarea>
                    @error('feedback_pengguna')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
<style>
    .rating {
        display: inline-block;
        direction: rtl;
    }

    .rating>input {
        display: none;
    }

    .rating>label {
        position: relative;
        width: 1em;
        font-size: 2rem;
        color: #ddd;
        cursor: pointer;
        float: right;
    }

    .rating>label::before {
        content: "\2605";
        position: absolute;
        opacity: 0;
        display: block;
    }

    .rating>label:hover:before,
    .rating>label:hover~label:before,
    .rating>input:checked~label:before {
        opacity: 1 !important;
        color: gold;
    }
</style>

<script>
    $(document).ready(function() {

        $('#form_create').on('submit', function(e) {
            e.preventDefault();
            $('.error-text').text('');

            console.log("Data yang dikirim:", $(this).serialize());

            $.ajax({
                type: "PUT",
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: "json",
                beforeSend: function() {
                    $('#form_create button[type=submit]').prop('disabled', true).text(
                        'Menyimpan...');
                },
                success: function(response) {
                    $('#form_create button[type=submit]').prop('disabled', false).text(
                        'Simpan');
                    if (response.success) {
                        $('#modal-master').modal('hide');
                        Swal.fire({
                            icon: "success",
                            title: "Berhasil!",
                            text: response.message,
                        });
                        location.reload();
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Gagal!",
                            text: "Terjadi kesalahan saat menyimpan.",
                        });
                    }
                },
                error: function(xhr) {
                    $('#form_create button[type=submit]').prop('disabled', false).text(
                        'Simpan');
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function(field, messages) {
                            $('#error-' + field).text(messages[0]);
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Gagal!",
                            text: "Kesalahan server.",
                        });
                    }
                }
            });
        });
    });
</script>
