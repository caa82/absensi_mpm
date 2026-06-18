<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgendaRapat extends Model
{
    use HasFactory;

    protected $table = 'agenda_rapat';
    protected $primaryKey = 'id_agenda';

    protected $fillable = [
        'judul_agenda',
        'deskripsi',
        'tanggal_rapat',
        'waktu_mulai',
        'waktu_selesai',
        'lokasi',
        'dibuat_oleh',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh', 'id_user');
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'id_agenda', 'id_agenda');
    }
}
