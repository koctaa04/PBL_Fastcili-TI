<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanKerusakan;
use App\Models\KriteriaPenilaian;
use Illuminate\Support\Facades\DB;

class VerifikasiLaporanController extends Controller
{
    public function     index()
    {
        $laporan = LaporanKerusakan::with(['user:id_user,nama', 'status:id_status,nama_status'])
            ->where('id_status', 1)
            ->get();

        return view('verifikasi_laporan.index', ['laporan' => $laporan]);
    }

    public function verif(string $id)
    {
        $laporan = LaporanKerusakan::find($id);

        return view('verifikasi_laporan.verifikasi', ['laporan' => $laporan]);
    }

    public function verifikasi(Request $request, $id)
    {
        $findLaporan = LaporanKerusakan::find($id);
        $findLaporan->id_status = 2;
        $findLaporan->save();

        $request->validate([
            'tingkat_kerusakan' => 'required|numeric',
            'frekuensi_digunakan' => 'required|numeric',
            'dampak' => 'required|numeric',
            'estimasi_biaya' => 'required|numeric',
            'potensi_bahaya' => 'required|numeric',
        ]);

        $kriteria = KriteriaPenilaian::create([
            'id_laporan' => $id,
            'tingkat_kerusakan' => $request->tingkat_kerusakan,
            'frekuensi_digunakan' => $request->frekuensi_digunakan,
            'dampak' => $request->dampak,
            'estimasi_biaya' => $request->estimasi_biaya,
            'potensi_bahaya' => $request->potensi_bahaya
        ]);

        if ($kriteria) {
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diverifikasi'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal Diverifikasi'
            ]);
        }

        redirect('/');
    }

    public function tolakForm(string $id)
    {
        $laporan = LaporanKerusakan::find($id);

        return view('verifikasi_laporan.tolak', ['laporan' => $laporan]);
    }

    public function tolak(Request $request, $id)
    {
        $findLaporan = LaporanKerusakan::find($id);

        if ($findLaporan) {
            $tolak = $findLaporan->update([
                'id_status' => 5,
                'keterangan' => $request->keterangan
            ]);
            if ($tolak) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil ditolak'
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal Ditolak'
        ]);

        redirect('/');
    }
}
