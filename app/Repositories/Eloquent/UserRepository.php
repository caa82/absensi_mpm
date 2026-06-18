<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function findById($id)
    {
        return User::with('anggota')->find($id);
    }

    public function findByUsername($username)
    {
        return User::with('anggota')->where('username', $username)->first();
    }

    public function updatePassword($userId, $newPassword)
    {
        $user = User::find($userId);
        if ($user) {
            $user->password = $newPassword; // Cast hashes it automatically in User model or Hash here
            return $user->save();
        }
        return false;
    }
}
