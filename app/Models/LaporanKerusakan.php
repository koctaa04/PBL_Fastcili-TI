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
        'id_user', 'id_fasilitas', 'id_status', 'deskripsi', 'foto', 'tanggal_laporan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function fasilitas()
    {
        return $this->belongsTo(Fasilitas::class, 'id_fasilitas');
    }

    public function status()
    {
        return $this->belongsTo(StatusLaporan::class, 'id_status');
    }

    public function kriteria()
    {
        return $this->hasMany(KriteriaPenilaian::class, 'id_laporan');
    }

    public function penugasan()
    {
        return $this->hasOne(PenugasanTeknisi::class, 'id_laporan');
    }
}
