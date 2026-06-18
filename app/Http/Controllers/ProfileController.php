<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\AnggotaRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    protected $userRepo;
    protected $anggotaRepo;

    public function __construct(
        UserRepositoryInterface $userRepo,
        AnggotaRepositoryInterface $anggotaRepo
    ) {
        $this->userRepo = $userRepo;
        $this->anggotaRepo = $anggotaRepo;
    }

    public function show()
    {
        $user = Auth::user();
        $anggota = $user->anggota;

        return view('profile.show', compact('user', 'anggota'));
    }

    public function update(ProfileUpdateRequest $request)
    {
        $user = Auth::user();
        $anggotaId = $user->id_anggota;

        if (!$anggotaId) {
            return redirect()->back()->with('error', 'User tidak memiliki profil Anggota.');
        }

        $data = $request->only('nama_anggota', 'email_astra', 'no_hp');

        if ($request->hasFile('foto_anggota')) {
            // Delete old photo if exists
            $anggota = $this->anggotaRepo->findById($anggotaId);
            if ($anggota && $anggota->foto_anggota) {
                // Extract file path from URL
                $oldPath = str_replace('/storage/', 'public/', $anggota->foto_anggota);
                if (Storage::exists($oldPath)) {
                    Storage::delete($oldPath);
                }
            }

            $file = $request->file('foto_anggota');
            $filename = 'foto_' . $anggotaId . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/foto_anggota', $filename);
            $data['foto_anggota'] = Storage::url($path);
        }

        $this->anggotaRepo->updateProfile($anggotaId, $data);

        return redirect()->route('profile.show')
            ->with('success', 'Profil Anda berhasil diperbarui!');
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $userId = Auth::id();
        $this->userRepo->updatePassword($userId, $request->new_password);

        return redirect()->route('profile.show')
            ->with('success', 'Password Anda berhasil diubah!');
    }
}
