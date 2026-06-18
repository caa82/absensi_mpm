<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Absensi MPM') | MPM Politeknik Astra</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.1/dist/sweetalert2.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-bg: #0f172a;
            --sidebar-bg: #1e293b;
            --card-bg: rgba(30, 41, 59, 0.7);
            --border-color: rgba(255, 255, 255, 0.08);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --accent-blue: #3b82f6;
            --accent-gradient: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            --font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            font-family: var(--font-family);
            background-color: var(--primary-bg);
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            transition: all 0.3s;
        }

        .sidebar-brand {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            border-bottom: 1px solid var(--border-color);
        }

        .sidebar-brand img {
            width: 42px;
            height: 42px;
            object-fit: cover;
            border-radius: 50%;
            margin-right: 10px;
            border: 2px solid var(--accent-blue);
        }

        .sidebar-brand span {
            font-weight: 800;
            font-size: 1.1rem;
            letter-spacing: 0.5px;
            background: var(--accent-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .sidebar-menu {
            padding: 1.5rem 1rem;
            list-style: none;
            margin: 0;
        }

        .menu-header {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--text-secondary);
            margin-bottom: 0.75rem;
            padding-left: 0.5rem;
            letter-spacing: 1px;
        }

        .menu-item {
            margin-bottom: 0.5rem;
        }

        .menu-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.2s;
            font-weight: 500;
        }

        .menu-link i {
            margin-right: 10px;
            font-size: 1.2rem;
            transition: transform 0.2s;
        }

        .menu-link:hover {
            color: var(--text-primary);
            background-color: rgba(255, 255, 255, 0.04);
        }

        .menu-link:hover i {
            transform: translateX(3px);
        }

        .menu-link.active {
            background: var(--accent-gradient);
            color: var(--text-primary);
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }

        /* Top Header */
        .top-navbar {
            margin-left: 260px;
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border-color);
            background-color: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(10px);
            position: sticky;
            top: 0;
            z-index: 90;
        }

        /* Content Container */
        .main-content {
            margin-left: 260px;
            padding: 2rem;
            min-height: calc(100vh - 75px);
        }

        /* Premium Glass Card */
        .glass-card {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.2);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .glass-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px 0 rgba(0, 0, 0, 0.3);
        }

        .card-title-premium {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 1.25rem;
            color: var(--text-primary);
            display: flex;
            align-items: center;
        }

        .card-title-premium i {
            margin-right: 8px;
            color: var(--accent-blue);
        }

        /* Buttons */
        .btn-premium {
            background: var(--accent-gradient);
            border: none;
            color: white;
            padding: 0.6rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.2);
        }

        .btn-premium:hover {
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
            transform: translateY(-1px);
            color: white;
        }

        .btn-outline-premium {
            background: transparent;
            border: 1px solid var(--accent-blue);
            color: var(--text-primary);
            padding: 0.6rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-outline-premium:hover {
            background: var(--accent-gradient);
            border-color: transparent;
            color: white;
            transform: translateY(-1px);
        }

        /* DataTables Custom Styling */
        .dataTables_wrapper {
            color: var(--text-secondary);
        }
        
        .table {
            color: var(--text-primary) !important;
        }

        .table th {
            border-bottom: 2px solid var(--border-color) !important;
            color: var(--text-primary) !important;
            font-weight: 600;
        }

        .table td {
            border-bottom: 1px solid var(--border-color) !important;
            color: var(--text-secondary) !important;
            vertical-align: middle;
        }

        .page-link {
            background-color: var(--sidebar-bg);
            border-color: var(--border-color);
            color: var(--text-secondary);
        }

        .page-link:hover {
            background-color: rgba(255, 255, 255, 0.08);
            color: var(--text-primary);
        }

        .active > .page-link {
            background: var(--accent-gradient);
            border-color: transparent;
        }

        /* Badges */
        .badge-hadir {
            background-color: rgba(16, 185, 129, 0.15) !important;
            color: #10b981 !important;
            border: 1px solid rgba(16, 185, 129, 0.3);
            padding: 0.5em 0.8em;
            border-radius: 8px;
        }

        .badge-izin {
            background-color: rgba(245, 158, 11, 0.15) !important;
            color: #f59e0b !important;
            border: 1px solid rgba(245, 158, 11, 0.3);
            padding: 0.5em 0.8em;
            border-radius: 8px;
        }

        .badge-shift2-hadir {
            background-color: rgba(99, 102, 241, 0.15) !important;
            color: #6366f1 !important;
            border: 1px solid rgba(99, 102, 241, 0.3);
            padding: 0.5em 0.8em;
            border-radius: 8px;
        }

        .badge-shift2-absen {
            background-color: rgba(239, 68, 68, 0.15) !important;
            color: #ef4444 !important;
            border: 1px solid rgba(239, 68, 68, 0.3);
            padding: 0.5em 0.8em;
            border-radius: 8px;
        }

        /* Responsive Sidebar Drawer */
        @media (max-width: 992px) {
            .sidebar {
                left: -260px;
            }
            .sidebar.active {
                left: 0;
            }
            .top-navbar, .main-content {
                margin-left: 0;
            }
            .top-navbar {
                padding: 1rem;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('images/logo_mpm.png') }}" alt="Logo MPM">
            <span>MPM POLITEKNIK ASTRA</span>
        </div>
        
        <ul class="sidebar-menu">
            @php
                $role = Auth::user()->role;
                $prefix = strtolower($role);
            @endphp
            
            <li class="menu-header">Menu Utama</li>
            
            <li class="menu-item">
                <a href="{{ route($prefix . '.dashboard') }}" class="menu-link {{ Route::is($prefix . '.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            @if($role === 'Sekretaris')
                <li class="menu-header">Kesekretariatan</li>
                <li class="menu-item">
                    <a href="{{ route('sekretaris.agenda.index') }}" class="menu-link {{ Route::is('sekretaris.agenda.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-event-fill"></i>
                        <span>Kelola Agenda</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('sekretaris.absensi.rekap') }}" class="menu-link {{ Route::is('sekretaris.absensi.rekap') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-spreadsheet-fill"></i>
                        <span>Rekap Kehadiran</span>
                    </a>
                </li>
            @endif
            
            @if($role === 'Anggota')
                <li class="menu-header">Keanggotaan</li>
                <li class="menu-item">
                    <a href="{{ route('anggota.absensi.create') }}" class="menu-link {{ Route::is('anggota.absensi.create') ? 'active' : '' }}">
                        <i class="bi bi-pencil-square"></i>
                        <span>Isi Absensi</span>
                    </a>
                </li>
            @endif

            <li class="menu-header">Pengaturan</li>
            <li class="menu-item">
                <a href="{{ route('profile.show') }}" class="menu-link {{ Route::is('profile.show') ? 'active' : '' }}">
                    <i class="bi bi-person-fill-gear"></i>
                    <span>Kelola Profil</span>
                </a>
            </li>
            <li class="menu-item mt-4">
                <form action="{{ route('logout') }}" method="POST" id="logout-form" class="d-none">
                    @csrf
                </form>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="menu-link text-danger">
                    <i class="bi bi-box-arrow-left text-danger"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Header & Content -->
    <div class="top-navbar">
        <button class="btn btn-outline-light d-lg-none" id="sidebar-toggle">
            <i class="bi bi-list"></i>
        </button>
        
        <div class="d-none d-md-block">
            <h5 class="mb-0 font-weight-bold text-white">Sistem Absensi Anggota MPM</h5>
            <small class="text-secondary">Politeknik Astra - Cikarang</small>
        </div>
        
        <div class="d-flex align-items-center">
            <div class="text-end me-3 d-none d-sm-block">
                <h6 class="mb-0 text-white font-weight-semibold">{{ Auth::user()->anggota->nama_anggota ?? Auth::user()->username }}</h6>
                <small class="text-secondary">
                    <span class="badge {{ $role === 'Sekretaris' ? 'bg-danger' : 'bg-primary' }} rounded-pill">{{ $role }}</span>
                </small>
            </div>
            
            <a href="{{ route('profile.show') }}">
                @if(Auth::user()->anggota && Auth::user()->anggota->foto_anggota)
                    <img src="{{ Auth::user()->anggota->foto_anggota }}" alt="Profile Photo" class="rounded-circle border border-2 border-primary" style="width: 45px; height: 45px; object-fit: cover;">
                @else
                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center border border-2 border-primary text-white font-weight-bold" style="width: 45px; height: 45px; font-size: 1.2rem;">
                        {{ strtoupper(substr(Auth::user()->username, 0, 1)) }}
                    </div>
                @endif
            </a>
        </div>
    </div>

    <!-- Main Container -->
    <div class="main-content">
        @yield('content')
    </div>

    <!-- Script imports -->
    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Bootstrap 5 Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.1/dist/sweetalert2.all.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom scripts -->
    <script>
        $(document).ready(function() {
            // Sidebar Toggle
            $('#sidebar-toggle').on('click', function() {
                $('#sidebar').toggleClass('active');
            });
            
            // SweetAlert Notifications
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    background: '#1e293b',
                    color: '#f8fafc',
                    confirmButtonColor: '#3b82f6'
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: "{{ session('error') }}",
                    background: '#1e293b',
                    color: '#f8fafc',
                    confirmButtonColor: '#ef4444'
                });
            @endif

            @if(session('warning'))
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan!',
                    text: "{{ session('warning') }}",
                    background: '#1e293b',
                    color: '#f8fafc',
                    confirmButtonColor: '#f59e0b'
                });
            @endif
        });
    </script>
    @yield('scripts')
</body>
</html>
