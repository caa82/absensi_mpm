<?php

namespace App\Repositories\Contracts;

interface AnggotaRepositoryInterface
{
    public function all();
    public function findById($id);
    public function findByNim($nim);
    public function updateProfile($anggotaId, array $data);
    public function getAllActiveCount();
}
