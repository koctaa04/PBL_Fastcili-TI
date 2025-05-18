<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fasilitas;
use App\Models\Gedung;
use App\Models\Ruangan;
use Illuminate\Support\Facades\Validator;

class FasilitasController extends Controller
{
    public function index(Request $request)
    {
        $query = Fasilitas::with('ruangan.gedung');

        if ($request->has('id_ruangan') && $request->id_ruangan != '') {
            $query->where('id_ruangan', $request->id_ruangan);
        }

        $fasilitas = $query->get();
        $gedung = Gedung::all();
        $ruangan = Ruangan::all();

        return view('fasilitas.index', [
            'fasilitas' => $fasilitas,
            'gedung' => $gedung,
            'ruangan' => $ruangan
        ]);
    }

    public function getRuangan($id)
    {
        $ruangan = Ruangan::where('id_gedung', $id)->get();
        return response()->json($ruangan);
    }

    public function create()
    {
        $ruangan = Ruangan::all();
        $gedung = Gedung::all();

        return view('fasilitas.create', ['ruangan' => $ruangan, 'gedung' => $gedung]);
    }

    public function store(Request $request)
    {
        $rules = [
            'id_ruangan' => 'required|exists:ruangan,id_ruangan',
            'nama_fasilitas' => 'required|string|max:50',
            'jumlah' => 'required|integer'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal Validasi',
                'msgField' => $validator->errors()
            ]);
        }

        Fasilitas::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambahkan data!'
        ]);

        redirect('/');
    }

    public function edit(string $id)
    {
        $fasilitas = Fasilitas::find($id);
        $ruangan = Ruangan::all();

        return view('fasilitas.edit', ['fasilitas' => $fasilitas, 'ruangan' => $ruangan]);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'nama_fasilitas' => 'required|string|max:50',
            'jumlah' => 'required|integer'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal Validasi',
                'msgField' => $validator->errors()
            ]);
        }

        $data = Fasilitas::find($id);

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
        $data = Fasilitas::find($id);

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
