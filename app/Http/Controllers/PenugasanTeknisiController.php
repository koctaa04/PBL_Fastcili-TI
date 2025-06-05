<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenugasanTeknisi;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class PenugasanTeknisiController extends Controller
{
    public function feedback(string $id)
    {
        $penugasan = PenugasanTeknisi::where('id_penugasan', $id)->first();

        return view('pages.teknisi.feedback', compact('penugasan'));
    }

    public function feedbackTeknisi(Request $request, $id)
    {
        $request->validate([
            'catatan_teknisi' => 'required|string',
            'dokumentasi' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $penugasan = PenugasanTeknisi::find($id);

        if ($penugasan) {
            $fotoFullPath = $request->file('dokumentasi')->store('uploads/dokumentasi', 'public');
            $filename = basename($fotoFullPath);

            $penugasan->catatan_teknisi = $request->catatan_teknisi;
            $penugasan->dokumentasi = $filename;
            $penugasan->tanggal_selesai = Carbon::now();
            $penugasan->status_perbaikan = 'Selesai Dikerjakan';
            $penugasan->save();

            return response()->json(['success' => true, 'message' => 'Berhasil menambahkan feedback!']);
        }

        return response()->json(['success' => false, 'message' => 'Data tidak ditemukan!']);
    }

    public function detailRiwayat(string $id)
    {
        $laporan = PenugasanTeknisi::find($id);

        return view('pages.teknisi.detail', compact('laporan'));
    }
}
