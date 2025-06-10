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
        if (auth()->user()->id_user === 1) {
            $laporan = PenugasanTeknisi::all();
        } else {
            $laporan = PenugasanTeknisi::with(['user', 'laporan.fasilitas'])
                ->where('id_user', auth()->user()->id_user)
                ->get();
        }
        // dd(auth()->user()->id_user);
        // dd($laporan);
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
        $penugasan = PenugasanTeknisi::where('id_penugasan', $id)->first();

        if (!$penugasan) {
            return response()->json([
                'success' => false,
                'messages' => 'Penugasan tidak ditemukan.',
            ]);
        }

        if (now()->greaterThan($penugasan->tenggat)) {
            return response()->json([
                'success' => false,
                'messages' => 'Tenggat waktu telah lewat. Anda tidak dapat mengunggah dokumentasi.',
            ]);
        }

        $perbaikan = PenugasanTeknisi::findOrFail($id);

        $rules = [
            'catatan_teknisi' => 'nullable|string|max:500',
            'dokumentasi' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // max 2MB
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'messages' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        try {
            $perbaikan->status_perbaikan = 'Selesai Dikerjakan';
            $perbaikan->catatan_teknisi = $request->catatan_teknisi;
            $perbaikan->tanggal_selesai = now();

            if ($request->hasFile('dokumentasi')) {
                // Hapus file lama
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
                'messages' => 'Perbaikan berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'messages' => 'Terjadi kesalahan saat menyimpan data.'
            ], 500);
        }
    }

    public function detail($id)
    {
        $perbaikan = PenugasanTeknisi::with(['laporan.fasilitas', 'user'])
            ->findOrFail($id);

        return view('perbaikan.detail', compact('perbaikan'));
    }
}
