<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id_anggota = auth()->user()->id_anggota;

        return [
            'nama_anggota' => 'required|string|max:255',
            'email_astra' => 'required|email|max:255|unique:anggota,email_astra,' . $id_anggota . ',id_anggota',
            'no_hp' => 'required|string|max:20',
            'foto_anggota' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_anggota.required' => 'Nama lengkap wajib diisi.',
            'email_astra.required' => 'Email Astra wajib diisi.',
            'email_astra.email' => 'Format email tidak valid.',
            'email_astra.unique' => 'Email ini sudah terdaftar di sistem.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'foto_anggota.image' => 'Foto profil harus berupa gambar.',
            'foto_anggota.mimes' => 'Format gambar harus JPEG, PNG, atau JPG.',
            'foto_anggota.max' => 'Ukuran gambar maksimal adalah 2 MB.',
        ];
    }
}
