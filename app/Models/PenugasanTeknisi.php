<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenugasanTeknisi extends Model
{
    use HasFactory;
    protected $table = 'penugasan_teknisi';
    protected $primaryKey = 'id_penugasan';

    protected $fillable = ['id_laporan', 'id_user', 'status_perbaikan', 'tanggal_selesai'];

    public function laporan()
    {
        return $this->belongsTo(LaporanKerusakan::class, 'id_laporan');
    }

    public function teknisi()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

}
