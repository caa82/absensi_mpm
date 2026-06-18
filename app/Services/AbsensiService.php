<?php

namespace App\Services;

use App\Repositories\Contracts\AbsensiRepositoryInterface;
use App\Repositories\Contracts\AgendaRepositoryInterface;
use App\Models\Izin;
use Carbon\Carbon;

class AbsensiService
{
    protected $absensiRepo;
    protected $agendaRepo;

    public function __construct(
        AbsensiRepositoryInterface $absensiRepo,
        AgendaRepositoryInterface $agendaRepo
    ) {
        $this->absensiRepo = $absensiRepo;
        $this->agendaRepo = $agendaRepo;
    }

    public function submitAttendance($memberId, array $data)
    {
        $agendaId = $data['id_agenda'];
        $agenda = $this->agendaRepo->findById($agendaId);

        if (!$agenda) {
            throw new \Exception('Agenda tidak ditemukan.');
        }

        // Check if agenda date is in the past
        $today = date('Y-m-d');
        if ($agenda->tanggal_rapat < $today) {
            throw new \Exception('Agenda rapat ini sudah lewat dan tidak dapat diisi.');
        }

        // Check duplicate
        $existing = $this->absensiRepo->findForMemberAndAgenda($memberId, $agendaId);
        if ($existing) {
            throw new \Exception('Anda sudah mengisi absensi untuk agenda rapat ini.');
        }

        $statusId = $data['id_status'];

        // Create Absensi record
        $absensi = $this->absensiRepo->create([
            'id_agenda' => $agendaId,
            'id_anggota' => $memberId,
            'id_status' => $statusId,
            'waktu_absen' => Carbon::now(),
            'keterangan' => $statusId == 4 ? $data['alasan'] : null,
        ]);

        // If Izin, create associated Izin record
        if ($statusId == 4) {
            Izin::create([
                'id_absensi' => $absensi->id_absensi,
                'alasan' => $data['alasan'],
                'bukti_file' => $data['bukti_file'] ?? null,
                'status_verifikasi' => 'Pending'
            ]);
        }

        return $absensi;
    }
}
