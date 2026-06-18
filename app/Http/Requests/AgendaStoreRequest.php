<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgendaStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'judul_agenda' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_rapat' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
            'lokasi' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'judul_agenda.required' => 'Judul agenda wajib diisi.',
            'tanggal_rapat.required' => 'Tanggal rapat wajib diisi.',
            'waktu_mulai.required' => 'Jam mulai wajib diisi.',
            'waktu_selesai.required' => 'Jam selesai wajib diisi.',
            'lokasi.required' => 'Lokasi rapat wajib diisi.',
        ];
    }
}
