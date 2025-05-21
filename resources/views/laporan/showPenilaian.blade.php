<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <form id="form_edit" action="{{ url('lapor_kerusakan.simpanPenilaian', $laporan->id_laporan) }}" method="POST">
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
                                <p><strong>Trending No:</strong> {{ $laporan->trending_no ?? '-' }}</p>
                                <p><strong>Gedung:</strong> {{ $laporan->fasilitas->ruangan->gedung->nama_gedung }}</p>
                                <p><strong>Ruangan:</strong> {{ $laporan->fasilitas->ruangan->nama_ruangan }}</p>
                                <p><strong>Fasilitas:</strong> {{ $laporan->fasilitas->nama_fasilitas }}</p>
                                <p><strong>Tanggal Lapor:</strong> {{ $laporan->tanggal_lapor }}</p>
                                <p><strong>Total Pelapor:</strong> {{ $laporan->pelaporLaporan->count() }}</p>
                                <p><strong>Skor Trending:</strong> {{ $laporan->skor_trending ?? '-' }}</p>
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
                            <div class="col-md-6 form-group mb-4">
                                <label><strong>Tingkat Kerusakan</strong></label>
                                @foreach (['Kurang Parah' => 1, 'Parah' => 3, 'Sangat Parah' => 5] as $label => $val)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tingkat_kerusakan"
                                            value="{{ $val }}" required>
                                        <label class="form-check-label">{{ $label }}</label>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Frekuensi Digunakan --}}
                            <div class="col-md-6 form-group mb-4">
                                <label><strong>Frekuensi Digunakan</strong></label>
                                @foreach (['Jarang' => 1, 'Cukup Sering' => 3, 'Sering' => 5] as $label => $val)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="frekuensi_digunakan"
                                            value="{{ $val }}" required>
                                        <label class="form-check-label">{{ $label }}</label>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Dampak --}}
                            <div class="col-md-6 form-group mb-4">
                                <label><strong>Dampak</strong></label>
                                @foreach (['Kurang Berdampak' => 1, 'Berdampak' => 3, 'Sangat Berdampak' => 5] as $label => $val)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="dampak"
                                            value="{{ $val }}" required>
                                        <label class="form-check-label">{{ $label }}</label>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Potensi Bahaya --}}
                            <div class="col-md-6 form-group mb-4">
                                <label><strong>Potensi Bahaya</strong></label>
                                @foreach (['Tidak Berbahaya' => 0, 'Kurang Berbahaya' => 1, 'Berbahaya' => 3, 'Sangat Berbahaya' => 5] as $label => $val)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="potensi_bahaya"
                                            value="{{ $val }}" required>
                                        <label class="form-check-label">{{ $label }}</label>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Estimasi Biaya --}}
                            <div class="col-md-6 form-group mb-4">
                                <label><strong>Estimasi Biaya</strong></label>
                                @foreach ([
        '< 250k' => 1,
        '250k ≤ biaya < 500k' => 2,
        '500k ≤ biaya < 750k' => 3,
        '750k ≤ biaya < 1500k' => 4,
        '≥ 1500k' => 5,
    ] as $label => $val)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="estimasi_biaya"
                                            value="{{ $val }}" required>
                                        <label class="form-check-label">{{ $label }}</label>
                                    </div>
                                @endforeach
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
    $(document).on('submit', '#form_edit', function(e) {
        e.preventDefault();

        $('.error-text').text('');

        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: "json",
            beforeSend: function() {
                $('#form_edit button[type=submit]').prop('disabled', true).text('Menyimpan...');
            },
            success: function(response) {
                $('#form_edit button[type=submit]').prop('disabled', false).text('Simpan');
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
                $('#form_edit button[type=submit]').prop('disabled', false).text('Simpan');
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
