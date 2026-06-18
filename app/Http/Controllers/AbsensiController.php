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

        // Fetch active agendas that are today or in the future
        $agendas = AgendaRapat::where('tanggal_rapat', '>=', date('Y-m-d'))
            ->orderBy('tanggal_rapat', 'asc')
            ->get();

        // Get statuses
        $statuses = StatusAbsensi::all();

        // Already filled agenda IDs
        $filledAgendaIds = Absensi::where('id_anggota', $user->id_anggota)
            ->pluck('id_agenda')
            ->toArray();

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
            // Handle file upload if Izin (status 4)
            if ($request->id_status == 4 && $request->hasFile('bukti_file')) {
                $file = $request->file('bukti_file');
                $filename = 'bukti_izin_' . $user->id_anggota . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('public/bukti_izin', $filename);
                $data['bukti_file'] = Storage::url($path);
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

        $reportData = $this->absensiRepo->getReportData($month, $year);

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
        ]);
    }

    public function export(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');

        return $this->exportService->exportAttendance($month, $year);
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
