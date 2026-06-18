<?php

namespace App\Repositories\Eloquent;

use App\Models\Anggota;
use App\Repositories\Contracts\AnggotaRepositoryInterface;

class AnggotaRepository implements AnggotaRepositoryInterface
{
    public function all()
    {
        return Anggota::orderBy('nama_anggota', 'asc')->get();
    }

    public function findById($id)
    {
        return Anggota::find($id);
    }

    public function findByNim($nim)
    {
        return Anggota::where('nim', $nim)->first();
    }

    public function updateProfile($anggotaId, array $data)
    {
        $anggota = Anggota::find($anggotaId);
        if ($anggota) {
            $anggota->update($data);
            return $anggota;
        }
        return null;
    }

    public function getAllActiveCount()
    {
        return Anggota::where('status', 'Aktif')->count();
    }
}
