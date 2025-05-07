<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $user = User::all();
 
        return view('users.index', ['users' => $user]);
    }

    public function create()
    {
        $level = Level::all();

        return view('users.create', ['level' => $level]);
    }

    public function store(Request $request)
    {
        $rules = [
            'id_level' => 'required|exists:level,id_level',
            'nama' => 'required|string|max:20',
            'no_induk' => 'required|string|max:20',
            'email' => 'required|string|max:50',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal Validasi',
                'msgField' => $validator->errors()
            ]);
        }

        User::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambahkan data!'
        ]);

        redirect('/');
    }

    public function edit(string $id)
    {
        $user = User::find($id);
        $level = Level::all();

        return view('users.edit', ['users' => $user, 'level' => $level]);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'id_level' => 'required|exists:level,id_level',
            'nama' => 'required|string|max:20',
            'no_induk' => 'required|string|max:20',
            'email' => 'required|string|max:50',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal Validasi',
                'msgField' => $validator->errors()
            ]);
        }

        $data = User::find($id);

        if ($data) {
            $
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
        $data = User::find($id);

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
