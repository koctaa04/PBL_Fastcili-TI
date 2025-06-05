<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <form id="form_penilaian" action="{{ route('laporan.simpanPenilaian', ['id' => $laporan->id_laporan]) }}"
            method="POST">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Penilaian Laporan Kerusakan</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                {{-- Detail Laporan --}}
                <div class="card mb-3">
                    <div class="card-header bg-primary text-white">
                        <strong>Detail Laporan</strong>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <img src="{{ asset('storage/uploads/laporan_kerusakan/' . $laporan->foto_kerusakan) }}"
                                    class="img-fluid rounded shadow-sm" alt="Foto Kerusakan" style="max-height: 250px;">
                            </div>
                            <div class="col-md-8">
                                <p><strong>Trending No:</strong> {{ $trendingNo }}</p>
                                <p><strong>Gedung:</strong> {{ $laporan->fasilitas->ruangan->gedung->nama_gedung }}</p>
                                <p><strong>Ruangan:</strong> {{ $laporan->fasilitas->ruangan->nama_ruangan }}</p>
                                <p><strong>Fasilitas:</strong> {{ $laporan->fasilitas->nama_fasilitas }}</p>
                                <p><strong>Tanggal Lapor:</strong> {{ $laporan->tanggal_lapor }}</p>
                                <p><strong>Total Pelapor:</strong> {{ $laporan->pelaporLaporan->count() }}</p>
                                <div class="mb-2">
                                    <strong>Deskripsi Pelapor:</strong>
                                    <ul class="pl-3 mb-0">
                                        @foreach ($laporan->pelaporLaporan as $pp)
                                            <li>{{ $pp->deskripsi_tambahan }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Penilaian --}}
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <strong>Form Penilaian</strong>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            {{-- Tingkat Kerusakan --}}
                            <div class="col-md-6 mb-4">
                                <fieldset class="rounded p-3">
                                    <legend class="w-auto px-2 mb-2 font-weight-bold">Tingkat Kerusakan</legend>
                                    <strong>
                                        @foreach (['Kurang Parah' => 1, 'Parah' => 3, 'Sangat Parah' => 5] as $label => $val)
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="tingkat_kerusakan"
                                                    id="tingkat_kerusakan_{{ $val }}"
                                                    value="{{ $val }}" required>
                                                <label class="form-check-label"
                                                    for="tingkat_kerusakan_{{ $val }}">
                                                    {{ $label }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </strong>
                                </fieldset>
                            </div>

                            {{-- Frekuensi Digunakan --}}
                            <div class="col-md-6 mb-4">
                                <fieldset class="rounded p-3">
                                    <legend class="w-auto px-2 mb-2 font-weight-bold">Frekuensi Digunakan</legend>
                                    <strong>
                                        @foreach (['Jarang' => 1, 'Cukup Sering' => 3, 'Sering' => 5] as $label => $val)
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                    name="frekuensi_digunakan" id="frekuensi_{{ $val }}"
                                                    value="{{ $val }}" required>
                                                <label class="form-check-label" for="frekuensi_{{ $val }}">
                                                    {{ $label }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </strong>
                                </fieldset>
                            </div>

                            {{-- Dampak --}}
                            <div class="col-md-6 mb-4">
                                <fieldset class="rounded p-3">
                                    <legend class="w-auto px-2 mb-2 font-weight-bold">Dampak</legend>
                                    <strong>
                                        @foreach (['Kurang Berdampak' => 1, 'Berdampak' => 3, 'Sangat Berdampak' => 5] as $label => $val)
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="dampak"
                                                    id="dampak_{{ $val }}" value="{{ $val }}"
                                                    required>
                                                <label class="form-check-label" for="dampak_{{ $val }}">
                                                    {{ $label }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </strong>
                                </fieldset>
                            </div>

                            {{-- Potensi Bahaya --}}
                            <div class="col-md-6 mb-4">
                                <fieldset class="rounded p-3">
                                    <legend class="w-auto px-2 mb-2 font-weight-bold">Potensi Bahaya</legend>
                                    <strong>
                                        @foreach (['Tidak Berbahaya' => 1, 'Cukup Berbahaya' => 3, 'Berbahaya' => 5] as $label => $val)
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="potensi_bahaya"
                                                    id="bahaya_{{ $val }}" value="{{ $val }}"
                                                    required>
                                                <label class="form-check-label" for="bahaya_{{ $val }}">
                                                    {{ $label }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </strong>
                                </fieldset>
                            </div>

                            {{-- Estimasi Biaya --}}
                            <div class="col-md-6 mb-4">
                                <fieldset class="rounded p-3">
                                    <legend class="w-auto px-2 mb-2 font-weight-bold">Estimasi Biaya</legend>
                                    <strong>
                                        @foreach ([
                                                    '< 250k' => 1,
                                                    '250k ≤ biaya < 500k' => 2,
                                                    '500k ≤ biaya < 750k' => 3,
                                                    '750k ≤ biaya < 1500k' => 4,
                                                    '≥ 1500k' => 5,
                                                ] as $label => $val)
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="estimasi_biaya"
                                                    id="biaya_{{ $val }}" value="{{ $val }}"
                                                    required>
                                                <label class="form-check-label" for="biaya_{{ $val }}">
                                                    {{ $label }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </strong>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer px-4">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan Penilaian</button>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).on('submit', '#form_penilaian', function(e) {
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
                $('#form_penilaian button[type=submit]').prop('disabled', true).text(
                    'Menyimpan...');
            },
            success: function(response) {
                $('#form_penilaian button[type=submit]').prop('disabled', false).text('Simpan');
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
                $('#form_penilaian button[type=submit]').prop('disabled', false).text('Simpan');
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
</script>
