<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Level;
use Illuminate\Support\Facades\Validator;

class LevelController extends Controller
{
    public function index()
    {
        $level = Level::all();
        return view('level.index', ['level' => $level]);
    }

    public function create()
    {
        return view('level.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'kode_level' => 'required|string|max:10|unique:level,kode_level',
            'nama_level' => 'required|string|max:25'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi inputan gagal',
                'msgField' => $validator->errors()
            ]);
        }

        Level::create($request->only(['kode_level', 'nama_level']));

        return response()->json([
            'success' => true,
            'message' => 'Data level berhasil ditambahkan'
        ]);

        redirect('/');
    }

    public function edit(string $id)
    {
        $level = Level::find($id);

        return view('level.edit', ['level' => $level]);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'kode_level' => 'required|string|max:10|unique:level,kode_level,' . $id . ',id_level',
            'nama_level' => 'required|string|max:25'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi inputan gagal',
                'msgField' => $validator->errors()
            ]);
        }

        $data = Level::find($id);

        if ($data) {
            $data->update($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Data level berhasil diubah'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ]);
        }

        redirect('/');
    }

    public function destroy(Request $request, $id)
    {
        $level = Level::find($id);

        if ($level) {
            $level->delete($request->all());
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
        redirect('/');
    }
}
