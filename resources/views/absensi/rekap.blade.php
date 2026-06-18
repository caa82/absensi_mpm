@extends('layouts.app')

@section('title', 'Rekap Kehadiran Anggota')

@section('content')
<div class="container-fluid p-0">

    <div class="row mb-4">
        <div class="col-12">
            <h3 class="text-white font-weight-bold">Rekapitulasi Kehadiran Anggota</h3>
            <p class="text-secondary">Pantau persentase dan rangkuman kehadiran seluruh anggota MPM Politeknik Astra.</p>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="glass-card mb-4">
        <h5 class="card-title-premium">
            <i class="bi bi-filter-square-fill"></i> Filter Periode Rekap
        </h5>

        <form action="{{ route('sekretaris.absensi.rekap') }}" method="GET" class="row align-items-end g-3 mt-2">
            <div class="col-md-4">
                <label for="month" class="form-label text-secondary">Bulan</label>
                <select name="month" id="month" class="form-select form-select-sm">
                    <option value="">-- Semua Bulan --</option>
                    @foreach($months as $num => $name)
                        <option value="{{ $num }}" {{ $selectedMonth == $num ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-md-4">
                <label for="year" class="form-label text-secondary">Tahun</label>
                <select name="year" id="year" class="form-select form-select-sm">
                    @for($y = date('Y') - 2; $y <= date('Y') + 2; $y++)
                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-premium btn-sm w-100">
                    <i class="bi bi-search me-1"></i> Terapkan Filter
                </button>
                <a href="{{ route('sekretaris.absensi.rekap') }}" class="btn btn-outline-secondary btn-sm w-100">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Data Table Card -->
    <div class="glass-card">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h5 class="card-title-premium mb-1">
                    <i class="bi bi-table"></i> Laporan Kehadiran
                </h5>
                <small class="text-secondary">
                    Periode: 
                    <strong>
                        @if($selectedMonth)
                            {{ $months[(int)$selectedMonth] }} {{ $selectedYear }}
                        @else
                            Tahun {{ $selectedYear }}
                        @endif
                    </strong> 
                    | Jumlah Agenda Rapat: <strong>{{ $total_agendas }}</strong>
                </small>
            </div>

            <div>
                <a href="{{ route('sekretaris.absensi.export', ['month' => $selectedMonth, 'year' => $selectedYear]) }}" class="btn btn-success btn-sm font-weight-bold d-flex align-items-center">
                    <i class="bi bi-file-earmark-excel-fill me-1"></i> Export ke Excel
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="rekap-table">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">No</th>
                        <th>NIM</th>
                        <th>Nama Anggota</th>
                        <th>Jabatan</th>
                        <th class="text-center">Hadir (1.0)</th>
                        <th class="text-center">Izin (0.75)</th>
                        <th class="text-center">Shift 2 Sebagian (0.5)</th>
                        <th class="text-center">Shift 2 Absen (0.0)</th>
                        <th class="text-center">Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $m)
                        <tr>
                            <td class="text-center text-white">{{ $m['no'] }}</td>
                            <td><code class="text-info">{{ $m['nim'] }}</code></td>
                            <td class="text-white font-weight-semibold">{{ $m['nama'] }}</td>
                            <td>{{ $m['jabatan'] }}</td>
                            <td class="text-center">
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2">{{ $m['hadir'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2">{{ $m['izin'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-2">{{ $m['shift_2_hadir'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2">{{ $m['shift_2_absen'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="font-weight-bold text-white fs-6">{{ $m['percentage'] }}%</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-secondary py-4">Tidak ada data rekapitulasi kehadiran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#rekap-table').DataTable({
            language: {
                search: "Cari cepat:",
                lengthMenu: "Tampilkan _MENU_ baris",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ anggota",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Lanjut",
                    previous: "Kembali"
                }
            },
            pageLength: 10,
            columnDefs: [
                { orderable: false, targets: [0, 4, 5, 6, 7] }
            ]
        });
    });
</script>
@endsection
