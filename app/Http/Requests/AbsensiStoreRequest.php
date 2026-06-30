<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AbsensiStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_agenda' => 'required|exists:agenda_rapat,id_agenda',
            'id_status' => 'required|exists:status_absensi,id_status',
            'alasan' => 'required_if:id_status,4|nullable|string',
            'bukti_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'bukti_foto' => 'required_if:id_status,1|required_if:id_status,5|nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'id_agenda.required' => 'Agenda wajib dipilih.',
            'id_status.required' => 'Status absensi wajib dipilih.',
            'alasan.required_if' => 'Alasan izin wajib diisi jika Anda memilih status Izin.',
            'bukti_file.mimes' => 'Bukti file harus berformat JPG, JPEG, PNG, atau PDF.',
            'bukti_file.max' => 'Ukuran bukti file maksimal adalah 2 MB.',
            'bukti_foto.required_if' => 'Bukti foto wajib diunggah untuk status kehadiran yang dipilih.',
            'bukti_foto.image' => 'Bukti foto harus berupa gambar.',
            'bukti_foto.mimes' => 'Bukti foto harus berformat JPG, JPEG, atau PNG.',
            'bukti_foto.max' => 'Ukuran bukti foto maksimal adalah 2 MB.',
        ];
    }
}
