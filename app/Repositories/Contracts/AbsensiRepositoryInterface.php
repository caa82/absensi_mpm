<?php

namespace App\Repositories\Contracts;

interface AbsensiRepositoryInterface
{
    public function create(array $data);
    public function findForMemberAndAgenda($memberId, $agendaId);
    public function getAttendanceStats($memberId = null);
    public function getMonthlyAttendanceTrend();
    public function getStatusDistribution();
    public function getAllCount();
    public function getReportData($month = null, $year = null, $search = null);
}
