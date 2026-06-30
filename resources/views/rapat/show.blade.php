@extends('layouts.app')

@section('title', 'Detail Rapat')

@section('content')
<div class="container-fluid p-0">

    <div class="row mb-4 align-items-center">
        <div class="col-sm-8">
            <h3 class="text-heading font-weight-bold">Detail Rapat MPM</h3>
            <p class="text-softer mb-0">Informasi detail agenda rapat, status absensi Anda, dan notula rapat.</p>
        </div>
        <div class="col-sm-4 text-sm-end mt-3 mt-sm-0">
            <a href="{{ route('anggota.rapat.index') }}" class="btn btn-outline-premium">
                <i class="bi bi-chevron-left me-1"></i> Kembali ke Daftar
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Rapat Info -->
        <div class="col-lg-7">
            <div class="glass-card mb-4 h-100">
                <h5 class="card-title-premium mb-4">
                    <i class="bi bi-info-circle-fill text-primary"></i> Informasi Rapat
                </h5>
                
                <div class="row g-3">
                    <div class="col-12 mb-2">
                        <strong class="text-heading d-block mb-1">Judul Agenda</strong>
                        <span class="text-soft fs-5 font-weight-semibold">{{ $agenda->judul_agenda }}</span>
                    </div>
                    <div class="col-12 mb-2">
                        <strong class="text-heading d-block mb-1">Deskripsi</strong>
                        <div class="text-soft p-3 rounded bg-secondary bg-opacity-10 border border-secondary border-opacity-10" style="white-space: pre-wrap;">{{ $agenda->deskripsi ?? 'Tidak ada deskripsi.' }}</div>
                    </div>
                    <div class="col-sm-6 mb-2">
                        <strong class="text-heading d-block mb-1">Tanggal Rapat</strong>
                        <span class="text-soft"><i class="bi bi-calendar-event me-2 text-primary"></i>{{ date('d F Y', strtotime($agenda->tanggal_rapat)) }}</span>
                    </div>
                    <div class="col-sm-6 mb-2">
                        <strong class="text-heading d-block mb-1">Waktu Rapat</strong>
                        <span class="text-soft"><i class="bi bi-clock me-2 text-primary"></i>{{ substr($agenda->waktu_mulai, 0, 5) }} - {{ substr($agenda->waktu_selesai, 0, 5) }} WIB</span>
                    </div>
                    <div class="col-sm-6 mb-2">
                        <strong class="text-heading d-block mb-1">Lokasi Rapat</strong>
                        <span class="text-soft"><i class="bi bi-geo-alt-fill me-2 text-danger"></i>{{ $agenda->lokasi }}</span>
                    </div>
                    <div class="col-sm-6 mb-2">
                        <strong class="text-heading d-block mb-1">Dibuat Oleh</strong>
                        <span class="text-soft"><i class="bi bi-person-fill me-2 text-info"></i>{{ $agenda->creator->username ?? 'Sekretaris' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Absensi Anda -->
        <div class="col-lg-5">
            <div class="glass-card mb-4 h-100">
                <h5 class="card-title-premium mb-4">
                    <i class="bi bi-person-check-fill text-success"></i> Status Kehadiran Anda
                </h5>

                @if($myAbsensi)
                    <div class="p-3 rounded-4 bg-secondary bg-opacity-10 border border-secondary border-opacity-15">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-softer small">Status Kehadiran:</span>
                            @if($myAbsensi->id_status == 1)
                                <span class="badge badge-hadir">Hadir</span>
                            @elseif($myAbsensi->id_status == 2)
                                <span class="badge badge-shift2-hadir">Shift 2 Sebagian</span>
                            @elseif($myAbsensi->id_status == 3)
                                <span class="badge badge-shift2-absen">Shift 2 Absen</span>
                            @elseif($myAbsensi->id_status == 4)
                                <span class="badge badge-izin">Izin</span>
                            @elseif($myAbsensi->id_status == 5)
                                <span class="badge badge-sakit">Sakit</span>
                            @endif
                        </div>

                        <div class="mb-3">
                            <span class="text-softer small d-block">Waktu Absen:</span>
                            <span class="text-heading font-weight-semibold">{{ date('d M Y, H:i', strtotime($myAbsensi->waktu_absen)) }} WIB</span>
                        </div>

                        @if($myAbsensi->keterangan)
                            <div class="mb-3">
                                <span class="text-softer small d-block">Keterangan:</span>
                                <span class="text-soft italic">"{{ $myAbsensi->keterangan }}"</span>
                            </div>
                        @endif

                        {{-- Bukti Foto Kehadiran / Surat Sakit --}}
                        @if(($myAbsensi->id_status == 1 || $myAbsensi->id_status == 5) && $myAbsensi->bukti_foto)
                            <div class="mb-3">
                                <span class="text-softer small d-block mb-1">{{ $myAbsensi->id_status == 1 ? 'Foto Kehadiran:' : 'Surat Sakit:' }}</span>
                                <a href="{{ $myAbsensi->bukti_foto }}" target="_blank">
                                    <img src="{{ $myAbsensi->bukti_foto }}" alt="Bukti Foto" class="rounded-3 border border-secondary border-opacity-20 img-fluid" style="max-height: 180px; width: 100%; object-fit: cover;">
                                </a>
                            </div>
                        @endif

                        {{-- Bukti Izin --}}
                        @if($myAbsensi->id_status == 4 && $myAbsensi->izin)
                            <div class="mb-3">
                                <span class="text-softer small d-block">Alasan Izin:</span>
                                <span class="text-soft italic">"{{ $myAbsensi->izin->alasan }}"</span>
                                
                                <div class="mt-2">
                                    <span class="text-softer small me-2">Verifikasi:</span>
                                    @if($myAbsensi->izin->status_verifikasi == 'Pending')
                                        <span class="badge bg-warning bg-opacity-15 text-warning border border-warning border-opacity-30">Pending</span>
                                    @elseif($myAbsensi->izin->status_verifikasi == 'Disetujui')
                                        <span class="badge bg-success bg-opacity-15 text-success border border-success border-opacity-30">Disetujui</span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-15 text-danger border border-danger border-opacity-30">Ditolak</span>
                                    @endif
                                </div>

                                @if($myAbsensi->izin->bukti_file)
                                    <div class="mt-3">
                                        <a href="{{ $myAbsensi->izin->bukti_file }}" download class="btn btn-sm btn-outline-info w-100 font-weight-semibold">
                                            <i class="bi bi-file-earmark-arrow-down-fill me-1"></i> Unduh Lampiran Izin
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @else
                    <div class="p-3 rounded-4 bg-danger bg-opacity-10 border border-danger border-opacity-15 text-center">
                        <i class="bi bi-exclamation-triangle-fill text-danger fs-3 mb-2 d-block"></i>
                        <h6 class="text-heading font-weight-bold">Belum Melakukan Absensi</h6>
                        <p class="text-softer small mb-3">Anda belum mengisi kehadiran untuk agenda rapat ini.</p>
                        
                        @php
                            $now = \Carbon\Carbon::now();
                            $waktuMulai = \Carbon\Carbon::parse($agenda->tanggal_rapat . ' ' . $agenda->waktu_mulai);
                            $waktuSelesai = \Carbon\Carbon::parse($agenda->tanggal_rapat . ' ' . $agenda->waktu_selesai);
                            $windowStart = $waktuMulai->copy()->subHours(24);
                            $windowEnd = $waktuSelesai;
                            $isOpen = $now->between($windowStart, $windowEnd);
                        @endphp

                        @if($isOpen)
                            <a href="{{ route('anggota.absensi.create') }}" class="btn btn-premium w-100 btn-sm">
                                <i class="bi bi-pencil-square me-1"></i> Isi Absensi Sekarang
                            </a>
                        @else
                            <div class="text-muted small border-top border-secondary border-opacity-10 pt-2 mt-2">
                                <i class="bi bi-info-circle me-1"></i>
                                Absensi dibuka mulai 24 jam sebelum rapat dimulai hingga rapat selesai.
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Notula Rapat Card -->
    <div class="glass-card mt-4">
        <h5 class="card-title-premium mb-3">
            <i class="bi bi-journal-text text-info"></i> Notula Hasil Rapat
        </h5>

        @if($agenda->notula || $agenda->notula_file)
            <div class="p-4 rounded-4 bg-secondary bg-opacity-10 border border-secondary border-opacity-15">
                @if($agenda->notula)
                    <div class="text-soft mb-3" style="white-space: pre-wrap; font-family: inherit; font-size: 1rem; line-height: 1.6;">{!! nl2br(e($agenda->notula)) !!}</div>
                @endif

                @if($agenda->notula_file)
                    @if($agenda->notula)
                        <hr class="border-secondary border-opacity-20 my-3">
                    @endif
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-file-earmark-arrow-down-fill text-info fs-3 me-3"></i>
                            <div>
                                <span class="text-heading d-block font-weight-semibold" style="color: #ffffff !important;">Berkas Notula Rapat</span>
                                <small class="text-softer" style="color: #cbd5e1 !important;">{{ basename($agenda->notula_file) }}</small>
                            </div>
                        </div>
                        <a href="{{ $agenda->notula_file }}" download class="btn btn-premium btn-sm">
                            <i class="bi bi-download me-1"></i> Unduh / Lihat Berkas
                        </a>
                    </div>
                @endif
            </div>
        @else
            <div class="text-center py-5 text-softer">
                <i class="bi bi-journal-x fs-1 mb-3 text-secondary" style="opacity: 0.5;"></i>
                <p class="mb-0">Notula rapat belum diunggah oleh Sekretaris.</p>
                <small class="text-muted">Hubungi sekretaris jika rapat telah selesai dilaksanakan untuk mendapatkan notulensi rapat.</small>
            </div>
        @endif
    </div>

</div>
@endsection
