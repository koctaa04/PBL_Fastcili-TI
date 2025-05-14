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
            'email' => 'required|string|max:50|unique:users,email',
        ];

        $validator = Validator::make($request->all(), $rules, [
            'email.unique' => 'Email ini sudah digunakan oleh pengguna lain'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'msgField' => $validator->errors()
            ], 422);
        }

        try {
            User::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambahkan data!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data',
                'error' => $e->getMessage()
            ], 500);
        }
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
            'email' => 'required|string|max:50|unique:users,email,' . $id . ',id_user',
        ];

        $validator = Validator::make($request->all(), $rules, [
            'email.unique' => 'Email ini sudah digunakan oleh pengguna lain'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'msgField' => $validator->errors()
            ], 422);
        }

        $data = User::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan!'
            ], 404);
        }

        try {
            $data->update($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah data!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        $data = User::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan!'
            ], 404);
        }

        try {
            $data->delete();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus data!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function toggleAccess($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->akses = !$user->akses;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Status akses berhasil diubah',
                'new_status' => $user->akses
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
