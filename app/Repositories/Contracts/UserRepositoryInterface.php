<?php

namespace App\Repositories\Contracts;

interface UserRepositoryInterface
{
    public function findById($id);
    public function findByUsername($username);
    public function updatePassword($userId, $newPassword);
}
