<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenugasanTeknisi;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PerbaikanController extends Controller
{
    public function index()
    {
        $laporan = PenugasanTeknisi::with(['user', 'laporan.fasilitas'])
        ->where('id_user', auth()->user()->id_user)
        ->get();
        return view('perbaikan.index', ['laporan_kerusakan' => $laporan]);
    }

    public function edit($id)
    {
        $perbaikan = PenugasanTeknisi::with('laporan.fasilitas')->findOrFail($id);
        // dd($perbaikan);
        return view('perbaikan.edit', ['laporan_kerusakan' => $perbaikan]);
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $perbaikan = PenugasanTeknisi::findOrFail($id);

        $rules = [
            'catatan_teknisi' => 'nullable|string|max:500',
            'dokumentasi' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'msgField' => $validator->errors()
            ]);
        }

        $perbaikan->status_perbaikan = 'Selesai Dikerjakan';
        $perbaikan->catatan_teknisi = $request->catatan_teknisi;
        $perbaikan->tanggal_selesai = now();

        // Jika ada dokumentasi baru diunggah
        if ($request->hasFile('dokumentasi')) {
            if ($perbaikan->dokumentasi && Storage::exists('public/uploads/dokumentasi/' . $perbaikan->dokumentasi)) {
                Storage::delete('public/uploads/dokumentasi/' . $perbaikan->dokumentasi);
            }

            $file = $request->file('dokumentasi');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/uploads/dokumentasi', $filename);

            $perbaikan->dokumentasi = $filename;
        }

        $perbaikan->save();

        return response()->json([
            'success' => true,
            'messages' => 'Perbaikan berhasil diperbarui'
        ]);
    }

    public function detail($id)
{
    $perbaikan = PenugasanTeknisi::with(['laporan.fasilitas', 'user'])
                    ->findOrFail($id);

    return view('perbaikan.detail', compact('perbaikan'));
}
}
