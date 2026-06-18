<?php

namespace App\Repositories\Eloquent;

use App\Models\AgendaRapat;
use App\Repositories\Contracts\AgendaRepositoryInterface;

class AgendaRepository implements AgendaRepositoryInterface
{
    public function all()
    {
        return AgendaRapat::orderBy('tanggal_rapat', 'desc')->get();
    }

    public function findById($id)
    {
        return AgendaRapat::find($id);
    }

    public function create(array $data)
    {
        return AgendaRapat::create($data);
    }

    public function update($id, array $data)
    {
        $agenda = AgendaRapat::find($id);
        if ($agenda) {
            $agenda->update($data);
            return $agenda;
        }
        return null;
    }

    public function delete($id)
    {
        $agenda = AgendaRapat::find($id);
        if ($agenda) {
            return $agenda->delete();
        }
        return false;
    }

    public function getUpcomingAgendas($limit)
    {
        // Upcoming includes today and future
        return AgendaRapat::where('tanggal_rapat', '>=', date('Y-m-d'))
            ->orderBy('tanggal_rapat', 'asc')
            ->orderBy('waktu_mulai', 'asc')
            ->limit($limit)
            ->get();
    }

    public function getAllCount()
    {
        return AgendaRapat::count();
    }

    public function paginate($perPage = 10, $search = null)
    {
        $query = AgendaRapat::query();
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul_agenda', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }
        return $query->orderBy('tanggal_rapat', 'desc')
                     ->orderBy('waktu_mulai', 'desc')
                     ->paginate($perPage);
    }
}
