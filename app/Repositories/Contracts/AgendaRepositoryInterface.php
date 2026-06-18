<?php

namespace App\Repositories\Contracts;

interface AgendaRepositoryInterface
{
    public function all();
    public function findById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function getUpcomingAgendas($limit);
    public function getAllCount();
    public function paginate($perPage = 10, $search = null);
}
