<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::when($request->id_level, function ($query, $id_level) {
                return $query->where('id_level', $id_level);
            })
                ->with('level')
                ->get();

            return datatables()->of($users)
                ->addColumn('profil', function ($user) {
                    return $user->foto_profil
                        ? '<img src="' . asset('uploads/foto_profil/' . $user->foto_profil) . '" width="50" style="border-radius:10px;">'
                        : '<img src="' . asset('default-avatar.jpg') . '" width="50" style="border-radius:10px;">';
                })
                ->addColumn('akses', function ($user) {
                    return $user->akses
                        ? '<i class="fas fa-user-check text-success"></i>'
                        : '<i class="fas fa-user-times text-danger"></i>';
                })
                ->addColumn('aksi', function ($user) {
                    return '
                    <div class="d-flex">
                        <button onclick="modalAction(\'' . url('/users/edit/' . $user->id_user) . '\')" class="btn btn-sm btn-info mr-2">Edit</button>
                        <form class="form-delete" action="' . url('/users/delete/' . $user->id_user) . '" method="POST">
                            <input type="hidden" name="_token" value="' . csrf_token() . '">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                        <button onclick="toggleAccess(\'' . url('/users/toggle-access/' . $user->id_user) . '\')" class="btn btn-sm ' . ($user->akses ? 'btn-secondary' : 'btn-success') . ' ml-2">
                            ' . ($user->akses ? 'Nonaktifkan' : 'Aktifkan') . '
                        </button>
                    </div>
                ';
                })
                ->rawColumns(['profil', 'akses', 'aksi'])
                ->toJson();
        }

        $level = Level::all();
        return view('users.index', ['level' => $level]);
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

    public function import()
    {
        return view('users.import');
    }

    public function import_ajax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file_user' => ['required', 'mimes:xlsx', 'max:1024']
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi data import gagal',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $file = $request->file('file_user');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray();

            $insert = [];
            $errors = [];

            foreach ($data as $index => $row) {
                if ($index === 0) continue; // Skip header

                // Validasi data
                if (!Level::find($row[0])) {
                    $errors[] = "Level ID {$row[0]} tidak ditemukan";
                    continue;
                }

                if (User::where('email', $row[1])->exists()) {
                    $errors[] = "Email {$row[1]} sudah terdaftar";
                    continue;
                }

                $insert[] = [
                    'id_level' => $row[0],
                    'email' => $row[1],
                    'nama' => $row[2],
                    'password' => Hash::make($row[3]),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            if (!empty($errors)) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Terdapat error validasi data',
                        'msgField' => $this->convertErrorsToFields($errors), // tambahan agar bisa ditampilkan di bawah input
                        'errors' => $errors // semua pesan error ditampilkan
                    ], 422);
                }

                return redirect()->back()->with('error', implode('<br>', $errors));
            }


            if (!empty($insert)) {
                User::insert($insert);
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Data user berhasil diimport',
                    'count' => count($insert)
                ]);
            }
            return redirect()->route('users.index')->with('success', 'Data user berhasil diimport (' . count($insert) . ' data)');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                    'error' => $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    protected function convertErrorsToFields(array $errors)
    {
        $result = [];

        foreach ($errors as $error) {
            // Coba deteksi field dari string error
            if (str_contains($error, 'email')) {
                $result['email'] = [$error];
            } elseif (str_contains($error, 'level') || str_contains($error, 'level_id')) {
                $result['level_id'] = [$error];
            } elseif (str_contains($error, 'nama')) {
                $result['name'] = [$error];
            } else {
                $result['file_user'] = [$error]; // fallback ke file_user
            }
        }

        return $result;
    }
}
