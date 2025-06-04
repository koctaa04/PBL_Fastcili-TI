<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Gedung;
use App\Models\Ruangan;
use App\Models\Fasilitas;
use Illuminate\Http\Request;
use App\Models\StatusLaporan;
use App\Models\PelaporLaporan;
use App\Models\LaporanKerusakan;
use App\Models\PenugasanTeknisi;
use App\Models\KriteriaPenilaian;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Database\Seeders\pelaporLaporanSeeder;
use Illuminate\Support\Facades\DB;

class LaporanKerusakanController extends Controller
{
    public function index()
    {
        // Ambil semua laporan + pelapor
        $laporan = PelaporLaporan::with([
            'laporan.fasilitas.ruangan.gedung',
            'laporan.status',
            'user'
        ])->get();
        $gedung = Gedung::all();
        return view('laporan.index', compact('gedung', 'laporan'));
    }

    public function getRuangan($idGedung)
    {
        return Ruangan::where('id_gedung', $idGedung)->get();
    }

    // Untuk Card sebelah kanan
    public function getFasilitasTerlapor($idRuangan)
    {
        return LaporanKerusakan::with('fasilitas')
            ->whereHas('fasilitas', fn($q) => $q->where('id_ruangan', $idRuangan))
            ->whereIn('id_status', [1, 2, 3])
            // ->where('id_user', '!=', Auth::user()->id)
            ->get()
            ->map(fn($lap) => [
                'id_laporan' => $lap->id_laporan,
                'nama_fasilitas' => $lap->fasilitas->nama_fasilitas,
                'deskripsi' => $lap->deskripsi,
                'foto_kerusakan' => $lap->foto_kerusakan,
                'tanggal_lapor' => $lap->tanggal_lapor
            ]);
    }

    // Untuk drop-down sebelah kiri
    public function getFasilitasBelumLapor($idRuangan)
    {
        $terlaporIds = LaporanKerusakan::whereIn('id_status', [1, 2, 3])
            ->pluck('id_fasilitas')
            ->toArray();

        return Fasilitas::where('id_ruangan', $idRuangan)
            ->whereNotIn('id_fasilitas', $terlaporIds)
            ->get();
    }


    public function destroy($id)
    {
        $pelapor = pelaporLaporan::find($id);

        if (!$pelapor) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        $laporan_id = $pelapor->id_laporan;

        // Hitung jumlah pelapor untuk laporan ini
        $jumlahPelapor = pelaporLaporan::where('id_laporan', $laporan_id)->count();

        // Hapus pelapor ini
        $pelapor->delete();

        // Jika pelapor tinggal 1, hapus juga laporan utama
        if ($jumlahPelapor == 1) {
            $laporanUtama = laporanKerusakan::find($laporan_id);
            if ($laporanUtama) {
                // Jika ada relasi lain yang perlu dihapus juga, tambahkan di sini
                $laporanUtama->delete();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }


    public function store(Request $request)
    {
        $userId = auth()->id();



        // Jika dukungan laporan sudah ada
        if ($request->filled('dukungan_laporan')) {
            $laporanId = $request->dukungan_laporan;
            // Jika Fasilitas yang dilaporkan sedang tahap perbaikan
            $sedangDiperbaiki = KriteriaPenilaian::where('id_laporan', $laporanId);
            if ($sedangDiperbaiki) {
                return response()->json([
                    'success' => false,
                    'message' => 'Fasilitas ini sedang dalam proses perbaikan atau sudah dilaporkan.'
                ]);
            }

            // Cek apakah user sudah mendukung laporan ini
            $sudahMendukung = PelaporLaporan::where('id_laporan', $laporanId)
                ->where('id_user', $userId)
                ->exists();

            if ($sudahMendukung) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah mendukung laporan ini sebelumnya.'
                ]);
            }

            // Validasi untuk dukungan laporan
            $request->validate([
                'tambahan_deskripsi' => 'nullable|string|max:255',
            ]);

            // Simpan dukungan
            PelaporLaporan::create([
                'id_laporan' => $laporanId,
                'id_user' => $userId,
                'deskripsi_tambahan' => $request->tambahan_deskripsi,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Dukungan terhadap laporan berhasil dikirim.'
            ]);
        } else {
            // Validasi laporan baru
            $request->validate([
                'id_fasilitas' => 'required|exists:fasilitas,id_fasilitas',
                'deskripsi' => 'required|string|max:255',
                'foto_kerusakan' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            // Cek apakah ada laporan aktif untuk fasilitas yang sama
            $existing = LaporanKerusakan::where('id_fasilitas', $request->id_fasilitas)
                ->whereIn('id_status', [1, 2, 3, 4]) // status aktif
                ->first();


            // Cek apakah user sudah pernah melaporkan laporan aktif ini
            $sudahMelaporkan = PelaporLaporan::where('id_laporan', $existing->id_laporan)
                ->where('id_user', $userId)
                ->whereHas('laporan', function ($query) use ($request) {
                    $query->where('id_status', '!=', 4);
                }) // hanya laporan aktif yang dicek
                ->exists();

            if ($sudahMelaporkan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah pernah melaporkan kerusakan ini sebelumnya.'
                ]);
            }

            // Upload foto
            $fotoFullPath = $request->file('foto_kerusakan')->store('uploads/laporan_kerusakan', 'public');
            $filename = basename($fotoFullPath);

            // Simpan laporan baru
            $laporan = LaporanKerusakan::create([
                'id_fasilitas' => $request->id_fasilitas,
                'deskripsi' => $request->deskripsi,
                'foto_kerusakan' => $filename,
                'tanggal_lapor' => now(),
                'id_status' => 1,
            ]);

            // Simpan pelapor
            PelaporLaporan::create([
                'id_laporan' => $laporan->id_laporan,
                'id_user' => $userId,
                'deskripsi_tambahan' => $request->deskripsi,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Laporan baru berhasil dikirim.'
            ]);
        }
    }

    public function trending(Request $request)
    {
        $bobot = [
            'MHS' => 1,
            'TDK' => 2,
            'DSN' => 3,
            'ADM' => 3
        ];

        $pelapor = PelaporLaporan::with(['user.level', 'laporan'])
            ->whereHas('laporan', function ($query) {
                $query->where('id_status', 1);
            })
            ->get();

        $skorPerLaporan = [];
        foreach ($pelapor as $item) {
            $idLaporan = $item->id_laporan;
            $kodeLevel = $item->user->level->kode_level ?? 'OTHER';
            $skor = $bobot[$kodeLevel] ?? 0;

            if (!isset($skorPerLaporan[$idLaporan])) {
                $skorPerLaporan[$idLaporan] = [
                    'skor' => 0,
                    'total_pelapor' => 0,
                    'created_at' => $item->laporan->created_at ?? now() // Get the report creation date
                ];
            }

            $skorPerLaporan[$idLaporan]['skor'] += $skor;
            $skorPerLaporan[$idLaporan]['total_pelapor']++;
        }

        $data = collect($skorPerLaporan)
            ->map(function ($item, $idLaporan) {
                $laporan = LaporanKerusakan::with(['fasilitas', 'pelaporLaporan'])->find($idLaporan);
                if (!$laporan) return null;

                return [
                    'laporan' => $laporan,
                    'skor' => $item['skor'],
                    'total_pelapor' => $item['total_pelapor'],
                    'created_at' => $item['created_at'] // Include created_at in the final data
                ];
            })
            ->filter()
            ->values();

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = strtolower($request->search);
            $data = $data->filter(function ($item) use ($searchTerm) {
                return str_contains(strtolower($item['laporan']->fasilitas->nama_fasilitas ?? ''), $searchTerm) ||
                    str_contains(strtolower($item['laporan']->deskripsi ?? ''), $searchTerm);
            })->values();
        }

        // Multi-level sorting
        $sortedData = $data->sortBy([
            // Primary sort: skor (descending)
            fn($a, $b) => $b['skor'] <=> $a['skor'],
            // Secondary sort: total_pelapor (descending) if skor is equal
            fn($a, $b) => $b['total_pelapor'] <=> $a['total_pelapor'],
            // Tertiary sort: created_at (ascending - older reports first) if both skor and total_pelapor are equal
            fn($a, $b) => $a['created_at'] <=> $b['created_at']
        ])->values();

        // Beri ranking dan ambil top 10
        $rankedData = $sortedData->take(10)->map(function ($item, $index) {
            $item['rank'] = $index + 1;
            return $item;
        });

        return view('laporan.trending', [
            'data' => $rankedData,
            'search' => $request->input('search', '')
        ]);
    }

    public function showPenilaian(string $id)
    {
        $data = PelaporLaporan::select('id_laporan', DB::raw('count(*) as total'))
            ->groupBy('id_laporan')
            ->orderByDesc('total')
            ->whereHas('laporan', function ($laporan) {
                $laporan->where('id_status', 1);
            })
            ->get();

        $trendingRanks = [];
        foreach ($data as $index => $item) {
            $trendingRanks[$item->id_laporan] = $index + 1;
        }

        $laporan = LaporanKerusakan::with([
            'fasilitas',
            'pelaporLaporan'
        ])->findOrFail($id);

        $trendingNo = $trendingRanks[$laporan->id_laporan] ?? '-';

        return view('laporan.showPenilaian', compact('laporan', 'trendingNo'));
    }

    public function simpanPenilaian(Request $request, $id)
    {
        $findLaporan = LaporanKerusakan::find($id);
        $findLaporan->id_status = 2;
        $findLaporan->save();

        $request->validate([
            'tingkat_kerusakan' => 'required|numeric',
            'frekuensi_digunakan' => 'required|numeric',
            'dampak' => 'required|numeric',
            'estimasi_biaya' => 'required|numeric',
            'potensi_bahaya' => 'required|numeric',
        ]);

        $kriteria = KriteriaPenilaian::create([
            'id_laporan' => $id,
            'tingkat_kerusakan' => $request->tingkat_kerusakan,
            'frekuensi_digunakan' => $request->frekuensi_digunakan,
            'dampak' => $request->dampak,
            'estimasi_biaya' => $request->estimasi_biaya,
            'potensi_bahaya' => $request->potensi_bahaya
        ]);

        if ($kriteria) {
            return response()->json([
                'success' => true,
                'messages' => 'Laporan berhasil diberi nilai'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'messages' => 'Gagal diberi nilai'
            ]);
        }

        redirect('/');
    }

    public function tugaskanTeknisi($id)
    {
        $laporan = LaporanKerusakan::with('fasilitas')->findOrFail($id);
        $teknisi = User::where('id_level', '3')->get();

        return view('laporan_prioritas.tugaskan_teknisi', compact('laporan', 'teknisi'));
    }

    public function verifikasiPerbaikan($id)
    {
        $laporan = LaporanKerusakan::with('penugasan.user')->findOrFail($id);

        return view('laporan_prioritas.verifikasi', compact('laporan'));
    }

    public function simpanPenugasan(Request $request)
    {
        $request->validate([
            'id_laporan' => 'required',
            'id_user' => 'required',
        ]);

        PenugasanTeknisi::updateOrCreate(
            ['id_laporan' => $request->id_laporan],
            [
                'id_user' => $request->id_user,
                'status_perbaikan' => 'Sedang dikerjakan',
                'tanggal_selesai' => null,
            ]
        );

        LaporanKerusakan::where('id_laporan', $request->id_laporan)
            ->update(['id_status' => 3]); // Asumsikan 3 = 'Dalam Perbaikan'

        return response()->json(['success' => true, 'messages' => 'Teknisi berhasil ditugaskan']);
    }

    public function simpanVerifikasi(Request $request)
    {
        $idLaporan = $request->id_laporan;
        $idPenugasan = $request->id_penugasan;

        $laporan = LaporanKerusakan::find($idLaporan);
        if (!$laporan) {
            return response()->json([
                'success' => false,
                'messages' => 'Laporan tidak ditemukan.',
            ]);
        }

        $penugasan = PenugasanTeknisi::find($idPenugasan);
        if (!$penugasan) {
            return response()->json([
                'success' => false,
                'messages' => 'Data penugasan tidak ditemukan.',
            ]);
        }

        if ($request->verifikasi === 'setuju') {
            $laporan->update([
                'id_status' => 4, // Selesai
            ]);

            $penugasan->update([
                'status_perbaikan' => 'Selesai',
                'komentar_sarpras' => null,
            ]);
        } else {
            $laporan->update([
                // id status berubah menjadi diperbaiki lagi ketika Ditolak
                'id_status' => 3,
            ]);

            $penugasan->update([
                'status_perbaikan' => 'Ditolak',
                'komentar_sarpras' => $request->keterangan,
            ]);
        }

        $kriteria = KriteriaPenilaian::find($idLaporan);
        $kriteria->delete();

        return response()->json([
            'success' => true,
            'messages' => 'Verifikasi berhasil diproses.',
        ]);
    }

    // ---------------------------------------------------








    // public function edit(string $id)
    // {
    //     $laporan = LaporanKerusakan::find($id);

    //     return view('laporan.edit', ['laporan' => $laporan]);
    // }

    // public function update(Request $request, $id)
    // {
    //     $laporan = LaporanKerusakan::findOrFail($id);

    //     // Validasi data input
    //     $rules = [
    //         'deskripsi' => 'required|string|max:255',
    //         'foto_kerusakan' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
    //     ];

    //     $validator = Validator::make($request->all(), $rules);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Validasi gagal',
    //             'msgField' => $validator->errors()
    //         ]);
    //     }

    //     // Update deskripsi
    //     $laporan->deskripsi = $request->deskripsi;
    //     $laporan->id_status = 1;

    //     // Cek apakah ada file baru yang diunggah
    //     if ($request->hasFile('foto_kerusakan')) {
    //         // Hapus file lama jika ada
    //         if ($laporan->foto_kerusakan && Storage::exists('public/uploads/laporan_kerusakan/' . $laporan->foto_kerusakan)) {
    //             Storage::delete('public/uploads/laporan_kerusakan/' . $laporan->foto_kerusakan);
    //         }

    //         // Simpan file baru
    //         $file = $request->file('foto_kerusakan');
    //         $filename = time() . '_' . $file->getClientOriginalName();
    //         $file->storeAs('public/uploads/laporan_kerusakan', $filename);

    //         $laporan->foto_kerusakan = $filename;
    //     }


    //     $laporan->save();

    //     return response()->json([
    //         'success' => true,
    //         'messages' => 'Data berhasil diperbarui'
    //     ]);
    // }



    public function createPelapor()
    {
        $fasilitas = Fasilitas::all();
        $statusList = StatusLaporan::all();
        $gedungList = Gedung::all();

        return view('pages.pelapor.create', compact('fasilitas', 'statusList', 'gedungList'));
    }

    public function storePelapor(Request $request)
    {
        $rules = [
            'id_fasilitas' => 'required|exists:fasilitas,id_fasilitas',
            'deskripsi' => 'required|string',
        ];

        if ($request->hasFile('foto_kerusakan')) {
            $rules['foto_kerusakan'] = 'required|image|max:2048';
        } else if ($request->input('image')) {
            $rules['image'] = 'required|string';
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Foto kerusakan harus diupload (file atau kamera).',
            ]);
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'msgField' => $validator->errors()
            ]);
        }

        // Upload foto jika ada
        $filename = null;
        if ($request->hasFile('foto_kerusakan')) {
            $file = $request->file('foto_kerusakan');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/uploads/laporan_kerusakan'), $filename);
        } else if ($request->input('image')) {
            $image = $request->input('image');
            list($type, $image_data) = explode(';', $image);
            list(, $image_data) = explode(',', $image_data);
            $imageData = base64_decode($image_data);

            $filename = time() . '_camera.jpg';
            $path = public_path('storage/uploads/laporan_kerusakan/' . $filename);
            file_put_contents($path, $imageData);
        }

        // Simpan data ke database
        LaporanKerusakan::create([
            'id_user' => auth()->user()->id_user,
            'id_fasilitas' => $request->id_fasilitas,
            'deskripsi' => $request->deskripsi,
            'foto_kerusakan' => $filename,
            'tanggal_lapor' => now(),
            'id_status' => 1 // Status default: "Menunggu"
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Laporan kerusakan berhasil ditambahkan'
        ]);
    }

    public function editPelapor(string $id)
    {
        $laporan = LaporanKerusakan::find($id);

        return view('pages.pelapor.edit', ['laporan' => $laporan]);
    }

    public function updatePelapor(Request $request, $id)
    {
        $laporan = LaporanKerusakan::findOrFail($id);

        $rules = [
            'deskripsi' => 'required|string|max:255',
        ];

        if ($request->hasFile('foto_kerusakan')) {
            $rules['foto_kerusakan'] = 'image|max:2048';
        } elseif ($request->input('image')) {
            $rules['image'] = 'string';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'msgField' => $validator->errors()
            ]);
        }

        $laporan->deskripsi = $request->deskripsi;

        if ($request->hasFile('foto_kerusakan')) {
            if ($laporan->foto_kerusakan && Storage::exists('public/uploads/laporan_kerusakan/' . $laporan->foto_kerusakan)) {
                Storage::delete('public/uploads/laporan_kerusakan/' . $laporan->foto_kerusakan);
            }

            $file = $request->file('foto_kerusakan');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/uploads/laporan_kerusakan', $filename);
            $laporan->foto_kerusakan = $filename;
        } elseif ($request->input('image')) {
            if ($laporan->foto_kerusakan && Storage::exists('public/uploads/laporan_kerusakan/' . $laporan->foto_kerusakan)) {
                Storage::delete('public/uploads/laporan_kerusakan/' . $laporan->foto_kerusakan);
            }

            $image = $request->input('image');
            list($type, $image_data) = explode(';', $image);
            list(, $image_data) = explode(',', $image_data);
            $imageData = base64_decode($image_data);

            $filename = time() . '_camera.jpg';
            Storage::put('public/uploads/laporan_kerusakan/' . $filename, $imageData);
            $laporan->foto_kerusakan = $filename;
        }

        $laporan->save();

        return response()->json([
            'success' => true,
            'messages' => 'Data berhasil diperbarui'
        ]);
    }

    public function rate(string $id)
    {
        $laporan =  PelaporLaporan::where('id_user', $id)->first();

        return view('pages.pelapor.rating', ['laporan' => $laporan]);
    }

    public function rating(Request $request, $id)
    {
        $request->validate([
            'rating_pengguna' => 'required|numeric|min:1|max:5',
            'feedback_pengguna' => 'required|string|max:500',
        ]);

        $laporan = PelaporLaporan::where('id_user', $id)->first();
        if (!$laporan) {
            return response()->json(['success' => false, 'message' => 'Laporan tidak ditemukan.']);
        }

        $laporan->rating_pengguna = $request->rating_pengguna;
        $laporan->feedback_pengguna = $request->feedback_pengguna;
        $laporan->save();

        return response()->json([
            'success' => true,
            'message' => 'Rating dan ulasan berhasil disimpan.',
        ]);
    }

    public function detail(string $id)
    {
        $laporan = PelaporLaporan::find($id);

        return view('pages.pelapor.detail', ['laporan' => $laporan]);
    }
}
