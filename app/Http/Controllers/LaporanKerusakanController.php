<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use Illuminate\Http\Request;
use App\Models\StatusLaporan;
use App\Models\LaporanKerusakan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LaporanKerusakanController extends Controller
{
    public function index()
    {
        // $laporan = LaporanKerusakan::with(['user', 'fasilitas', 'status'])->get();
        // return view('laporan.index', ['laporan_kerusakan' => $laporan]);

        $laporan = LaporanKerusakan::with(['user', 'fasilitas', 'status'])
            ->where('id_user', auth()->user()->id_user)
            ->get();

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
