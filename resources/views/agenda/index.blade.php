@extends('layouts.app')

@section('title', 'Daftar Agenda Rapat')

@section('content')
<div class="container-fluid p-0">

    <div class="row mb-4 align-items-center">
        <div class="col-sm-6">
            <h3 class="text-heading font-weight-bold">Kelola Agenda Rapat</h3>
            <p class="text-softer mb-0">Buat dan atur jadwal rapat pengurus serta pantau absensinya.</p>
        </div>
        <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <a href="{{ route('sekretaris.agenda.create') }}" class="btn btn-premium">
                <i class="bi bi-calendar-plus me-1"></i> Buat Agenda Baru
            </a>
        </div>
    </div>

    <!-- Table Card -->
    <div class="glass-card">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h5 class="card-title-premium mb-0">
                <i class="bi bi-list-task"></i> Daftar Agenda Rapat
            </h5>
            
            <form action="{{ route('sekretaris.agenda.index') }}" method="GET" class="d-flex">
                <div class="input-group">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari agenda..." value="{{ $search }}" style="width: 200px;">
                    <button class="btn btn-premium btn-sm" type="submit">Cari</button>
                    @if($search)
                        <a href="{{ route('sekretaris.agenda.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x"></i></a>
                    @endif
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="agenda-table">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 60px;">No</th>
                        <th>Judul Agenda</th>
                        <th>Tanggal Rapat</th>
                        <th>Waktu</th>
                        <th>Lokasi</th>
                        <th class="text-center" style="width: 300px;">Aksi</th>
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
                                <a href="{{ route('sekretaris.absensi.detail', $agenda->id_agenda) }}" class="btn btn-sm btn-info text-white me-1" title="Lihat Detail Absensi">
                                    <i class="bi bi-people-fill"></i>
                                </a>

                                @php
                                    $today = date('Y-m-d');
                                    $isPast = $agenda->tanggal_rapat < $today;
                                @endphp

                                {{-- Notula button: only for past/today agendas --}}
                                @if($isPast || $agenda->tanggal_rapat == $today)
                                    <a href="{{ route('sekretaris.agenda.notula', $agenda->id_agenda) }}" class="btn btn-sm text-white me-1 {{ $agenda->notula || $agenda->notula_file ? 'btn-success' : 'btn-secondary' }}" title="{{ $agenda->notula || $agenda->notula_file ? 'Notula Rapat (Tersedia)' : 'Unggah/Tulis Notula Rapat' }}">
                                        <i class="bi bi-journal-text"></i>
                                    </a>
                                @endif

                                @if(!$isPast)
                                    <a href="{{ route('sekretaris.agenda.edit', $agenda->id_agenda) }}" class="btn btn-sm btn-warning text-white me-1" title="Edit Agenda">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                @endif

                                <form action="{{ route('sekretaris.agenda.destroy', $agenda->id_agenda) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger text-white btn-delete" title="Hapus Agenda">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-softer">Tidak ada data agenda rapat ditemukan.</td>
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

@section('scripts')
<script>
    $(document).ready(function() {
        // Confirmation before deleting
        $('.btn-delete').on('click', function(e) {
            e.preventDefault();
            const form = $(this).closest('.delete-form');
            
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data agenda dan seluruh riwayat absensinya akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                background: '#1e293b',
                color: '#f8fafc',
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#475569',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
