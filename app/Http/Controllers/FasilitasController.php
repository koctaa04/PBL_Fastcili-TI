<?php

namespace App\Http\Controllers;

use App\Models\Gedung;
use App\Models\Ruangan;
use App\Models\Fasilitas;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class FasilitasController extends Controller
{
    public function index(Request $request)
    {
        $gedung = Gedung::all();
        $ruangan = Ruangan::all();

        return view('fasilitas.index', compact('gedung', 'ruangan'));
    }

    public function list(Request $request)
    {
        $fasilitas = Fasilitas::with(['ruangan', 'ruangan.gedung'])
            ->select('fasilitas.id_fasilitas', 'fasilitas.id_ruangan', 'fasilitas.nama_fasilitas', 'fasilitas.jumlah')
            ->join('ruangan', 'ruangan.id_ruangan', '=', 'fasilitas.id_ruangan')
            ->orderBy('fasilitas.created_at', 'desc') // Primary sort by created_at (newest first)
            ->orderBy('fasilitas.id_ruangan', 'asc'); // Secondary sort by id_ruangan
            ;
            

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
            'kode_fasilitas' => 'required|string|max:50',
            'jumlah' => 'required|integer|min:1'
        ];

        $validator = Validator::make($request->all(), $rules, [
            'jumlah' => 'Jumlah harus merupakan angka dan lebih dari 0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi inputan gagal. Mohon cek kembali inputan Anda!',
                'msgField' => $validator->errors()
            ], 422);
        }


        // Cek apakah fasilitas dengan nama dan ruangan yang sama sudah ada
        $fasilitas = Fasilitas::where('id_ruangan', $request->id_ruangan)
            ->where('nama_fasilitas', $request->nama_fasilitas)
            ->first();

        if ($fasilitas) {
            // Jika ada, update jumlah saja
            $fasilitas->jumlah += $request->jumlah;
            $fasilitas->save();
        } else {
            // Jika tidak ada, buat data baru
            Fasilitas::create([
                'id_ruangan' => $request->id_ruangan,
                'nama_fasilitas' => $request->nama_fasilitas,
                'jumlah' => $request->jumlah,
                'created_at' => now()
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambahkan data!'
        ]);
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

    public function import()
    {
        return view('fasilitas.import');
    }

    public function import_ajax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file_fasilitas' => ['required', 'mimes:xlsx', 'max:1024']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi file gagal',
                'errors' => $validator->errors(),
                'msgField' => ['file_fasilitas' => $validator->errors()->get('file_fasilitas')]
            ], 422);
        }

        try {
            $file = $request->file('file_fasilitas');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray();

            $errors = [];
            $berhasil = 0;

            foreach ($data as $index => $row) {
                if ($index === 0) continue; // Skip header
                $barisExcel = $index + 1;

                $id_ruangan = trim($row[0] ?? '');
                $nama_fasilitas = trim($row[1] ?? '');
                $jumlah = $row[2] ?? null;

                $rowErrors = [];

                if ($id_ruangan === '' || $nama_fasilitas === '' || $jumlah === null || $jumlah === '') {
                    $rowErrors[] = "Kolom tidak boleh kosong.";
                }

                if (!is_numeric($id_ruangan) || !Ruangan::find($id_ruangan)) {
                    $rowErrors[] = "ID Ruangan {$id_ruangan} tidak ditemukan.";
                }

                if (!is_numeric($jumlah) || $jumlah <= 0) {
                    $rowErrors[] = "Jumlah harus berupa angka lebih dari 0.";
                }

                if ($rowErrors) {
                    $errors[] = "Baris ke-{$barisExcel}: " . implode(' ', $rowErrors);
                    continue;
                }

                // Cek apakah fasilitas dengan nama dan ruangan sudah ada
                $fasilitas = Fasilitas::where('id_ruangan', $id_ruangan)
                    ->where('nama_fasilitas', $nama_fasilitas)
                    ->first();

                if ($fasilitas) {
                    // Update jumlah
                    $fasilitas->jumlah += (int)$jumlah;
                    $fasilitas->updated_at = now();
                    $fasilitas->save();
                } else {
                    // Buat baru
                    Fasilitas::create([
                        'id_ruangan' => $id_ruangan,
                        'nama_fasilitas' => $nama_fasilitas,
                        'jumlah' => (int)$jumlah,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }

                $berhasil++;
            }

            if ($errors) {
                return response()->json([
                    'status' => false,
                    'message' => 'Terdapat kesalahan pada data Excel.',
                    'errors' => $errors,
                    'msgField' => ['file_fasilitas' => $errors]
                ], 422);
            }

            return response()->json([
                'status' => true,
                'message' => 'Data Fasilitas berhasil diimport.',
                'count' => $berhasil
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat proses import: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }



    protected function convertErrorsToFields(array $errors)
    {
        $result = [];
        foreach ($errors as $error) {
            if (str_contains($error, 'id_ruangan')) {
                $result['id_ruangan'] = [$error];
            } elseif (str_contains($error, 'nama_fasilitas') || str_contains($error, 'level_id')) {
                $result['nama_fasilitas'] = [$error];
            } elseif (str_contains($error, 'jumlah')) {
                $result['jumlah'] = [$error];
            } else {
                $result['file_fasilitas'] = [$error];
            }
        }
        return $result;
    }
}
