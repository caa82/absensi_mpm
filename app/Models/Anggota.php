<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;

    protected $table = 'anggota';
    protected $primaryKey = 'id_anggota';

    protected $fillable = [
        'nim',
        'nama_anggota',
        'jenis_kelamin',
        'email_astra',
        'no_hp',
        'jabatan',
        'angkatan',
        'foto_anggota',
        'status',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id_anggota', 'id_anggota');
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'id_anggota', 'id_anggota');
    }
}
