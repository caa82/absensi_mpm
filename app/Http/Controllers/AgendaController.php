<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgendaStoreRequest;
use App\Services\AgendaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
