<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Detail Data Perbaikan</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="card-body">
                {{-- Informasi Laporan --}}
                <h5 class="mb-1">Informasi Laporan</h5>
                <table class="table table-borderless">
                    <tr>
                        <td style="width: 30%;">
                            <img src="{{ asset('storage/uploads/laporan_kerusakan/' . $perbaikan->laporan->foto_kerusakan) }}"
                                alt="Foto Kerusakan" class="img-fluid rounded" style="max-width: 100%;"
                                onerror="this.onerror=null;this.src='{{ asset('images/fasilitas-rusak.jpeg') }}';">
                        </td>
                        <td>
                            <p><strong>Fasilitas:</strong> {{ $perbaikan->laporan->fasilitas->nama_fasilitas ?? '-' }}
                            </p>
                            <p><strong>Deskripsi Kerusakan:</strong> {{ $perbaikan->laporan->deskripsi }}</p>
                            <p><strong>Tanggal Lapor:</strong> {{ $perbaikan->laporan->tanggal_lapor }}</p>
                            <p><strong>Teknisi yang ditugaskan:</strong> {{ $perbaikan->laporan->penugasan->user->nama }}</p>
                        </td>
                    </tr>
                </table>

                <hr>
                {{-- Informasi Perbaikan --}}
                @if ($perbaikan->status_perbaikan == 'Selesai Dikerjakan')
                    <h5 class="mb-1">Informasi Perbaikan</h5>
                    <table class="table table-borderless">
                        <tr>
                            <td style="width: 30%;">
                                <img src="{{ asset('storage/uploads/dokumentasi/' . $perbaikan->dokumentasi) }}"
                                    alt="Dokumentasi Perbaikan" class="img-fluid rounded" style="max-width: 100%;">
                            </td>
                            <td>
                                <p><strong>Status:</strong>
                                    <span class="badge badge-pill badge-success">
                                        {{ $perbaikan->status_perbaikan }}
                                    </span>
                                </p>
                                <p><strong>Catatan Teknisi:</strong> {{ $perbaikan->catatan_teknisi ?? '-' }}</p>
                                <p><strong>Tanggal Selesai:</strong> {{ $perbaikan->tanggal_selesai ?? '-' }}</p>
                            </td>
                        </tr>
                    </table>
                @endif

            </div>
        </div>
    </div>
</div>
