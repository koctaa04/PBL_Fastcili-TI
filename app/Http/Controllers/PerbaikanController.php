<?php

namespace App\Http\Controllers;

use App\Models\PelaporLaporan;
use Illuminate\Http\Request;
use App\Models\PenugasanTeknisi;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PerbaikanController extends Controller
{
    public function index()
    {
        if (auth()->user()->id_level == 1 || auth()->user()->id_level == 2) {
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
        if (auth()->user()->id_level == 3) {
            $perbaikan = PenugasanTeknisi::with('laporan.fasilitas')->findOrFail($id);
            
            return view('perbaikan.edit', ['laporan_kerusakan' => $perbaikan]);
        } else {
            return back();
        }
    }

    public function update(Request $request, $id)
    {
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
        if (auth()->user()->id_level == 1 || auth()->user()->id_level == 2 || auth()->user()->id_level == 3) {
            $perbaikan = PenugasanTeknisi::with(['laporan.fasilitas', 'user', 'laporan.pelaporLaporan'])
                ->findOrFail($id);
    
            $laporan = $perbaikan->laporan;
    
            // Ambil semua PelaporLaporan terkait laporan ini
            $pelaporLaporan = $laporan->pelaporLaporan;
    
            // Hitung jumlah pendukung (total PelaporLaporan)
            $jumlahPendukung = $pelaporLaporan->count();
    
            // Ambil rating yang tidak null
            $ratings = $pelaporLaporan->whereNotNull('rating_pengguna')->pluck('rating_pengguna');
    
            // Hitung jumlah yang sudah memberikan rating
            $jumlahRatingDiberikan = $ratings->count();
    
            // Hitung total nilai rating
            $totalRating = $ratings->sum();
    
            // Hitung rating akhir skala 0-5
            $totalPossibleScore = 5 * $jumlahPendukung;
            $ratingAkhir = $totalPossibleScore > 0 ? ($totalRating / $totalPossibleScore) * 5 : 0;
    
            // Ambil maksimal 5 feedback_pengguna pertama yang tidak null
            $ulasan = $pelaporLaporan
                ->whereNotNull('feedback_pengguna')
                ->pluck('feedback_pengguna')
                ->take(10);
    
            // Kirim ke view
            return view('perbaikan.detail', compact(
                'perbaikan',
                'jumlahPendukung',
                'jumlahRatingDiberikan',
                'ratingAkhir',
                'ulasan'
            ));
        } else {
            return back();
        }
    }
}
