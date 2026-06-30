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
            'tanggal_rapat' => [
                'required',
                'date',
                'after_or_equal:today',
                function ($attribute, $value, $fail) {
                    $agendaId = $this->route('agenda');
                    if ($agendaId instanceof \App\Models\AgendaRapat) {
                        $agendaId = $agendaId->id_agenda;
                    }
                    $exists = \App\Models\AgendaRapat::where('tanggal_rapat', $value)
                        ->when($agendaId, function ($query, $id) {
                            return $query->where('id_agenda', '!=', $id);
                        })
                        ->exists();
                    if ($exists) {
                        $fail('Sudah ada rapat yang dijadwalkan pada tanggal ini. Maksimal 1 rapat per hari.');
                    }
                }
            ],
            'waktu_mulai' => 'required',
            'waktu_selesai' => [
                'required',
                function ($attribute, $value, $fail) {
                    $waktuMulai = $this->input('waktu_mulai');
                    if ($waktuMulai && $value <= $waktuMulai) {
                        $fail('Jam selesai harus setelah jam mulai.');
                    }
                }
            ],
            'lokasi' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'judul_agenda.required' => 'Judul agenda wajib diisi.',
            'tanggal_rapat.required' => 'Tanggal rapat wajib diisi.',
            'tanggal_rapat.date' => 'Format tanggal rapat tidak valid.',
            'tanggal_rapat.after_or_equal' => 'Tanggal rapat tidak boleh di masa lalu.',
            'waktu_mulai.required' => 'Jam mulai wajib diisi.',
            'waktu_selesai.required' => 'Jam selesai wajib diisi.',
            'lokasi.required' => 'Lokasi rapat wajib diisi.',
        ];
    }
}
