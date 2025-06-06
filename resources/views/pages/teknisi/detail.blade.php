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
                    <th class="text-right col-3">Gedung :</th>
                    <td class="col-9">{{ $laporan->laporan->fasilitas->ruangan->gedung->nama_gedung }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Fasilitas :</th>
                    <td class="col-9">{{ $laporan->laporan->fasilitas->ruangan->nama_ruangan }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Deskripsi :</th>
                    <td class="col-9">
                        @if ($laporan->laporan && $laporan->laporan->laporanPelapor && $laporan->laporan->laporanPelapor->count())
                            <ul class="mb-0">
                                <li>{{ $laporan->laporan->deskripsi }}</li>
                                @foreach ($laporan->laporan->laporanPelapor as $pelapor)
                                    @if ($pelapor->id_laporan == $laporan->laporan->id)
                                        <li>{{ $pelapor->deskripsi_tambahan }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        @else
                            {{ $laporan->laporan->deskripsi }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="text-right col-3">Tanggal Lapor :</th>
                    <td class="col-9">
                        {{ $laporan->created_at->locale('id')->translatedFormat('l, d F Y') }}
                    </td>
                </tr>
                <tr>
                    <th class="text-right col-3">Tanggal Selesai :</th>
                    <td class="col-9">
                        {{ $laporan->tanggal_selesai->locale('id')->translatedFormat('l, d F Y') ?? '-' }}
                    </td>
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
                    <td class="col-9">{{ $laporan->user->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Catatan Teknisi :</th>
                    <td class="col-9">{{ $laporan->catatan_teknisi ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Catatan Sarpras :</th>
                    <td class="col-9">{{ $laporan->komentar_sarpras ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Rating :</th>
                    <td class="col-9">
                        @php
                            $ratings = $laporan->laporan->laporanPelapor;
                            $avgRating = $ratings ? $ratings->avg('rating_pengguna') : null;
                        @endphp

                        @if ($avgRating)
                            @for ($i = 0; $i < floor($avgRating); $i++)
                                <span style="color: gold;">&#9733;</span>
                            @endfor
                            @if ($avgRating - floor($avgRating) >= 0.5)
                                <span style="color: gold;">&#9734;</span>
                            @endif
                            <small>({{ number_format($avgRating, 1) }})</small>
                        @else
                            -
                        @endif
                        <pre>{{ $avgRating }}</pre>
                    </td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-primary">Tutup</button>
        </div>
    </div>
</div>
