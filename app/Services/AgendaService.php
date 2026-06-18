<?php

namespace App\Services;

use App\Repositories\Contracts\AgendaRepositoryInterface;

class AgendaService
{
    protected $agendaRepo;

    public function __construct(AgendaRepositoryInterface $agendaRepo)
    {
        $this->agendaRepo = $agendaRepo;
    }

    public function getPaginatedAgendas($perPage = 10, $search = null)
    {
        return $this->agendaRepo->paginate($perPage, $search);
    }

    public function getAgendaById($id)
    {
        return $this->agendaRepo->findById($id);
    }

    public function createAgenda(array $data)
    {
        return $this->agendaRepo->create($data);
    }

    public function updateAgenda($id, array $data)
    {
        return $this->agendaRepo->update($id, $data);
    }

    public function deleteAgenda($id)
    {
        return $this->agendaRepo->delete($id);
    }
}
