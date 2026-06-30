@extends('layouts.app')

@section('title', 'Daftar Rapat')

@section('content')
<div class="container-fluid p-0">

    <div class="row mb-4 align-items-center">
        <div class="col-sm-8">
            <h3 class="text-heading font-weight-bold">Daftar Rapat MPM</h3>
            <p class="text-softer mb-0">Lihat agenda, status absensi, dan notula rapat yang diselenggarakan.</p>
        </div>
    </div>

    <!-- Table Card -->
    <div class="glass-card">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h5 class="card-title-premium mb-0">
                <i class="bi bi-calendar-event-fill"></i> Riwayat & Jadwal Rapat
            </h5>
            
            <form action="{{ route('anggota.rapat.index') }}" method="GET" class="d-flex">
                <div class="input-group">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari rapat..." value="{{ $search }}" style="width: 200px;">
                    <button class="btn btn-premium btn-sm" type="submit">Cari</button>
                    @if($search)
                        <a href="{{ route('anggota.rapat.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x"></i></a>
                    @endif
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 60px;">No</th>
                        <th>Judul Agenda</th>
                        <th>Tanggal Rapat</th>
                        <th>Waktu</th>
                        <th>Lokasi</th>
                        <th class="text-center">Notula</th>
                        <th class="text-center" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($agendas as $index => $agenda)
                        <tr>
                            <td class="text-center">{{ $agendas->firstItem() + $index }}</td>
                            <td>
                                <span class="text-heading font-weight-semibold d-block">{{ $agenda->judul_agenda }}</span>
                                <small class="text-softer d-inline-block text-truncate" style="max-width: 300px;">{{ $agenda->deskripsi ?? 'Tidak ada deskripsi.' }}</small>
                            </td>
                            <td>{{ date('d F Y', strtotime($agenda->tanggal_rapat)) }}</td>
                            <td>{{ substr($agenda->waktu_mulai, 0, 5) }} - {{ substr($agenda->waktu_selesai, 0, 5) }} WIB</td>
                            <td>
                                <span class="text-softer"><i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $agenda->lokasi }}</span>
                            </td>
                            <td class="text-center">
                                @if($agenda->notula || $agenda->notula_file)
                                    <span class="badge bg-success bg-opacity-15 text-success border border-success border-opacity-30">Tersedia</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-15 text-softer border border-secondary border-opacity-30">Belum Ada</span>
                                @endif
                            <td class="text-center">
                                <a href="{{ route('anggota.rapat.show', $agenda->id_agenda) }}" class="btn btn-sm btn-premium" title="Lihat Detail Rapat">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-softer">Tidak ada data agenda rapat ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap">
            <div class="text-softer small">
                Menampilkan {{ $agendas->firstItem() ?? 0 }} sampai {{ $agendas->lastItem() ?? 0 }} dari {{ $agendas->total() }} agenda
            </div>
            <div>
                {{ $agendas->appends(['search' => $search])->links('pagination::bootstrap-5') }}
            </div>
        </div>

    </div>

</div>
@endsection
