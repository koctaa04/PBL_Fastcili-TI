<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use Illuminate\Http\Request;
use App\Models\StatusLaporan;
use App\Models\LaporanKerusakan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LaporanKerusakanController extends Controller
{
    public function index()
    {
        $laporan = LaporanKerusakan::with(['user', 'fasilitas', 'status'])->get();
        return view('laporan.index', ['laporan_kerusakan' => $laporan]);
    }

    public function create()
    {
        $fasilitas = Fasilitas::all();
        $statusList = StatusLaporan::all();
        return view('laporan.create', compact('fasilitas', 'statusList'));
    }
    public function store(Request $request)
    {
        $rules = [
            'id_fasilitas' => 'required|exists:fasilitas,id_fasilitas',
            'deskripsi' => 'required|string',
            'foto_kerusakan' => 'nullable|image|max:2048'
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
            $file->move(public_path('uploads'), $filename);
        }

        // Simpan data ke database
        LaporanKerusakan::create([
            'id_user' => $request->id_user,
            'id_fasilitas' => $request->id_fasilitas,
            'deskripsi' => $request->deskripsi,
            'foto_kerusakan' => $filename ?? 'default.jpg',
            'tanggal_lapor' => now(),
            'id_status' => 1 // Status default: "Menunggu"
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Laporan kerusakan berhasil ditambahkan'
        ]);
    }
}
