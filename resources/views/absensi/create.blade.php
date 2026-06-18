@extends('layouts.app')

@section('title', 'Isi Absensi Rapat')

@section('content')
<div class="container-fluid p-0">

    <div class="row mb-4">
        <div class="col-12">
            <h3 class="text-white font-weight-bold">Pengisian Absensi Rapat</h3>
            <p class="text-secondary">Silakan isi kehadiran Anda untuk agenda rapat hari ini atau mendatang.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            
            @if(count($agendas) - count($filledAgendaIds) <= 0)
                <div class="glass-card text-center py-5">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 text-success d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                        <i class="bi bi-calendar-check-fill" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="text-white font-weight-bold">Semua Absensi Terisi!</h5>
                    <p class="text-secondary mb-0">Tidak ada agenda rapat aktif saat ini yang perlu Anda isi absensinya.</p>
                </div>
            @else
                <div class="glass-card">
                    <h5 class="card-title-premium">
                        <i class="bi bi-pencil-square"></i> Formulir Absensi Anggota
                    </h5>

                    <form action="{{ route('anggota.absensi.store') }}" method="POST" enctype="multipart/form-data" class="mt-4" id="absensi-form">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="id_agenda" class="form-label text-secondary">Pilih Agenda Rapat <span class="text-danger">*</span></label>
                            <select name="id_agenda" id="id_agenda" class="form-select @error('id_agenda') is-invalid @enderror" required>
                                <option value="" disabled selected>-- Pilih Agenda Rapat --</option>
                                @foreach($agendas as $agenda)
                                    @if(!in_array($agenda->id_agenda, $filledAgendaIds))
                                        <option value="{{ $agenda->id_agenda }}" {{ old('id_agenda') == $agenda->id_agenda ? 'selected' : '' }}>
                                            {{ $agenda->judul_agenda }} ({{ date('d M Y', strtotime($agenda->tanggal_rapat)) }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('id_agenda')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-secondary d-block mb-3">Status Kehadiran <span class="text-danger">*</span></label>
                            <div class="row g-3">
                                @foreach($statuses as $status)
                                    <div class="col-sm-6">
                                        <div class="border border-secondary border-opacity-15 rounded-3 p-3 bg-secondary bg-opacity-10 h-100 d-flex align-items-center">
                                            <input class="form-check-input me-3 status-radio" type="radio" name="id_status" id="status_{{ $status->id_status }}" value="{{ $status->id_status }}" {{ old('id_status') == $status->id_status ? 'checked' : '' }} required>
                                            <label class="form-check-label text-white cursor-pointer w-100" for="status_{{ $status->id_status }}">
                                                <span class="d-block font-weight-bold mb-1">{{ $status->nama_status }}</span>
                                                <small class="text-secondary">Bobot: {{ (float)$status->bobot_kehadiran }}</small>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('id_status')
                                <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Conditional Permission Fields (Izin) -->
                        <div id="izin-fields" class="d-none border border-secondary border-opacity-20 rounded-4 p-4 mb-4 bg-secondary bg-opacity-5">
                            <h6 class="text-warning font-weight-semibold mb-3">
                                <i class="bi bi-info-circle-fill me-1"></i> Rincian Izin Rapat
                            </h6>
                            
                            <div class="mb-3">
                                <label for="alasan" class="form-label text-secondary">Alasan Izin <span class="text-danger">*</span></label>
                                <textarea name="alasan" id="alasan" rows="3" class="form-control @error('alasan') is-invalid @enderror" placeholder="Tulis alasan tidak dapat menghadiri rapat secara lengkap...">{{ old('alasan') }}</textarea>
                                @error('alasan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label for="bukti_file" class="form-label text-secondary">Upload Bukti (Opsional)</label>
                                <input type="file" name="bukti_file" id="bukti_file" class="form-control @error('bukti_file') is-invalid @enderror">
                                <small class="text-muted mt-1 d-block">Format file: JPG, JPEG, PNG, atau PDF. Maksimal 2MB.</small>
                                @error('bukti_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('anggota.dashboard') }}" class="btn btn-outline-premium">Batal</a>
                            <button type="submit" class="btn btn-premium">Kirim Absensi</button>
                        </div>

                    </form>
                </div>
            @endif

        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        function toggleIzinFields() {
            // id_status = 4 is "Izin"
            if ($('#status_4').is(':checked')) {
                $('#izin-fields').removeClass('d-none');
                $('#alasan').prop('required', true);
            } else {
                $('#izin-fields').addClass('d-none');
                $('#alasan').prop('required', false);
            }
        }

        $('.status-radio').on('change', toggleIzinFields);
        
        // Run on load to support validation error backfills
        toggleIzinFields();
    });
</script>
@endsection
