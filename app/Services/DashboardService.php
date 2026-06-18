<?php

namespace App\Services;

use App\Repositories\Contracts\AbsensiRepositoryInterface;
use App\Repositories\Contracts\AgendaRepositoryInterface;
use App\Repositories\Contracts\AnggotaRepositoryInterface;
use App\Models\AgendaRapat;
use App\Models\Absensi;

class DashboardService
{
    protected $absensiRepo;
    protected $agendaRepo;
    protected $anggotaRepo;

    public function __construct(
        AbsensiRepositoryInterface $absensiRepo,
        AgendaRepositoryInterface $agendaRepo,
        AnggotaRepositoryInterface $anggotaRepo
    ) {
        $this->absensiRepo = $absensiRepo;
        $this->agendaRepo = $agendaRepo;
        $this->anggotaRepo = $anggotaRepo;
    }

    public function getSekretarisDashboardData()
    {
        $totalAnggota = $this->anggotaRepo->getAllActiveCount();
        $totalAgenda = $this->agendaRepo->getAllCount();
        $totalAbsensi = $this->absensiRepo->getAllCount();

        // Retrieve report data to easily compute averages and rankings
        $reportData = $this->absensiRepo->getReportData();
        $membersStats = $reportData['data'];
        $totalAgendasCount = $reportData['total_agendas'];

        $averageAttendance = 0;
        $anggotaTerajin = null;
        $anggotaTidakHadir = null;

        if (count($membersStats) > 0) {
            $sumPercentages = array_sum(array_column($membersStats, 'percentage'));
            $averageAttendance = round($sumPercentages / count($membersStats), 2);

            // Sort to find Terajin and Tidak Hadir
            // Sort descending by percentage
            usort($membersStats, function ($a, $b) {
                return $b['percentage'] <=> $a['percentage'];
            });
            $anggotaTerajin = $membersStats[0];

            // Sort ascending by percentage to find least active
            usort($membersStats, function ($a, $b) {
                return $a['percentage'] <=> $b['percentage'];
            });
            $anggotaTidakHadir = $membersStats[0];
        }

        // Monthly trends for line chart
        $monthlyTrendsRaw = $this->absensiRepo->getMonthlyAttendanceTrend();
        $monthlyLabels = [];
        $monthlyValues = [];
        foreach ($monthlyTrendsRaw as $trend) {
            $monthlyLabels[] = date('F Y', strtotime($trend->month_year . '-01'));
            // Calculate average percentage per month
            // Average = (total weight / (total participants * 1)) * 100
            $monthlyValues[] = $trend->total_participants > 0 
                ? round(($trend->total_weight / $trend->total_participants) * 100, 2) 
                : 0;
        }

        // Status distribution for pie chart
        $statusRaw = $this->absensiRepo->getStatusDistribution();
        $statusLabels = [];
        $statusValues = [];
        foreach ($statusRaw as $stat) {
            $statusLabels[] = $stat->nama_status;
            $statusValues[] = $stat->count;
        }

        // Upcoming agendas
        $upcomingAgendas = $this->agendaRepo->getUpcomingAgendas(3);

        return [
            'total_anggota' => $totalAnggota,
            'total_agenda' => $totalAgenda,
            'total_absensi' => $totalAbsensi,
            'average_attendance' => $averageAttendance,
            'anggota_terajin' => $anggotaTerajin,
            'anggota_tidak_hadir' => $anggotaTidakHadir,
            'upcoming_agendas' => $upcomingAgendas,
            'chart_trend' => [
                'labels' => $monthlyLabels,
                'data' => $monthlyValues
            ],
            'chart_status' => [
                'labels' => $statusLabels,
                'data' => $statusValues
            ]
        ];
    }

    public function getAnggotaDashboardData($anggotaId)
    {
        $stats = $this->absensiRepo->getAttendanceStats($anggotaId);
        
        // Closest upcoming agenda
        $upcoming = $this->agendaRepo->getUpcomingAgendas(1)->first();

        // Recent attendance history
        $recentHistory = Absensi::with(['agenda', 'status'])
            ->where('id_anggota', $anggotaId)
            ->join('agenda_rapat', 'absensi.id_agenda', '=', 'agenda_rapat.id_agenda')
            ->orderBy('agenda_rapat.tanggal_rapat', 'desc')
            ->orderBy('agenda_rapat.waktu_mulai', 'desc')
            ->limit(5)
            ->get();

        return [
            'stats' => $stats,
            'upcoming_agenda' => $upcoming,
            'recent_history' => $recentHistory
        ];
    }
}
