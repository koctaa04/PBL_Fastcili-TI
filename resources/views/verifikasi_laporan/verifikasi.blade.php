<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <form method="POST" action="{{ route('laporan.verifikasi', ['id' => $laporan->id_laporan]) }}" id="form_create">
            @csrf
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalLabel">📝 Penilaian Bobot Laporan</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="mb-4">
                    <h6><strong>Deskripsi Kriteria Penilaian</strong></h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th>Kasus</th>
                                    <th>Pelapor</th>
                                    <th>Fasilitas</th>
                                    <th>Foto</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $laporan->deskripsi }}</td>
                                    <td>{{ $laporan->user->nama }}</td>
                                    <td>{{ $laporan->fasilitas->nama_fasilitas }}</td>
                                    <td><img src="{{ asset('storage/uploads/laporan_kerusakan/' . $laporan->foto_kerusakan) }}"
                                            alt="Foto Kerusakan" width="200"
                                            onerror="this.onerror=null;this.src='{{ asset('images/fasilitas-rusak.jpeg') }}';">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="container-fluid">
                    <h6 class="text-secondary mt-3">1. Informasi Kerusakan</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label><i class="fas fa-tools"></i> Tingkat Kerusakan</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tingkat_kerusakan"
                                    id="tingkat_kerusakan" value="1">
                                <label class="form-check-label" for="kerusakan1">Kurang Parah</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tingkat_kerusakan"
                                    id="tingkat_kerusakan" value="3">
                                <label class="form-check-label" for="kerusakan2">Parah</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tingkat_kerusakan"
                                    id="tingkat_kerusakan" value="5">
                                <label class="form-check-label" for="kerusakan3">Sangat Parah</label>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Frekuensi Digunakan</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="frekuensi_digunakan"
                                    id="frekuensi_digunakan" value="1">
                                <label class="form-check-label" for="frekuensi1">Jarang</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="frekuensi_digunakan"
                                    id="frekuensi_digunakan" value="3">
                                <label class="form-check-label" for="frekuensi2">Cukup Sering</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="frekuensi_digunakan"
                                    id="frekuensi_digunakan" value="5">
                                <label class="form-check-label" for="frekuensi3">Sering</label>
                            </div>
                        </div>
                    </div>

                    <h6 class="text-secondary mt-3">2. Dampak dan Risiko</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Dampak</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="dampak" id="dampak"
                                    value="1">
                                <label class="form-check-label" for="dampak1">Kurang Berdampak</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="dampak" id="dampak"
                                    value="3">
                                <label class="form-check-label" for="dampak2">Berdampak</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="dampak" id="dampak"
                                    value="5">
                                <label class="form-check-label" for="dampak3">Sangat Berdampak</label>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Potensi Bahaya</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="potensi_bahaya"
                                    id="potensi_bahaya" value="1">
                                <label class="form-check-label" for="bahaya1">Tidak Berbahaya</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="potensi_bahaya"
                                    id="potensi_bahaya" value="3">
                                <label class="form-check-label" for="bahaya2">Cukup Berbahaya</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="potensi_bahaya"
                                    id="potensi_bahaya" value="5">
                                <label class="form-check-label" for="bahaya3">Berbahaya</label>
                            </div>
                        </div>
                    </div>

                    <!-- Estimasi Biaya -->
                    <h6 class="text-secondary mt-3">3. Estimasi Biaya</h6>
                    <div class="form-group mb-3">
                        <label><i class="fas fa-money-bill-wave"></i> Estimasi Biaya</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="estimasi_biaya" id="estimasi_biaya"
                                value="1">
                            <label class="form-check-label" for="biaya1">&lt; 250k</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="estimasi_biaya" id="estimasi_biaya"
                                value="2">
                            <label class="form-check-label" for="biaya2">250k ≤ biaya &lt; 500k</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="estimasi_biaya" id="estimasi_biaya"
                                value="3">
                            <label class="form-check-label" for="biaya3">500k ≤ biaya &lt; 750k</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="estimasi_biaya" id="estimasi_biaya"
                                value="4">
                            <label class="form-check-label" for="biaya4">750k ≤ biaya &lt; 1500k</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="estimasi_biaya" id="estimasi_biaya"
                                value="5">
                            <label class="form-check-label" for="biaya5">≥ 1500k</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-success" id="btn-save">Simpan</button>
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
</script>
