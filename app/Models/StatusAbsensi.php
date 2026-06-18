<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusAbsensi extends Model
{
    use HasFactory;

    protected $table = 'status_absensi';
    protected $primaryKey = 'id_status';

    protected $fillable = [
        'nama_status',
        'bobot_kehadiran',
    ];

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'id_status', 'id_status');
    }
}
