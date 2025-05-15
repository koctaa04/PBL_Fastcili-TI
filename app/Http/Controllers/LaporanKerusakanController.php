<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Gedung;
use App\Models\Ruangan;
use App\Models\Fasilitas;
use Illuminate\Http\Request;
use App\Models\StatusLaporan;
use App\Models\LaporanKerusakan;
use App\Models\PenugasanTeknisi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LaporanKerusakanController extends Controller
{
    public function tugaskanTeknisi($id)
    {
        $laporan = LaporanKerusakan::with('fasilitas')->findOrFail($id);
        // $teknisi = User::where('id_level', '3')->get(); // Sesuaikan dengan role teknisi
        $teknisi = User::all(); // Sesuaikan dengan role teknisi

        return view('laporan_prioritas.tugaskan_teknisi', compact('laporan', 'teknisi'));
    }

    public function verifikasiPerbaikan($id)
    {
        $laporan = LaporanKerusakan::with('penugasan.user')->findOrFail($id);

        return view('laporan_prioritas.verifikasi', compact('laporan'));
    }
    // public function verifikasiPerbaikan($id)
    // {
    //     $laporan = LaporanKerusakan::with('penugasan.user')->findOrFail($id);
    //     return view('laporan_prioritas.modal-verifikasi', compact('laporan'));
    // }


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
                'status_perbaikan' => 'Selesai Dikerjakan',
                'komentar_sarpras' => null,
            ]);
        } else {
            $laporan->update([
                'id_status' => 5, // Ditolak
            ]);

            $penugasan->update([
                'status_perbaikan' => 'Ditolak',
                'komentar_sarpras' => $request->keterangan,
            ]);
        }

        return response()->json([
            'success' => true,
            'messages' => 'Verifikasi berhasil diproses.',
        ]);
    }






    public function index()
    {
        // $laporan = LaporanKerusakan::with(['user', 'fasilitas', 'status'])->get();
        // return view('laporan.index', ['laporan_kerusakan' => $laporan]);

        // Muncul laporan sesuai user yang login
        $laporan = LaporanKerusakan::with(['user', 'fasilitas', 'status'])
            ->where('id_user', auth()->user()->id_user)
            ->get();

        return view('laporan.index', ['laporan_kerusakan' => $laporan]);
    }


    public function getRuangan($id)
    {
        $ruangan = Ruangan::where('id_gedung', $id)->get();
        return response()->json($ruangan);
    }

    public function getFasilitas($id)
    {
        $fasilitas = Fasilitas::where('id_ruangan', $id)->get();
        return response()->json($fasilitas);
    }



    public function create()
    {
        $fasilitas = Fasilitas::all();
        $statusList = StatusLaporan::all();
        $gedungList = Gedung::all(); // ambil semua data gedung
        return view('laporan.create', compact('fasilitas', 'statusList', 'gedungList'));
    }
    public function store(Request $request)
    {
        $rules = [
            'id_fasilitas' => 'required|exists:fasilitas,id_fasilitas',
            'deskripsi' => 'required|string',
            'foto_kerusakan' => 'required|image|max:2048'
        ];

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


    public function edit(string $id)
    {
        $laporan = LaporanKerusakan::find($id);

        return view('laporan.edit', ['laporan' => $laporan]);
    }

    public function update(Request $request, $id)
    {
        $laporan = LaporanKerusakan::findOrFail($id);

        // Validasi data input
        $rules = [
            'deskripsi' => 'required|string|max:255',
            'foto_kerusakan' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'msgField' => $validator->errors()
            ]);
        }

        // Update deskripsi
        $laporan->deskripsi = $request->deskripsi;
        $laporan->id_status = 1;

        // Cek apakah ada file baru yang diunggah
        if ($request->hasFile('foto_kerusakan')) {
            // Hapus file lama jika ada
            if ($laporan->foto_kerusakan && Storage::exists('public/uploads/laporan_kerusakan/' . $laporan->foto_kerusakan)) {
                Storage::delete('public/uploads/laporan_kerusakan/' . $laporan->foto_kerusakan);
            }

            // Simpan file baru
            $file = $request->file('foto_kerusakan');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/uploads/laporan_kerusakan', $filename);

            $laporan->foto_kerusakan = $filename;
        }


        $laporan->save();

        return response()->json([
            'success' => true,
            'messages' => 'Data berhasil diperbarui'
        ]);
    }
}
