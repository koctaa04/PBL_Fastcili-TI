<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ruangan;
use App\Models\Gedung;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class RuanganController extends Controller
{
    public function index(Request $request)
    {
        $id_gedung = $request->query('id_gedung');

        if (!empty($id_gedung)) {
            $ruangan = Ruangan::where('id_gedung', $id_gedung)->get();
        } else {
            $ruangan = Ruangan::all(); // tampilkan semua
        }

        $gedung = Gedung::all();

        return view('ruangan.index', ['ruangan' => $ruangan, 'gedung' => $gedung]);
    }

    public function create()
    {
        $gedung = Gedung::all();

        return view('ruangan.create', ['gedung' => $gedung]);
    }

    public function store(Request $request)
    {
        $rules = [
            'id_gedung' => 'required|exists:gedung,id_gedung',
            'kode_ruangan' => 'required|string|max:20',
            'nama_ruangan' => 'required|string|max:50'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal Validasi',
                'msgField' => $validator->errors()
            ]);
        }

        Ruangan::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambahkan data!'
        ]);

        redirect('/');
    }

    public function edit(string $id)
    {
        $ruangan = Ruangan::find($id);
        $gedung = Gedung::all();

        return view('ruangan.edit', ['ruangan' => $ruangan, 'gedung' => $gedung]);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'id_gedung' => 'required|exists:gedung,id_gedung',
            'kode_ruangan' => 'required|string|max:20',
            'nama_ruangan' => 'required|string|max:50'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal Validasi',
                'msgField' => $validator->errors()
            ]);
        }

        $data = Ruangan::find($id);

        if ($data) {
            $data->update($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah data!'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan!'
            ]);
        }

        redirect('/');
    }

    public function destroy(Request $request, $id)
    {
        $data = Ruangan::find($id);

        if ($data) {
            $data->delete($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus data!'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan!'
            ]);
        }

        redirect('/');
    }
}
