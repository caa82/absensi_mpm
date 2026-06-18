<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';
    protected $primaryKey = 'id_absensi';

    protected $fillable = [
        'id_agenda',
        'id_anggota',
        'id_status',
        'waktu_absen',
        'keterangan',
    ];

    public function agenda()
    {
        return $this->belongsTo(AgendaRapat::class, 'id_agenda', 'id_agenda');
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'id_anggota', 'id_anggota');
    }

    public function status()
    {
        return $this->belongsTo(StatusAbsensi::class, 'id_status', 'id_status');
    }

    public function izin()
    {
        return $this->hasOne(Izin::class, 'id_absensi', 'id_absensi');
    }
}
