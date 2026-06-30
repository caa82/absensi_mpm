@extends('layouts.app')

@section('title', 'Dashboard Sekretaris')

@section('content')
<div class="container-fluid p-0">

    <!-- Header Row -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="text-white font-weight-bold">Dashboard Sekretaris</h3>
            <p class="text-secondary">Statistik tingkat kehadiran dan ringkasan aktivitas organisasi MPM.</p>
        </div>
    </div>

    <!-- Statistics Cards Grid -->
    <div class="row mb-4 g-4">
        
        <!-- Total Anggota -->
        <div class="col-xl-3 col-sm-6">
            <div class="glass-card d-flex align-items-center">
                <div class="rounded-circle bg-primary bg-opacity-25 p-3 text-primary me-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                    <i class="bi bi-people-fill" style="font-size: 1.6rem;"></i>
                </div>
                <div>
                    <h6 class="text-secondary mb-1">Total Anggota</h6>
                    <h3 class="text-white mb-0 font-weight-bold">{{ $total_anggota }}</h3>
                </div>
            </div>
        </div>

        <!-- Total Agenda -->
        <div class="col-xl-3 col-sm-6">
            <div class="glass-card d-flex align-items-center">
                <div class="rounded-circle bg-warning bg-opacity-25 p-3 text-warning me-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                    <i class="bi bi-calendar2-week-fill" style="font-size: 1.6rem;"></i>
                </div>
                <div>
                    <h6 class="text-secondary mb-1">Total Agenda</h6>
                    <h3 class="text-white mb-0 font-weight-bold">{{ $total_agenda }}</h3>
                </div>
            </div>
        </div>

        <!-- Total Absensi -->
        <div class="col-xl-3 col-sm-6">
            <div class="glass-card d-flex align-items-center">
                <div class="rounded-circle bg-success bg-opacity-25 p-3 text-success me-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                    <i class="bi bi-check2-circle" style="font-size: 1.6rem;"></i>
                </div>
                <div>
                    <h6 class="text-secondary mb-1">Total Absensi</h6>
                    <h3 class="text-white mb-0 font-weight-bold">{{ $total_absensi }}</h3>
                </div>
            </div>
        </div>

        <!-- Rerata Kehadiran -->
        <div class="col-xl-3 col-sm-6">
            <div class="glass-card d-flex align-items-center">
                <div class="rounded-circle bg-info bg-opacity-25 p-3 text-info me-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                    <i class="bi bi-percent" style="font-size: 1.6rem;"></i>
                </div>
                <div>
                    <h6 class="text-secondary mb-1">Kehadiran Rata-Rata</h6>
                    <h3 class="text-white mb-0 font-weight-bold">{{ $average_attendance }}%</h3>
                </div>
            </div>
        </div>

    </div>

    <!-- Active & Inactive Members Cards -->
    <div class="row mb-4 g-4">
        
        <!-- Member Terajin -->
        <div class="col-md-6">
            <div class="glass-card h-100">
                <h5 class="card-title-premium text-success">
                    <i class="bi bi-trophy-fill text-success"></i> Anggota Terajin
                </h5>
                @if($anggota_terajin)
                    <div class="d-flex align-items-center mt-3">
                        <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center text-success font-weight-bold me-3" style="width: 55px; height: 55px; font-size: 1.5rem;">
                            {{ strtoupper(substr($anggota_terajin['nama'], 0, 1)) }}
                        </div>
                        <div>
                            <h6 class="text-white mb-0 font-weight-semibold">{{ $anggota_terajin['nama'] }}</h6>
                            <small class="text-secondary">{{ $anggota_terajin['jabatan'] }} (NIM: {{ $anggota_terajin['nim'] }})</small>
                            <div class="mt-1">
                                <span class="badge bg-success bg-opacity-25 text-success">Kehadiran: {{ $anggota_terajin['percentage'] }}%</span>
                            </div>
                        </div>
                    </div>
                @else
                    <p class="text-secondary mt-3">Belum ada data kehadiran.</p>
                @endif
            </div>
        </div>

        <!-- Paling Sering Absen -->
        <div class="col-md-6">
            <div class="glass-card h-100">
                <h5 class="card-title-premium text-danger">
                    <i class="bi bi-exclamation-octagon-fill text-danger"></i> Paling Sering Tidak Hadir
                </h5>
                @if($anggota_tidak_hadir)
                    <div class="d-flex align-items-center mt-3">
                        <div class="rounded-circle bg-danger bg-opacity-10 d-flex align-items-center justify-content-center text-danger font-weight-bold me-3" style="width: 55px; height: 55px; font-size: 1.5rem;">
                            {{ strtoupper(substr($anggota_tidak_hadir['nama'], 0, 1)) }}
                        </div>
                        <div>
                            <h6 class="text-white mb-0 font-weight-semibold">{{ $anggota_tidak_hadir['nama'] }}</h6>
                            <small class="text-secondary">{{ $anggota_tidak_hadir['jabatan'] }} (NIM: {{ $anggota_tidak_hadir['nim'] }})</small>
                            <div class="mt-1">
                                <span class="badge bg-danger bg-opacity-25 text-danger">Kehadiran: {{ $anggota_tidak_hadir['percentage'] }}%</span>
                            </div>
                        </div>
                    </div>
                @else
                    <p class="text-secondary mt-3">Belum ada data kehadiran.</p>
                @endif
            </div>
        </div>

    </div>

    <!-- Charts Row -->
    <div class="row mb-4 g-4">
        
        <!-- Attendance Trend Chart -->
        <div class="col-lg-8">
            <div class="glass-card h-100">
                <h5 class="card-title-premium">
                    <i class="bi bi-graph-up-arrow"></i> Grafik Kehadiran Bulanan (%)
                </h5>
                <div style="height: 300px; position: relative;">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Status Distribution Chart -->
        <div class="col-lg-4">
            <div class="glass-card h-100">
                <h5 class="card-title-premium">
                    <i class="bi bi-pie-chart-fill"></i> Statistik Status Absensi
                </h5>
                <div style="height: 300px; position: relative; display: flex; align-items: center; justify-content: center;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

    </div>

    <!-- Upcoming Agendas & Recent Links -->
    <div class="row g-4">
        
        <div class="col-12">
            <div class="glass-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title-premium mb-0">
                        <i class="bi bi-hourglass-split"></i> Agenda Rapat Terdekat
                    </h5>
                    <a href="{{ route('sekretaris.agenda.index') }}" class="btn btn-sm btn-outline-premium">Kelola Agenda</a>
                </div>
                
                @if($upcoming_agendas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Judul Agenda</th>
                                    <th>Tanggal Rapat</th>
                                    <th>Waktu</th>
                                    <th>Lokasi</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($upcoming_agendas as $agenda)
                                    <tr>
                                        <td>
                                            <span class="text-white font-weight-semibold">{{ $agenda->judul_agenda }}</span>
                                        </td>
                                        <td>{{ date('d M Y', strtotime($agenda->tanggal_rapat)) }}</td>
                                        <td>{{ substr($agenda->waktu_mulai, 0, 5) }} - {{ substr($agenda->waktu_selesai, 0, 5) }} WIB</td>
                                        <td>
                                            <span class="text-secondary"><i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $agenda->lokasi }}</span>
                                        <td class="text-center">
                                            <a href="{{ route('sekretaris.absensi.detail', $agenda->id_agenda) }}" class="btn btn-sm btn-premium py-1 px-2" title="Detail Absen">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-secondary mb-0">Tidak ada agenda rapat terdekat.</p>
                @endif
            </div>
        </div>

    </div>

</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Trend Chart
        const trendCtx = document.getElementById('trendChart').getContext('2d');
        const trendLabels = {!! json_encode($chart_trend['labels']) !!};
        const trendData = {!! json_encode($chart_trend['data']) !!};
        
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: trendLabels.length > 0 ? trendLabels : ['Belum Ada Data'],
                datasets: [{
                    label: 'Persentase Kehadiran',
                    data: trendData.length > 0 ? trendData : [0],
                    borderColor: '#8b5cf6',
                    backgroundColor: 'rgba(139, 92, 246, 0.15)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        min: 0,
                        max: 100,
                        ticks: { color: '#94a3b8' },
                        grid: { color: 'rgba(255, 255, 255, 0.05)' }
                    },
                    x: {
                        ticks: { color: '#94a3b8' },
                        grid: { display: false }
                    }
                }
            }
        });

        // Status Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusLabels = {!! json_encode($chart_status['labels']) !!};
        const statusData = {!! json_encode($chart_status['data']) !!};

        const defaultColors = ['#10b981', '#6366f1', '#ef4444', '#f59e0b'];

        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: statusLabels.length > 0 ? statusLabels : ['Belum Ada Data'],
                datasets: [{
                    data: statusData.length > 0 ? statusData : [100],
                    backgroundColor: statusData.length > 0 ? defaultColors.slice(0, statusLabels.length) : ['#334155'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#94a3b8',
                            boxWidth: 12,
                            padding: 15
                        }
                    }
                },
                cutout: '70%'
            }
        });
    });
</script>
@endsection
