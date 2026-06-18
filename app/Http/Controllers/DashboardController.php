<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function sekretarisDashboard()
    {
        $data = $this->dashboardService->getSekretarisDashboardData();
        return view('dashboard.sekretaris', $data);
    }

    public function anggotaDashboard()
    {
        $user = Auth::user();
        if (!$user->id_anggota) {
            return redirect()->route('login')->withErrors(['username' => 'User tidak terhubung ke profil anggota.']);
        }

        $data = $this->dashboardService->getAnggotaDashboardData($user->id_anggota);
        $data['user'] = $user;
        $data['anggota'] = $user->anggota;

        return view('dashboard.anggota', $data);
    }
}
