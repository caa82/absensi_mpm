<?php

namespace App\Http\Controllers;

use App\Http\Requests\AbsensiStoreRequest;
use App\Services\AbsensiService;
use App\Services\ExportService;
use App\Repositories\Contracts\AbsensiRepositoryInterface;
use App\Models\StatusAbsensi;
use App\Models\AgendaRapat;
use App\Models\Absensi;
use App\Models\Izin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    protected $absensiService;
    protected $exportService;
    protected $absensiRepo;

    public function __construct(
        AbsensiService $absensiService,
        ExportService $exportService,
        AbsensiRepositoryInterface $absensiRepo
    ) {
        $this->absensiService = $absensiService;
        $this->exportService = $exportService;
        $this->absensiRepo = $absensiRepo;
    }

    public function create()
    {
        $user = Auth::user();
        if (!$user->id_anggota) {
            return redirect()->route('anggota.dashboard')->with('error', 'User tidak memiliki profil Anggota.');
        }

        // Already filled agenda IDs
        $filledAgendaIds = Absensi::where('id_anggota', $user->id_anggota)
            ->pluck('id_agenda')
            ->toArray();

        $now = Carbon::now();
        $nowStr = $now->toDateTimeString();

        // Fetch agendas where current time is between: (meeting_time - 24 hours) and (meeting_time - 1 hour)
        $query = AgendaRapat::whereRaw(
            "TIMESTAMP(CONCAT(tanggal_rapat, ' ', waktu_mulai)) - INTERVAL 24 HOUR <= ? 
             AND TIMESTAMP(CONCAT(tanggal_rapat, ' ', waktu_mulai)) - INTERVAL 1 HOUR >= ?",
            [$nowStr, $nowStr]
        );

        if (!empty($filledAgendaIds)) {
            $query->whereNotIn('id_agenda', $filledAgendaIds);
        }
        $agendas = $query->orderBy('tanggal_rapat', 'asc')->orderBy('waktu_mulai', 'asc')->get();

        // Get statuses
        $statuses = StatusAbsensi::all();

        return view('absensi.create', compact('agendas', 'statuses', 'filledAgendaIds'));
    }

    public function store(AbsensiStoreRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();

        if (!$user->id_anggota) {
            return redirect()->back()->with('error', 'User tidak memiliki profil Anggota.');
        }

        try {
            // Handle bukti_foto upload for Hadir (1) or Sakit (5)
            if (in_array($request->id_status, [1, 5]) && $request->hasFile('bukti_foto')) {
                $file = $request->file('bukti_foto');
                $prefix = $request->id_status == 1 ? 'bukti_hadir' : 'surat_sakit';
                $filename = $prefix . '_' . $user->id_anggota . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('bukti_absensi', $filename, 'public');
                $data['bukti_foto'] = parse_url(Storage::disk('public')->url($path), PHP_URL_PATH);
            }

            // Handle file upload if Izin (status 4)
            if ($request->id_status == 4 && $request->hasFile('bukti_file')) {
                $file = $request->file('bukti_file');
                $filename = 'bukti_izin_' . $user->id_anggota . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('bukti_izin', $filename, 'public');
                $data['bukti_file'] = parse_url(Storage::disk('public')->url($path), PHP_URL_PATH);
            }

            $this->absensiService->submitAttendance($user->id_anggota, $data);

            return redirect()->route('anggota.dashboard')
                ->with('success', 'Absensi Anda berhasil dikirim!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function rekap(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year', date('Y'));
        $search = $request->input('search');

        $reportData = $this->absensiRepo->getReportData($month, $year, $search);

        $indonesianMonths = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return view('absensi.rekap', [
            'total_agendas' => $reportData['total_agendas'],
            'members' => $reportData['data'],
            'months' => $indonesianMonths,
            'selectedMonth' => $month,
            'selectedYear' => $year,
            'search' => $search,
        ]);
    }

    public function export(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');

        return $this->exportService->exportAttendance($month, $year);
    }

    public function exportPdf(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year', date('Y'));
        $search = $request->input('search');

        $reportData = $this->absensiRepo->getReportData($month, $year, $search);

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

        $fileName = 'rekap_kehadiran_' . str_replace(' ', '_', strtolower($period)) . '_' . time() . '.pdf';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('absensi.pdf', [
            'total_agendas' => $reportData['total_agendas'],
            'members' => $reportData['data'],
            'period' => $period,
        ]);

        // Set paper size to A4 Landscape for better layout
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download($fileName);
    }

    public function detail($id_agenda)
    {
        $agenda = AgendaRapat::find($id_agenda);
        if (!$agenda) {
            return redirect()->route('sekretaris.agenda.index')->with('error', 'Agenda tidak ditemukan.');
        }

        $absensi = Absensi::with(['anggota', 'status', 'izin'])
            ->where('id_agenda', $id_agenda)
            ->get();

        return view('absensi.detail', compact('agenda', 'absensi'));
    }

    public function verifyIzin(Request $request, $id_izin)
    {
        $izin = Izin::find($id_izin);
        if (!$izin) {
            return redirect()->back()->with('error', 'Data izin tidak ditemukan.');
        }

        $status = $request->input('status'); // Disetujui or Ditolak
        if (in_array($status, ['Disetujui', 'Ditolak'])) {
            $izin->status_verifikasi = $status;
            $izin->save();
            return redirect()->back()->with('success', 'Status verifikasi izin berhasil diperbarui menjadi ' . $status . '!');
        }

        return redirect()->back()->with('error', 'Status verifikasi tidak valid.');
    }
}
