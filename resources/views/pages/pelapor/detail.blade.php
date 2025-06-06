<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Detail Data Laporan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body text-center">
            <img src="{{ asset('storage/uploads/laporan_kerusakan/' . $laporan->laporan->foto_kerusakan) }}"
                onerror="this.onerror=null;this.src='{{ asset('images/fasilitas-rusak.jpeg') }}';" alt="Foto Kerusakan"
                style="max-width: 300px; height: auto;" class="img-fluid rounded mb-3">

            <table class="table table-sm table-bordered table-striped text-left">
                <tr>
                    <th class="text-right col-3">Fasilitas :</th>
                    <td class="col-9">{{ $laporan->laporan->fasilitas->nama_fasilitas }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Deskripsi :</th>
                    <td class="col-9">{{ $laporan->deskripsi_tambahan }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Tanggal Lapor :</th>
                    <td class="col-9"> {{ $laporan->created_at->locale('id')->translatedFormat('l, d F Y') }}</td>


                </tr>
                <tr>
                    @php
                        $statusColor = match ($laporan->laporan->id_status) {
                            1 => 'bg-secondary',
                            2 => 'bg-primary',
                            3 => 'bg-info',
                            4 => 'bg-success text-white',
                            5 => 'bg-danger text-white',
                            default => 'bg-dark',
                        };
                    @endphp
                    <th class="text-right col-3">Status :</th>
                    <td class="col-9">
                        <span class="badge {{ $statusColor }}">{{ $laporan->laporan->status->nama_status }}</span>
                    </td>
                </tr>
                <tr>
                    <th class="text-right col-3">Teknisi :</th>
                    <td class="col-9">{{ $laporan->laporan->penugasan->user->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Keterangan :</th>
                    <td class="col-9">{{ $laporan->keterangan ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Rating :</th>
                    <td class="col-9">
                        @if ($laporan->rating_pengguna)
                            @for ($i = 0; $i < $laporan->rating_pengguna; $i++)
                                <span style="color: gold;">&#9733;</span>
                            @endfor
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="text-right col-3">Ulasan :</th>
                    <td class="col-9">{{ $laporan->feedback_pengguna ?? '-' }}</td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-primary">Tutup</button>
        </div>
    </div>
</div>
