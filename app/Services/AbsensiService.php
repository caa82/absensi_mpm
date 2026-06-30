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

        // Check if current time is within allowed window (between 24 hours and 1 hour before waktu_mulai)
        $now = Carbon::now();
        $waktuMulai = Carbon::parse($agenda->tanggal_rapat . ' ' . $agenda->waktu_mulai);
        
        $windowStart = $waktuMulai->copy()->subHours(24);
        $windowEnd = $waktuMulai->copy()->subHour();

        if ($now->lt($windowStart)) {
            throw new \Exception('Absensi belum dibuka. Absensi baru dibuka mulai 24 jam sebelum rapat dimulai (' . $windowStart->format('d M Y, H:i') . ' WIB).');
        }

        if ($now->gt($windowEnd)) {
            throw new \Exception('Absensi sudah ditutup. Absensi ditutup 1 jam sebelum rapat dimulai (' . $windowEnd->format('d M Y, H:i') . ' WIB).');
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
            'keterangan' => $statusId == 5 ? 'Sakit' : ($statusId == 4 ? ($data['alasan'] ?? null) : null),
            'bukti_foto' => $data['bukti_foto'] ?? null,
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
