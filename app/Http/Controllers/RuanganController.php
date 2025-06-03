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
        if ($request->ajax()) {
            return $this->getRuanganData($request);
        }

        $gedung = Gedung::all();
        $ruangan = Ruangan::all();
        return view('ruangan.index', compact('gedung', 'ruangan'));
    }

    private function getRuanganData(Request $request)
    {
        $ruangan = Ruangan::with('gedung');

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $ruangan->where(function ($query) use ($search) {
                $query->where('nama_ruangan', 'like', '%' . $search . '%')
                    ->orWhere('kode_ruangan', 'like', '%' . $search . '%');
            });
        }

        // Filter by gedung
        if ($request->has('id_gedung') && !empty($request->id_gedung)) {
            $ruangan->where('id_gedung', $request->id_gedung);
        }

        // Order by newest first
        $ruangan->orderBy('created_at', 'desc');

        // Pagination - 12 items per page (4x3)
        return $ruangan->paginate(12);
    }

    public function create()
    {
        $gedung = Gedung::all();

        return view('ruangan.create', ['gedung' => $gedung]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $rules = [
            'id_gedung' => 'required|exists:gedung,id_gedung',
            'kode_ruangan' => 'required|string|max:20|unique:ruangan,kode_ruangan',
            'nama_ruangan' => 'required|string|max:50'
        ];

        $customMessages = [
            'kode_ruangan.unique' => 'Kode ruangan sudah digunakan. Silakan gunakan kode yang lain.'
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi inputan gagal. Mohon cek kembali inputan Anda!',
                'msgField' => $validator->errors()
            ], 422);
        }

        // Ruangan::create([$request->all(), 'created_at' => now()]);
        Ruangan::create([
            'id_gedung' => $request->id_gedung,
            'kode_ruangan' => $request->kode_ruangan,
            'nama_ruangan' => $request->nama_ruangan,
            'created_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambahkan data ruangan!'
        ]);
    }

    public function edit(string $id)
    {
        $ruangan = Ruangan::find($id);
        $gedung = Gedung::all();

        return view('ruangan.edit', ['ruangan' => $ruangan, 'gedung' => $gedung]);
    }

    public function update(Request $request, $id)
    {
        $ruangan = Ruangan::findOrFail($id);

        $rules = [
            'id_gedung' => 'required|exists:gedung,id_gedung',
            'kode_ruangan' => 'required|string|max:20|unique:ruangan,kode_ruangan,' . $ruangan->id_ruangan . ',id_ruangan',
            'nama_ruangan' => 'required|string|max:50'
        ];

        $customMessages = [
            'kode_ruangan.unique' => 'Kode ruangan sudah digunakan. Silakan gunakan kode yang lain.'
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $ruangan->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Data ruangan berhasil diperbarui!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data',
                'error' => $e->getMessage()
            ], 500);
        }
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
