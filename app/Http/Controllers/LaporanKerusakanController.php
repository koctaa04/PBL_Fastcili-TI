<?php

namespace App\Http\Controllers;

use App\Models\Gedung;
use App\Models\Ruangan;
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

    public function destroy(Request $request, $id)
    {
        $laporan = LaporanKerusakan::find($id);

        if ($laporan) {
            $laporan->delete($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Berhasil Dihapus'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    public function createPelapor()
    {
        $fasilitas = Fasilitas::all();
        $statusList = StatusLaporan::all();
        $gedungList = Gedung::all();

        return view('users.pelapor.create', compact('fasilitas', 'statusList', 'gedungList'));
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

        return view('users.pelapor.edit', ['laporan' => $laporan]);
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
        $laporan = LaporanKerusakan::find($id);

        return view('users.pelapor.rating', ['laporan' => $laporan]);
    }

    public function rating(Request $request, $id)
    {
        $request->validate([
            'rating_pengguna' => 'required|numeric|min:1|max:5',
            'feedback_pengguna' => 'required|string|max:500',
        ]);

        $laporan = LaporanKerusakan::find($id);
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
        $laporan = LaporanKerusakan::find($id);

        return view('users.pelapor.detail', ['laporan' => $laporan]);
    }
}
