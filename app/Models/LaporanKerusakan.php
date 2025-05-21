<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanKerusakan extends Model
{
    use HasFactory;

    protected $table = 'laporan_kerusakan';
    protected $primaryKey = 'id_laporan';

    protected $fillable = [
        'id_fasilitas',
        'deskripsi',
        'foto_kerusakan',
        'tanggal_lapor',
        'id_status',
        'keterangan',
    ];

    public function fasilitas()
    {
        return $this->belongsTo(Fasilitas::class, 'id_fasilitas');
    }

    public function status()
    {
        return $this->belongsTo(StatusLaporan::class, 'id_status');
    }

    public function pelapor()
    {
        return $this->hasMany(PelaporLaporan::class, 'id_laporan');
    }

    //Kriteria
    public function kriteria()
    {
        return $this->hasMany(KriteriaPenilaian::class, 'id_laporan');
    }
}
