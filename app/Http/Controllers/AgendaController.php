<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgendaStoreRequest;
use App\Services\AgendaService;
use App\Models\AgendaRapat;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AgendaController extends Controller
{
    protected $agendaService;

    public function __construct(AgendaService $agendaService)
    {
        $this->agendaService = $agendaService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $agendas = $this->agendaService->getPaginatedAgendas(10, $search);

        return view('agenda.index', compact('agendas', 'search'));
    }

    public function create()
    {
        return view('agenda.create');
    }

    public function store(AgendaStoreRequest $request)
    {
        $data = $request->validated();
        $data['dibuat_oleh'] = Auth::id();

        $this->agendaService->createAgenda($data);

        return redirect()->route('sekretaris.agenda.index')
            ->with('success', 'Agenda rapat berhasil dibuat!');
    }

    public function show($id)
    {
        $agenda = $this->agendaService->getAgendaById($id);

        if (!$agenda) {
            return redirect()->route('sekretaris.agenda.index')->with('error', 'Agenda tidak ditemukan.');
        }

        return view('agenda.show', compact('agenda'));
    }

    public function edit($id)
    {
        $agenda = $this->agendaService->getAgendaById($id);

        if (!$agenda) {
            return redirect()->route('sekretaris.agenda.index')->with('error', 'Agenda tidak ditemukan.');
        }

        // Check if agenda date is in the past, block editing if required: "Agenda yang sudah lewat tidak dapat diubah"
        $today = date('Y-m-d');
        if ($agenda->tanggal_rapat < $today) {
            return redirect()->route('sekretaris.agenda.index')->with('error', 'Agenda rapat yang sudah lewat tidak dapat diubah.');
        }

        return view('agenda.edit', compact('agenda'));
    }

    public function update(AgendaStoreRequest $request, $id)
    {
        $agenda = $this->agendaService->getAgendaById($id);

        if (!$agenda) {
            return redirect()->route('sekretaris.agenda.index')->with('error', 'Agenda tidak ditemukan.');
        }

        // Lock editing for past agendas
        $today = date('Y-m-d');
        if ($agenda->tanggal_rapat < $today) {
            return redirect()->route('sekretaris.agenda.index')->with('error', 'Agenda rapat yang sudah lewat tidak dapat diubah.');
        }

        $this->agendaService->updateAgenda($id, $request->validated());

        return redirect()->route('sekretaris.agenda.index')
            ->with('success', 'Agenda rapat berhasil diubah!');
    }

    public function destroy($id)
    {
        $agenda = $this->agendaService->getAgendaById($id);

        if (!$agenda) {
            return redirect()->route('sekretaris.agenda.index')->with('error', 'Agenda tidak ditemukan.');
        }

        // Lock deletion for past agendas if desired, or keep it open for deletion but we block editing.
        $this->agendaService->deleteAgenda($id);

        return redirect()->route('sekretaris.agenda.index')
            ->with('success', 'Agenda rapat berhasil dihapus!');
    }

    /**
     * Show notula form for a specific agenda (Sekretaris)
     */
    public function showNotula($id)
    {
        $agenda = AgendaRapat::find($id);
        if (!$agenda) {
            return redirect()->route('sekretaris.agenda.index')->with('error', 'Agenda tidak ditemukan.');
        }

        return view('agenda.notula', compact('agenda'));
    }

    /**
     * Store/Update notula for a specific agenda (Sekretaris)
     */
    public function storeNotula(Request $request, $id)
    {
        $agenda = AgendaRapat::find($id);
        if (!$agenda) {
            return redirect()->route('sekretaris.agenda.index')->with('error', 'Agenda tidak ditemukan.');
        }

        $request->validate([
            'notula' => 'required_without:notula_file|nullable|string',
            'notula_file' => 'required_without:notula|nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ], [
            'notula.required_without' => 'Tulis notula atau unggah file notula rapat.',
            'notula_file.required_without' => 'Unggah file notula atau tulis notula rapat.',
            'notula_file.file' => 'Notula harus berupa file.',
            'notula_file.mimes' => 'Format file notula harus PDF, Word (DOC/DOCX), atau Gambar (JPG/JPEG/PNG).',
            'notula_file.max' => 'Ukuran file notula maksimal 5MB.',
        ]);

        if ($request->hasFile('notula_file')) {
            // Delete old file if exists
            if ($agenda->notula_file) {
                $parsedUrl = parse_url($agenda->notula_file, PHP_URL_PATH);
                $oldPath = preg_replace('/^\/storage\//', '', $parsedUrl);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            
            $file = $request->file('notula_file');
            $filename = 'notula_rapat_' . $id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('notula', $filename, 'public');
            $agenda->notula_file = parse_url(Storage::disk('public')->url($path), PHP_URL_PATH);
        }

        $agenda->notula = $request->input('notula');
        $agenda->save();

        return redirect()->route('sekretaris.agenda.index')
            ->with('success', 'Notula rapat berhasil disimpan!');
    }

    /**
     * Daftar Rapat for Anggota
     */
    public function rapatIndex(Request $request)
    {
        $search = $request->input('search');
        $query = AgendaRapat::query();
        
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul_agenda', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $agendas = $query->orderBy('tanggal_rapat', 'desc')
                         ->orderBy('waktu_mulai', 'desc')
                         ->paginate(10);

        return view('rapat.index', compact('agendas', 'search'));
    }

    /**
     * Detail Rapat for Anggota (including notula)
     */
    public function rapatShow($id)
    {
        $agenda = AgendaRapat::with('creator')->find($id);
        if (!$agenda) {
            return redirect()->route('anggota.rapat.index')->with('error', 'Rapat tidak ditemukan.');
        }

        $user = Auth::user();

        // Get this member's attendance for this meeting
        $myAbsensi = null;
        if ($user->id_anggota) {
            $myAbsensi = Absensi::with(['status', 'izin'])
                ->where('id_agenda', $id)
                ->where('id_anggota', $user->id_anggota)
                ->first();
        }

        return view('rapat.show', compact('agenda', 'myAbsensi'));
    }
}
