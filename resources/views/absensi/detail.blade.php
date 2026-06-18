@extends('layouts.app')

@section('title', 'Detail Absensi Rapat')

@section('content')
<div class="container-fluid p-0">

    <div class="row mb-4 align-items-center">
        <div class="col-sm-8">
            <h3 class="text-white font-weight-bold">Detail Absensi Rapat</h3>
            <p class="text-secondary mb-0">Daftar kehadiran dan berkas perizinan rapat pengurus.</p>
        </div>
        <div class="col-sm-4 text-sm-end mt-3 mt-sm-0">
            <a href="{{ route('sekretaris.agenda.index') }}" class="btn btn-outline-premium">
                <i class="bi bi-chevron-left me-1"></i> Kembali ke Agenda
            </a>
        </div>
    </div>

    <!-- Agenda Info Panel -->
    <div class="glass-card mb-4">
        <h5 class="card-title-premium text-white">
            <i class="bi bi-info-circle-fill text-primary"></i> Informasi Rapat
        </h5>
        
        <div class="row g-3 mt-2 text-secondary">
            <div class="col-md-6">
                <div class="mb-2"><strong class="text-white">Judul Agenda:</strong> {{ $agenda->judul_agenda }}</div>
                <div class="mb-2"><strong class="text-white">Deskripsi:</strong> {{ $agenda->deskripsi ?? '-' }}</div>
                <div class="mb-2"><strong class="text-white">Lokasi Rapat:</strong> {{ $agenda->lokasi }}</div>
            </div>
            <div class="col-md-6">
                <div class="mb-2"><strong class="text-white">Tanggal Rapat:</strong> {{ date('d F Y', strtotime($agenda->tanggal_rapat)) }}</div>
                <div class="mb-2"><strong class="text-white">Waktu Rapat:</strong> {{ substr($agenda->waktu_mulai, 0, 5) }} - {{ substr($agenda->waktu_selesai, 0, 5) }} WIB</div>
                <div class="mb-2"><strong class="text-white">Dibuat Oleh:</strong> {{ $agenda->creator->username }}</div>
            </div>
        </div>
    </div>

    <!-- Attendance List Table Card -->
    <div class="glass-card">
        <h5 class="card-title-premium mb-4 text-white">
            <i class="bi bi-people-fill text-success"></i> Anggota yang Sudah Absen
        </h5>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="detail-table">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">No</th>
                        <th>NIM</th>
                        <th>Nama Anggota</th>
                        <th>Waktu Absen</th>
                        <th>Status</th>
                        <th>Keterangan / Berkas Izin</th>
                        <th class="text-center" style="width: 200px;">Verifikasi Izin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensi as $idx => $absen)
                        <tr>
                            <td class="text-center text-white">{{ $idx + 1 }}</td>
                            <td><code class="text-info">{{ $absen->anggota->nim }}</code></td>
                            <td class="text-white font-weight-semibold">{{ $absen->anggota->nama_anggota }}</td>
                            <td>{{ date('d-m-Y H:i', strtotime($absen->waktu_absen)) }} WIB</td>
                            <td>
                                @if($absen->id_status == 1)
                                    <span class="badge badge-hadir">Hadir</span>
                                @elseif($absen->id_status == 2)
                                    <span class="badge badge-shift2-hadir">Shift 2 Sebagian</span>
                                @elseif($absen->id_status == 3)
                                    <span class="badge badge-shift2-absen">Shift 2 Absen</span>
                                @elseif($absen->id_status == 4)
                                    <span class="badge badge-izin">Izin</span>
                                @endif
                            </td>
                            <td>
                                @if($absen->id_status == 4 && $absen->izin)
                                    <div class="small">
                                        <div class="text-white font-weight-semibold">Alasan:</div>
                                        <div class="text-secondary italic">"{{ $absen->izin->alasan }}"</div>
                                        
                                        @if($absen->izin->bukti_file)
                                            <div class="mt-1">
                                                <a href="{{ $absen->izin->bukti_file }}" target="_blank" class="btn btn-sm btn-outline-info py-0 px-2 font-weight-semibold small mt-1">
                                                    <i class="bi bi-file-earmark-arrow-down-fill"></i> Lihat Bukti
                                                </a>
                                            </div>
                                        @else
                                            <small class="text-muted italic d-block mt-1">Tidak ada bukti file diupload.</small>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($absen->id_status == 4 && $absen->izin)
                                    @if($absen->izin->status_verifikasi == 'Pending')
                                        <form action="{{ route('sekretaris.absensi.verify-izin', $absen->izin->id_izin) }}" method="POST" class="d-inline verify-form">
                                            @csrf
                                            <input type="hidden" name="status" value="Disetujui">
                                            <button type="submit" class="btn btn-sm btn-success bg-opacity-20 text-success border border-success border-opacity-30 btn-verify me-1">
                                                Setuju
                                            </button>
                                        </form>
                                        <form action="{{ route('sekretaris.absensi.verify-izin', $absen->izin->id_izin) }}" method="POST" class="d-inline verify-form">
                                            @csrf
                                            <input type="hidden" name="status" value="Ditolak">
                                            <button type="submit" class="btn btn-sm btn-danger bg-opacity-20 text-danger border border-danger border-opacity-30 btn-verify">
                                                Tolak
                                            </button>
                                        </form>
                                    @else
                                        @if($absen->izin->status_verifikasi == 'Disetujui')
                                            <span class="badge bg-success bg-opacity-15 text-success border border-success border-opacity-30 px-3 py-2">
                                                <i class="bi bi-check-circle-fill me-1"></i> Disetujui
                                            </span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-15 text-danger border border-danger border-opacity-30 px-3 py-2">
                                                <i class="bi bi-x-circle-fill me-1"></i> Ditolak
                                            </span>
                                        @endif
                                    @endif
                                @else
                                    <span class="text-muted small">Tidak Perlu</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-secondary">Belum ada anggota yang mengisi absensi untuk rapat ini.</td>
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
        $('#detail-table').DataTable({
            language: {
                search: "Cari cepat:",
                lengthMenu: "Tampilkan _MENU_ baris",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ pengisian",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Lanjut",
                    previous: "Kembali"
                }
            },
            pageLength: 10
        });

        // Verify action popup confirmation
        $('.btn-verify').on('click', function(e) {
            e.preventDefault();
            const form = $(this).closest('.verify-form');
            const action = form.find('input[name="status"]').val();
            
            Swal.fire({
                title: 'Konfirmasi Verifikasi',
                text: "Apakah Anda yakin ingin " + (action === 'Disetujui' ? 'menyetujui' : 'menolak') + " izin dari anggota ini?",
                icon: 'question',
                showCancelButton: true,
                background: '#1e293b',
                color: '#f8fafc',
                confirmButtonColor: action === 'Disetujui' ? '#10b981' : '#ef4444',
                cancelButtonColor: '#475569',
                confirmButtonText: 'Ya, Konfirmasi',
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
