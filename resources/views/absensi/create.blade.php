@extends('layouts.app')

@section('title', 'Isi Absensi Rapat')

@section('content')
<div class="container-fluid p-0">

    <div class="row mb-4">
        <div class="col-12">
            <h3 class="text-heading font-weight-bold">Pengisian Absensi Rapat</h3>
            <p class="text-softer">Absensi dibuka mulai 24 jam sebelum rapat dimulai hingga rapat selesai. Silakan isi kehadiran Anda.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            
            @if($agendas->isEmpty())
                <div class="glass-card text-center py-5">
                    <div class="rounded-circle bg-info bg-opacity-10 p-3 text-info d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                        <i class="bi bi-clock-history" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="text-heading font-weight-bold">Belum Ada Rapat yang Bisa Diisi</h5>
                    <p class="text-softer mb-0">Tidak ada agenda rapat aktif saat ini. Absensi dibuka mulai 24 jam sebelum rapat dimulai hingga rapat selesai.</p>
                </div>
            @else
                <div class="glass-card">
                    <h5 class="card-title-premium">
                        <i class="bi bi-pencil-square"></i> Formulir Absensi Anggota
                    </h5>

                    <form action="{{ route('anggota.absensi.store') }}" method="POST" enctype="multipart/form-data" class="mt-4" id="absensi-form">
                        @csrf
                        
                        {{-- Agenda Selection as Cards --}}
                        <div class="mb-4">
                            <label class="form-label text-softer d-block mb-3">Agenda Rapat Hari Ini <span class="text-danger">*</span></label>
                            
                            @if($agendas->count() == 1)
                                @php $agenda = $agendas->first(); @endphp
                                <input type="hidden" name="id_agenda" value="{{ $agenda->id_agenda }}">
                                <div class="border border-primary border-opacity-40 rounded-4 p-3 bg-primary bg-opacity-5">
                                    <div class="d-flex align-items-start">
                                        <div class="rounded-circle bg-primary bg-opacity-15 p-2 me-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; min-width: 44px;">
                                            <i class="bi bi-calendar-event text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-heading font-weight-bold mb-1">{{ $agenda->judul_agenda }}</h6>
                                            <div class="text-softer small">
                                                <i class="bi bi-clock me-1"></i>{{ substr($agenda->waktu_mulai, 0, 5) }} - {{ substr($agenda->waktu_selesai, 0, 5) }} WIB
                                                <span class="mx-2">|</span>
                                                <i class="bi bi-geo-alt-fill me-1"></i>{{ $agenda->lokasi }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="row g-3">
                                    @foreach($agendas as $agenda)
                                        <div class="col-12">
                                            <label class="w-100 cursor-pointer">
                                                <input type="radio" name="id_agenda" value="{{ $agenda->id_agenda }}" class="d-none agenda-radio" {{ old('id_agenda') == $agenda->id_agenda ? 'checked' : '' }} required>
                                                <div class="border border-secondary border-opacity-20 rounded-4 p-3 bg-secondary bg-opacity-5 agenda-card transition-all">
                                                    <div class="d-flex align-items-start">
                                                        <div class="rounded-circle bg-secondary bg-opacity-15 p-2 me-3 d-flex align-items-center justify-content-center agenda-icon" style="width: 44px; height: 44px; min-width: 44px;">
                                                            <i class="bi bi-calendar-event text-secondary"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="text-heading font-weight-bold mb-1">{{ $agenda->judul_agenda }}</h6>
                                                            <div class="text-softer small">
                                                                <i class="bi bi-clock me-1"></i>{{ substr($agenda->waktu_mulai, 0, 5) }} - {{ substr($agenda->waktu_selesai, 0, 5) }} WIB
                                                                <span class="mx-2">|</span>
                                                                <i class="bi bi-geo-alt-fill me-1"></i>{{ $agenda->lokasi }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            @error('id_agenda')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Status Kehadiran --}}
                        <div class="mb-4">
                            <label class="form-label text-softer d-block mb-3">Status Kehadiran <span class="text-danger">*</span></label>
                            <div class="row g-3">
                                @foreach($statuses as $status)
                                    <div class="col-sm-6">
                                        <div class="border border-secondary border-opacity-15 rounded-3 p-3 bg-secondary bg-opacity-10 h-100 d-flex align-items-center">
                                            <input class="form-check-input me-3 status-radio" type="radio" name="id_status" id="status_{{ $status->id_status }}" value="{{ $status->id_status }}" {{ old('id_status') == $status->id_status ? 'checked' : '' }} required>
                                            <label class="form-check-label text-soft cursor-pointer w-100" for="status_{{ $status->id_status }}">
                                                <span class="d-block font-weight-bold mb-1">{{ $status->nama_status }}</span>
                                                <small class="text-softer">Bobot: {{ (float)$status->bobot_kehadiran }}</small>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('id_status')
                                <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Conditional: Bukti Foto Hadir --}}
                        <div id="hadir-fields" class="d-none border border-success border-opacity-20 rounded-4 p-4 mb-4 bg-success bg-opacity-5">
                            <h6 class="text-success font-weight-semibold mb-3">
                                <i class="bi bi-camera-fill me-1"></i> Bukti Kehadiran
                            </h6>
                            <div>
                                <label for="bukti_foto_hadir" class="form-label text-softer">Upload Foto Kehadiran <span class="text-danger">*</span></label>
                                <input type="file" name="bukti_foto" id="bukti_foto_hadir" class="form-control @error('bukti_foto') is-invalid @enderror" accept="image/*">
                                <small class="text-muted mt-1 d-block">Foto Anda di ruangan rapat. Format: JPG, JPEG, PNG. Maks 2MB.</small>
                                @error('bukti_foto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Conditional: Bukti Surat Sakit --}}
                        <div id="sakit-fields" class="d-none border border-pink border-opacity-20 rounded-4 p-4 mb-4" style="border-color: rgba(236,72,153,0.3) !important; background: rgba(236,72,153,0.05);">
                            <h6 class="font-weight-semibold mb-3" style="color: #ec4899;">
                                <i class="bi bi-file-medical-fill me-1"></i> Rincian Sakit
                            </h6>
                            <div>
                                <label for="bukti_foto_sakit" class="form-label text-softer">Upload Surat Sakit <span class="text-danger">*</span></label>
                                <input type="file" name="bukti_foto" id="bukti_foto_sakit" class="form-control @error('bukti_foto') is-invalid @enderror" accept="image/*">
                                <small class="text-muted mt-1 d-block">Foto surat keterangan sakit dari dokter. Format: JPG, JPEG, PNG. Maks 2MB.</small>
                                @error('bukti_foto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Conditional: Permission Fields (Izin) --}}
                        <div id="izin-fields" class="d-none border border-secondary border-opacity-20 rounded-4 p-4 mb-4 bg-secondary bg-opacity-5">
                            <h6 class="text-warning font-weight-semibold mb-3">
                                <i class="bi bi-info-circle-fill me-1"></i> Rincian Izin Rapat
                            </h6>
                            
                            <div class="mb-3">
                                <label for="alasan" class="form-label text-softer">Alasan Izin <span class="text-danger">*</span></label>
                                <textarea name="alasan" id="alasan" rows="3" class="form-control @error('alasan') is-invalid @enderror" placeholder="Tulis alasan tidak dapat menghadiri rapat secara lengkap...">{{ old('alasan') }}</textarea>
                                @error('alasan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label for="bukti_file" class="form-label text-softer">Upload Bukti (Opsional)</label>
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
        function toggleConditionalFields() {
            var selectedStatus = $('input[name="id_status"]:checked').val();
            
            // Hide all conditional sections first
            $('#hadir-fields, #sakit-fields, #izin-fields').addClass('d-none');
            $('#alasan').prop('required', false);
            // Disable all bukti_foto inputs to prevent sending wrong one
            $('#bukti_foto_hadir, #bukti_foto_sakit').prop('disabled', true).prop('required', false);
            
            if (selectedStatus == 1) { // Hadir
                $('#hadir-fields').removeClass('d-none');
                $('#bukti_foto_hadir').prop('disabled', false).prop('required', true);
            } else if (selectedStatus == 5) { // Sakit
                $('#sakit-fields').removeClass('d-none');
                $('#bukti_foto_sakit').prop('disabled', false).prop('required', true);
            } else if (selectedStatus == 4) { // Izin
                $('#izin-fields').removeClass('d-none');
                $('#alasan').prop('required', true);
            }
        }

        // Agenda card selection styling
        $('.agenda-radio').on('change', function() {
            $('.agenda-card').removeClass('border-primary border-opacity-40').addClass('border-secondary border-opacity-20');
            $('.agenda-icon').removeClass('bg-primary bg-opacity-15').addClass('bg-secondary bg-opacity-15');
            $('.agenda-icon i').removeClass('text-primary').addClass('text-secondary');
            
            var card = $(this).closest('label').find('.agenda-card');
            card.removeClass('border-secondary border-opacity-20').addClass('border-primary border-opacity-40');
            card.find('.agenda-icon').removeClass('bg-secondary bg-opacity-15').addClass('bg-primary bg-opacity-15');
            card.find('.agenda-icon i').removeClass('text-secondary').addClass('text-primary');
        });

        $('.status-radio').on('change', toggleConditionalFields);
        
        // Run on load to support validation error backfills
        toggleConditionalFields();
        // Apply initial agenda selection styling
        $('.agenda-radio:checked').trigger('change');
    });
</script>
@endsection
