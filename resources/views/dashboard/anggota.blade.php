@extends('layouts.app')

@section('title', 'Dashboard Anggota')

@section('content')
<div class="container-fluid p-0">

    <!-- Header Row -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="text-heading font-weight-bold">Dashboard Anggota</h3>
            <p class="text-secondary">Informasi ringkasan kehadiran dan agenda rapat Anda.</p>
        </div>
    </div>

    <!-- Profile Overview & Main Attendance Stat Card -->
    <div class="row mb-4 g-4">
        
        <!-- Profile Info Card -->
        <div class="col-lg-5">
            <div class="glass-card h-100 d-flex flex-column align-items-center justify-content-center text-center p-4">
                <div class="mb-3">
                    @if($anggota->foto_anggota)
                        <img src="{{ $anggota->foto_anggota }}" alt="Foto Profile" class="rounded-circle border border-4 border-primary" style="width: 110px; height: 110px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center border border-4 border-primary text-white font-weight-bold" style="width: 110px; height: 110px; font-size: 3rem;">
                            {{ strtoupper(substr($user->username, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <h4 class="text-heading font-weight-bold mb-1">{{ $anggota->nama_anggota }}</h4>
                <p class="text-secondary mb-2">{{ $anggota->jabatan }}</p>
                <small class="text-muted mb-3">NIM: {{ $anggota->nim }} | Angkatan: {{ $anggota->angkatan }}</small>
                
                <span class="badge bg-primary bg-opacity-25 text-primary border border-primary border-opacity-50 px-3 py-2">
                    Status Keanggotaan: {{ $anggota->status }}
                </span>
            </div>
        </div>

        <!-- Attendance Performance Card -->
        <div class="col-lg-7">
            <div class="glass-card h-100 d-flex flex-column justify-content-between p-4">
                <div>
                    <h5 class="card-title-premium">
                        <i class="bi bi-person-check-fill"></i> Ringkasan Kehadiran Anda
                    </h5>
                    <p class="text-secondary">Persentase kehadiran dihitung berdasarkan bobot status kehadiran Anda dibagi jumlah agenda rapat.</p>
                </div>
                
                <div class="row align-items-center my-3">
                    <div class="col-sm-5 text-center text-sm-start mb-3 mb-sm-0">
                        <h1 class="display-3 text-heading font-weight-bold mb-0" style="background: var(--accent-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                            {{ $stats['percentage'] }}%
                        </h1>
                        <small class="text-secondary">Persentase Kehadiran</small>
                    </div>
                    <div class="col-sm-7">
                        <div class="progress mb-2" style="height: 12px; background-color: rgba(255, 255, 255, 0.05); border-radius: 6px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $stats['percentage'] }}%; border-radius: 6px;" aria-valuenow="{{ $stats['percentage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small class="text-secondary d-block">
                            @if($stats['percentage'] >= 85)
                                <i class="bi bi-emoji-smile-fill text-success me-1"></i> Kinerja kehadiran sangat baik!
                            @elseif($stats['percentage'] >= 75)
                                <i class="bi bi-emoji-neutral-fill text-warning me-1"></i> Kehadiran cukup, pertahankan keaktifan.
                            @else
                                <i class="bi bi-emoji-frown-fill text-danger me-1"></i> Tingkatkan kehadiran Anda pada agenda selanjutnya!
                            @endif
                        </small>
                    </div>
                </div>

                <div class="border-top border-secondary border-opacity-10 pt-3">
                    <small class="text-secondary">Perhitungan bobot: Hadir (1, Izin (0), Sakit (0), Shift 2 Hadir Sebagian (0.5), Shift 2 Tidak Hadir ().</small>
                </div>
            </div>
        </div>

    </div>

    <!-- Attendance Breakdown Cards Grid -->
    <div class="row row-cols-2 row-cols-md-5 g-3 mb-4">
        <!-- Hadir -->
        <div class="col">
            <div class="glass-card text-center p-3">
                <h6 class="text-secondary mb-2">Hadir</h6>
                <h2 class="text-success font-weight-bold mb-0">{{ $stats['hadir'] }}</h2>
            </div>
        </div>
        <!-- Sakit -->
        <div class="col">
            <div class="glass-card text-center p-3">
                <h6 class="text-secondary mb-2">Sakit</h6>
                <h2 class="font-weight-bold mb-0" style="color: #ec4899;">{{ $stats['sakit'] }}</h2>
            </div>
        </div>
        <!-- Izin -->
        <div class="col">
            <div class="glass-card text-center p-3">
                <h6 class="text-secondary mb-2">Izin</h6>
                <h2 class="text-warning font-weight-bold mb-0">{{ $stats['izin'] }}</h2>
            </div>
        </div>
        <!-- Shift 2 Sebagian -->
        <div class="col">
            <div class="glass-card text-center p-3">
                <h6 class="text-secondary mb-2">Shift 2 Sebagian</h6>
                <h2 class="text-info font-weight-bold mb-0">{{ $stats['shift_2_hadir'] }}</h2>
            </div>
        </div>
        <!-- Shift 2 Tidak Hadir -->
        <div class="col">
            <div class="glass-card text-center p-3">
                <h6 class="text-secondary mb-2">Shift 2 Tidak Hadir</h6>
                <h2 class="text-danger font-weight-bold mb-0">{{ $stats['shift_2_absen'] }}</h2>
            </div>
        </div>
    </div>

    <!-- Upcoming Meeting & Recent History -->
    <div class="row g-4">
        
        <!-- Upcoming Meeting Alert -->
        <div class="col-lg-6">
            <div class="glass-card h-100">
                <h5 class="card-title-premium">
                    <i class="bi bi-bell-fill text-warning"></i> Agenda Rapat Terdekat
                </h5>
                
                @if($upcoming_agenda)
                    <div class="p-3 bg-secondary bg-opacity-10 border border-secondary border-opacity-15 rounded-4 mt-3">
                        <h6 class="text-heading font-weight-bold mb-2">{{ $upcoming_agenda->judul_agenda }}</h6>
                        <p class="text-secondary small mb-3">{{ Str::limit($upcoming_agenda->deskripsi, 120) }}</p>
                        
                        <div class="row g-2 text-secondary small mb-3">
                            <div class="col-6">
                                <i class="bi bi-calendar-check text-primary me-1"></i> {{ date('d F Y', strtotime($upcoming_agenda->tanggal_rapat)) }}
                            </div>
                            <div class="col-6">
                                <i class="bi bi-clock text-primary me-1"></i> {{ substr($upcoming_agenda->waktu_mulai, 0, 5) }} - {{ substr($upcoming_agenda->waktu_selesai, 0, 5) }} WIB
                            </div>
                            <div class="col-12">
                                <i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $upcoming_agenda->lokasi }}
                            </div>
                        </div>

                        <div class="d-grid">
                            <a href="{{ route('anggota.absensi.create') }}" class="btn btn-premium btn-sm">
                                <i class="bi bi-pencil-square me-1"></i> Isi Absensi Sekarang
                            </a>
                        </div>
                    </div>
                @else
                    <p class="text-secondary mt-3">Tidak ada agenda rapat terdekat dalam waktu dekat.</p>
                @endif
            </div>
        </div>

        <!-- Recent History -->
        <div class="col-lg-6">
            <div class="glass-card h-100">
                <h5 class="card-title-premium">
                    <i class="bi bi-clock-history"></i> Riwayat Absensi Terbaru
                </h5>
                
                @if($recent_history->count() > 0)
                    <div class="list-group list-group-flush bg-transparent mt-3">
                        @foreach($recent_history as $absen)
                            <div class="list-group-item bg-transparent border-secondary border-opacity-10 px-0 py-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-heading font-weight-semibold mb-1">{{ $absen->judul_agenda }}</h6>
                                    <small class="text-secondary d-block">
                                        <i class="bi bi-calendar me-1"></i> {{ date('d M Y', strtotime($absen->tanggal_rapat)) }} | {{ date('H:i', strtotime($absen->waktu_absen)) }} WIB
                                    </small>
                                    @if($absen->keterangan)
                                        <small class="text-muted italic mt-1 d-block">Ket: "{{ $absen->keterangan }}"</small>
                                    @endif
                                </div>
                                
                                @if($absen->id_status == 1)
                                    <span class="badge badge-hadir">Hadir</span>
                                @elseif($absen->id_status == 2)
                                    <span class="badge badge-shift2-hadir">Shift 2 Sebagian</span>
                                @elseif($absen->id_status == 3)
                                    <span class="badge badge-shift2-absen">Shift 2 Absen</span>
                                @elseif($absen->id_status == 4)
                                    <span class="badge badge-izin">Izin</span>
                                @elseif($absen->id_status == 5)
                                    <span class="badge badge-sakit">Sakit</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-secondary mt-3">Belum ada riwayat pengisian absensi.</p>
                @endif
            </div>
        </div>

    </div>

</div>
@endsection
