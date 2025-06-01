<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fasilitas;
use App\Models\Gedung;
use App\Models\Ruangan;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class FasilitasController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $fasilitas = Fasilitas::with(['ruangan', 'ruangan.gedung'])
                ->select('fasilitas.id_fasilitas', 'fasilitas.id_ruangan', 'fasilitas.nama_fasilitas', 'fasilitas.jumlah')
                ->join('ruangan', 'ruangan.id_ruangan', '=', 'fasilitas.id_ruangan');

            // Add ruangan filter if provided
            if ($request->has('id_ruangan') && $request->id_ruangan != '') {
                $fasilitas->where('ruangan.id_ruangan', $request->id_ruangan);
            }
            // Add gedung filter if provided
            if ($request->has('id_gedung') && $request->id_gedung != '') {
                $fasilitas->where('ruangan.id_gedung', $request->id_gedung);
            }

            // For card view
            if ($request->has('per_page')) {
                return $fasilitas->paginate($request->per_page);
            }

            return DataTables::of($fasilitas)
                ->addIndexColumn()
                ->addColumn('aksi', function ($fasilitas) {
                    return '
                <div class="d-flex">
                    <button onclick="modalAction(\'' . url('/fasilitas/edit/' . $fasilitas->id_fasilitas) . '\')" class="btn btn-sm btn-info mr-2">Edit</button>
                    <form class="form-delete" action="' . url('/fasilitas/delete/' . $fasilitas->id_fasilitas) . '" method="POST">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </div>
            ';
                })
                ->rawColumns(['aksi'])
                ->toJson();
        }

        $gedung = Gedung::all();
        $ruangan = Ruangan::all();

        return view('fasilitas.index', compact('gedung', 'ruangan'));

    }

    public function list(Request $request)
    {
        $fasilitas = Fasilitas::with(['ruangan', 'ruangan.gedung'])
            ->select('fasilitas.id_fasilitas', 'fasilitas.id_ruangan', 'fasilitas.nama_fasilitas', 'fasilitas.jumlah')
            ->join('ruangan', 'ruangan.id_ruangan', '=', 'fasilitas.id_ruangan');

        // Add ruangan filter if provided
        if ($request->has('id_ruangan') && $request->id_ruangan != '') {
            $fasilitas->where('ruangan.id_ruangan', $request->id_ruangan);
        }

        // Add gedung filter if provided
        if ($request->has('id_gedung') && $request->id_gedung != '') {
            $fasilitas->where('ruangan.id_gedung', $request->id_gedung);
        }

        // Add search filter if provided
        if ($request->has('search') && $request->search != '') {
            $searchTerm = '%' . $request->search . '%';
            $fasilitas->where('fasilitas.nama_fasilitas', 'like', $searchTerm);
        }

        $perPage = $request->has('per_page') ? $request->per_page : 16;
        $page = $request->has('page') ? $request->page : 1;

        return response()->json($fasilitas->paginate($perPage, ['*'], 'page', $page));
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
        return view('fasilitas.create', compact('ruangan', 'gedung'));
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

        Fasilitas::create([$request->all(), 'created_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambahkan data!'
        ]);

        redirect('/');
    }

    public function edit(string $id)
    {
        $fasilitas = Fasilitas::find($id);
        return view('fasilitas.edit', compact('fasilitas'));
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
