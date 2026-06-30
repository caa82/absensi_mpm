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
            --primary-bg: #f8fafc;
            --sidebar-bg: #ffffff;
            --card-bg: #ffffff;
            --border-color: #e2e8f0;
            --text-primary: #334155;
            --text-secondary: #64748b;
            --text-heading: #0f172a;
            --accent-blue: #8b5cf6;
            --accent-gradient: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);
            --font-family: 'Plus Jakarta Sans', sans-serif;
            --card-shadow: 0 10px 25px -5px rgba(139, 92, 246, 0.04), 0 8px 10px -6px rgba(139, 92, 246, 0.04);
            --sidebar-shadow: 4px 0 24px rgba(139, 92, 246, 0.03);
            --header-shadow: 0 4px 20px rgba(139, 92, 246, 0.02);
        }

        body {
            font-family: var(--font-family);
            background-color: var(--primary-bg);
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
            transition: background-color 0.3s ease;
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
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--sidebar-shadow);
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
            color: var(--text-heading);
        }

        .sidebar-menu {
            list-style: none;
            padding: 1rem 0;
            margin: 0;
        }

        .menu-header {
            padding: 0.75rem 1.5rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-secondary);
            font-weight: 700;
        }

        .menu-item {
            margin: 0.25rem 1rem;
        }

        .menu-link {
            display: flex;
            align-items: center;
            padding: 0.8rem 1rem;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.25s ease;
        }

        .menu-link:hover {
            background-color: rgba(139, 92, 246, 0.05);
            color: var(--accent-blue);
            transform: translateX(4px);
        }

        .menu-link i {
            font-size: 1.25rem;
            margin-right: 12px;
        }

        .menu-link.active {
            background: var(--accent-gradient);
            color: #ffffff !important;
            box-shadow: 0 8px 16px rgba(139, 92, 246, 0.25);
        }

        .menu-link.active:hover {
            transform: none;
        }

        /* Top Navbar Styling */
        .top-navbar {
            height: 70px;
            background-color: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            position: fixed;
            top: 0;
            right: 0;
            left: 260px;
            z-index: 99;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.3s ease;
            box-shadow: var(--header-shadow);
        }

        /* Layout Content spacing */
        .main-content {
            margin-left: 260px;
            padding: 90px 2rem 2rem;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* Elevated modern card */
        .glass-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: var(--card-shadow);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px -5px rgba(139, 92, 246, 0.08), 0 10px 20px -6px rgba(139, 92, 246, 0.08);
        }

        .card-title-premium {
            font-weight: 700;
            font-size: 1.15rem;
            color: var(--text-heading);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-premium {
            background: var(--accent-gradient);
            color: white !important;
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.25s ease;
            box-shadow: 0 4px 12px rgba(139, 92, 246, 0.2);
        }

        .btn-premium:hover {
            box-shadow: 0 6px 18px rgba(139, 92, 246, 0.35);
            transform: translateY(-1px);
        }

        .btn-outline-premium {
            background: transparent;
            border: 1px solid var(--accent-blue);
            color: var(--accent-blue);
            padding: 0.6rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.25s ease;
        }

        .btn-outline-premium:hover {
            background: var(--accent-gradient);
            border-color: transparent;
            color: white !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(139, 92, 246, 0.2);
        }

        /* DataTables Custom Styling */
        .dataTables_wrapper {
            color: var(--text-secondary);
        }
        
        .table {
            background-color: #ffffff !important;
            color: var(--text-primary) !important;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid var(--border-color) !important;
        }

        .table th {
            border-bottom: 2px solid var(--border-color) !important;
            color: var(--text-heading) !important;
            background-color: #f8fafc !important;
            font-weight: 700;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 1rem 0.75rem !important;
        }

        .table td {
            border-bottom: 1px solid var(--border-color) !important;
            color: var(--text-primary) !important;
            background-color: #ffffff !important;
            vertical-align: middle;
            padding: 1rem 0.75rem !important;
        }

        .table-hover tbody tr:hover td {
            background-color: #f5f3ff !important;
            transition: background-color 0.2s ease;
        }

        /* Enforce slate colors for elements inside the table */
        .table td span:not(.badge), 
        .table td small, 
        .table td i:not(.bi-geo-alt-fill), 
        .table td .text-soft, 
        .table td .text-softer {
            color: var(--text-secondary) !important;
        }

        .table td .text-heading, .table td strong {
            color: var(--text-heading) !important;
        }

        .table td .btn i {
            color: inherit !important;
        }

        .page-link {
            background-color: #ffffff;
            border-color: var(--border-color);
            color: var(--text-secondary);
            border-radius: 8px;
            margin: 0 2px;
        }

        .page-link:hover {
            background-color: rgba(139, 92, 246, 0.05);
            color: var(--accent-blue);
            border-color: var(--border-color);
        }

        .active > .page-link {
            background: var(--accent-gradient);
            border-color: transparent;
            color: #ffffff;
        }

        /* Badges */
        .badge-hadir {
            background-color: rgba(16, 185, 129, 0.1) !important;
            color: #10b981 !important;
            border: 1px solid rgba(16, 185, 129, 0.2);
            padding: 0.5em 0.8em;
            border-radius: 8px;
            font-weight: 600;
        }

        .badge-izin {
            background-color: rgba(245, 158, 11, 0.1) !important;
            color: #f59e0b !important;
            border: 1px solid rgba(245, 158, 11, 0.2);
            padding: 0.5em 0.8em;
            border-radius: 8px;
            font-weight: 600;
        }

        .badge-sakit {
            background-color: rgba(236, 72, 153, 0.1) !important;
            color: #ec4899 !important;
            border: 1px solid rgba(236, 72, 153, 0.2);
            padding: 0.5em 0.8em;
            border-radius: 8px;
            font-weight: 600;
        }

        .badge-shift2-hadir {
            background-color: rgba(99, 102, 241, 0.1) !important;
            color: #6366f1 !important;
            border: 1px solid rgba(99, 102, 241, 0.2);
            padding: 0.5em 0.8em;
            border-radius: 8px;
            font-weight: 600;
        }

        .badge-shift2-absen {
            background-color: rgba(239, 68, 68, 0.1) !important;
            color: #ef4444 !important;
            border: 1px solid rgba(239, 68, 68, 0.2);
            padding: 0.5em 0.8em;
            border-radius: 8px;
            font-weight: 600;
        }

        /* Softer text utilities */
        .text-soft { color: var(--text-primary) !important; }
        .text-softer { color: var(--text-secondary) !important; }
        .text-heading { color: var(--text-heading) !important; }

        /* Form controls light theme */
        .form-control, .form-select {
            background-color: #ffffff;
            border-color: var(--border-color);
            color: var(--text-primary);
            border-radius: 12px;
            padding: 0.6rem 1rem;
            transition: all 0.2s ease;
        }
        .form-control:focus, .form-select:focus {
            background-color: #ffffff;
            border-color: var(--accent-blue);
            color: var(--text-primary);
            box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.15);
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
                <li class="menu-item">
                    <a href="{{ route('anggota.rapat.index') }}" class="menu-link {{ Route::is('anggota.rapat.*') ? 'active' : '' }}">
                        <i class="bi bi-journal-text"></i>
                        <span>Daftar Rapat</span>
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
            <h5 class="mb-0 font-weight-bold text-heading">Sistem Absensi Anggota MPM</h5>
            <small class="text-secondary">Politeknik Astra - Cikarang</small>
        </div>
        
        <div class="d-flex align-items-center">
            <div class="text-end me-3 d-none d-sm-block">
                <h6 class="mb-0 text-heading font-weight-semibold">{{ Auth::user()->anggota->nama_anggota ?? Auth::user()->username }}</h6>
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
                    background: '#ffffff',
                    color: '#1e293b',
                    confirmButtonColor: '#8b5cf6'
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: "{{ session('error') }}",
                    background: '#ffffff',
                    color: '#1e293b',
                    confirmButtonColor: '#ef4444'
                });
            @endif

            @if($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal!',
                    html: '{!! implode("<br>", array_map("e", $errors->all())) !!}',
                    background: '#ffffff',
                    color: '#1e293b',
                    confirmButtonColor: '#ef4444'
                });
            @endif

            @if(session('warning'))
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan!',
                    text: "{{ session('warning') }}",
                    background: '#ffffff',
                    color: '#1e293b',
                    confirmButtonColor: '#f59e0b'
                });
            @endif
        });
    </script>
    @yield('scripts')
</body>
</html>
