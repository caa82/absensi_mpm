<?php

namespace App\Repositories\Eloquent;

use App\Models\Absensi;
use App\Models\AgendaRapat;
use App\Models\Anggota;
use App\Models\StatusAbsensi;
use App\Repositories\Contracts\AbsensiRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AbsensiRepository implements AbsensiRepositoryInterface
{
    public function create(array $data)
    {
        return Absensi::create($data);
    }

    public function findForMemberAndAgenda($memberId, $agendaId)
    {
        return Absensi::where('id_anggota', $memberId)
            ->where('id_agenda', $agendaId)
            ->first();
    }

    public function getAttendanceStats($memberId = null)
    {
        $totalAgendas = AgendaRapat::where('tanggal_rapat', '<=', date('Y-m-d'))->count();

        if ($memberId) {
            $absensiList = Absensi::with('status')
                ->where('id_anggota', $memberId)
                ->get();

            $hadir = $absensiList->where('id_status', 1)->count();
            $shift2Hadir = $absensiList->where('id_status', 2)->count();
            $shift2Absen = $absensiList->where('id_status', 3)->count();
            $izin = $absensiList->where('id_status', 4)->count();
            $sakit = $absensiList->where('id_status', 5)->count();

            // Total weight
            $totalWeight = ($hadir * 1.0) + ($shift2Hadir * 0.5) + ($shift2Absen * 0.0) + ($izin * 0.0) + ($sakit * 0.0);

            // Calculate percentage based on total past agendas
            $percentage = $totalAgendas > 0 ? round(($totalWeight / $totalAgendas) * 100, 2) : 0;

            return [
                'total_agendas' => $totalAgendas,
                'hadir' => $hadir,
                'shift_2_hadir' => $shift2Hadir,
                'shift_2_absen' => $shift2Absen,
                'izin' => $izin,
                'sakit' => $sakit,
                'total_weight' => $totalWeight,
                'percentage' => $percentage
            ];
        }

        return null;
    }

    public function getMonthlyAttendanceTrend()
    {
        // Monthly attendance trend for past 6 months
        $trend = DB::table('absensi')
            ->join('agenda_rapat', 'absensi.id_agenda', '=', 'agenda_rapat.id_agenda')
            ->join('status_absensi', 'absensi.id_status', '=', 'status_absensi.id_status')
            ->select(
                DB::raw("DATE_FORMAT(agenda_rapat.tanggal_rapat, '%Y-%m') as month_year"),
                DB::raw("SUM(status_absensi.bobot_kehadiran) as total_weight"),
                DB::raw("COUNT(DISTINCT absensi.id_anggota) as total_participants")
            )
            ->groupBy('month_year')
            ->orderBy('month_year', 'asc')
            ->limit(6)
            ->get();

        return $trend;
    }

    public function getStatusDistribution()
    {
        $distribution = DB::table('absensi')
            ->join('status_absensi', 'absensi.id_status', '=', 'status_absensi.id_status')
            ->select('status_absensi.nama_status', DB::raw('count(*) as count'))
            ->groupBy('status_absensi.id_status', 'status_absensi.nama_status')
            ->get();

        return $distribution;
    }

    public function getAllCount()
    {
        return Absensi::count();
    }

    public function getReportData($month = null, $year = null, $search = null)
    {
        // 1. Get agendas in the filtered range
        $agendaQuery = AgendaRapat::query();
        if ($month) {
            $agendaQuery->whereMonth('tanggal_rapat', $month);
        }
        if ($year) {
            $agendaQuery->whereYear('tanggal_rapat', $year);
        }
        
        $agendas = $agendaQuery->pluck('id_agenda');
        $totalAgendasInPeriod = $agendas->count();

        // 2. Fetch all members (with optional search filter)
        $anggotaQuery = Anggota::where('status', 'Aktif');
        if ($search) {
            $anggotaQuery->where(function ($q) use ($search) {
                $q->where('nama_anggota', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('jabatan', 'like', "%{$search}%");
            });
        }
        $anggotaList = $anggotaQuery->orderBy('nama_anggota', 'asc')->get();

        $report = [];
        foreach ($anggotaList as $idx => $anggota) {
            if ($totalAgendasInPeriod > 0) {
                $absens = Absensi::where('id_anggota', $anggota->id_anggota)
                    ->whereIn('id_agenda', $agendas)
                    ->get();

                $hadir = $absens->where('id_status', 1)->count();
                $shift2Hadir = $absens->where('id_status', 2)->count();
                $shift2Absen = $absens->where('id_status', 3)->count();
                $izin = $absens->where('id_status', 4)->count();
                $sakit = $absens->where('id_status', 5)->count();

                $totalWeight = ($hadir * 1.0) + ($shift2Hadir * 0.5) + ($shift2Absen * 0.0) + ($izin * 0.0) + ($sakit * 0.0);
                $percentage = round(($totalWeight / $totalAgendasInPeriod) * 100, 2);
            } else {
                $hadir = 0;
                $shift2Hadir = 0;
                $shift2Absen = 0;
                $izin = 0;
                $sakit = 0;
                $percentage = 0;
            }

            $report[] = [
                'no' => $idx + 1,
                'nim' => $anggota->nim,
                'nama' => $anggota->nama_anggota,
                'jabatan' => $anggota->jabatan,
                'hadir' => $hadir,
                'izin' => $izin,
                'sakit' => $sakit,
                'shift_2_hadir' => $shift2Hadir,
                'shift_2_absen' => $shift2Absen,
                'percentage' => $percentage
            ];
        }

        return [
            'total_agendas' => $totalAgendasInPeriod,
            'data' => $report
        ];
    }
}
