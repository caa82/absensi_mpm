<?php

namespace App\Services;

use App\Repositories\Contracts\AbsensiRepositoryInterface;
use App\Exports\AttendanceExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportService
{
    protected $absensiRepo;

    public function __construct(AbsensiRepositoryInterface $absensiRepo)
    {
        $this->absensiRepo = $absensiRepo;
    }

    public function exportAttendance($month = null, $year = null)
    {
        $reportData = $this->absensiRepo->getReportData($month, $year);

        // Convert month to Indonesian word
        $indonesianMonths = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $period = 'Semua Periode';
        if ($month && $year) {
            $monthName = $indonesianMonths[(int)$month] ?? $month;
            $period = "{$monthName} {$year}";
        } elseif ($year) {
            $period = "Tahun {$year}";
        } elseif ($month) {
            $monthName = $indonesianMonths[(int)$month] ?? $month;
            $period = "Bulan {$monthName}";
        }

        $fileName = 'rekap_kehadiran_' . str_replace(' ', '_', strtolower($period)) . '_' . time() . '.xlsx';

        return Excel::download(new AttendanceExport($reportData['data'], $period), $fileName);
    }
}
