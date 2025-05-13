<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gedung;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class GedungController extends Controller
{
    public function index()
    {
        $gedung = Gedung::all();
        return view('gedung.index', ['gedung' => $gedung]);
    }

    public function create()
    {
        return view('gedung.create');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $rules = [
            'kode_gedung' => 'required|string|max:10|unique:gedung,kode_gedung',
            'nama_gedung' => 'required|string|max:50',
            'deskripsi' => 'required|string|max:255',
            'foto_gedung' => 'required|image|max:2048'
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
        if ($request->hasFile('foto_gedung')) {
            $file = $request->file('foto_gedung');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/uploads/gedung'), $filename);
        }

        // Gedung::create($request->only(['kode_gedung', 'nama_gedung', 'deskripsi', 'deskripsi']));

        // Simpan data ke database
        Gedung::create([
            'kode_gedung' => $request->kode_gedung,
            'nama_gedung' => $request->nama_gedung,
            'deskripsi' => $request->deskripsi,
            'foto_gedung' => $filename,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Data gedung berhasil ditambahkan'
        ]);
    }

    public function edit(string $id)
    {
        $gedung = Gedung::find($id);

        return view('gedung.edit', ['gedung' => $gedung]);
    }

    public function update(Request $request, $id)
    {
        $gedung = Gedung::findOrFail($id);

        $validated = $request->validate([
            'nama_gedung' => 'required|string|max:255',
            'kode_gedung' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto_gedung')) {
            // Hapus foto lama jika ada
            $oldPhotoPath = public_path('uploads/foto_gedung/' . $gedung->foto_gedung);
            if ($gedung->foto_gedung && Storage::exists($oldPhotoPath)) {
                Storage::delete($oldPhotoPath);
            }

            // Simpan foto baru
            $file = $request->file('foto_gedung');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/foto_gedung'), $filename);
            $gedung->foto_gedung = $filename;
        }

        $gedung->update($validated);
        
        return response()->json(['success' => true, 'message' => 'Gedung berhasil diperbarui']);
        redirect('/');
    }

    public function destroy(Request $request, $id)
    {
        $gedung = Gedung::find($id);

        if ($gedung) {
            $gedung->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ]);
        }
    }
}
