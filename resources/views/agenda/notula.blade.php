@extends('layouts.app')

@section('title', 'Notula Rapat')

@section('content')
<div class="container-fluid p-0">

    <div class="row mb-4 align-items-center">
        <div class="col-sm-8">
            <h3 class="text-heading font-weight-bold">Notula Rapat</h3>
            <p class="text-softer mb-0">Tulis atau edit catatan hasil rapat (notula) untuk agenda ini.</p>
        </div>
        <div class="col-sm-4 text-sm-end mt-3 mt-sm-0">
            <a href="{{ route('sekretaris.agenda.index') }}" class="btn btn-outline-premium">
                <i class="bi bi-chevron-left me-1"></i> Kembali ke Agenda
            </a>
        </div>
    </div>

    <!-- Agenda Info Panel -->
    <div class="glass-card mb-4">
        <h5 class="card-title-premium">
            <i class="bi bi-info-circle-fill text-primary"></i> Informasi Rapat
        </h5>
        
        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <div class="mb-2"><strong class="text-heading">Judul Agenda:</strong> <span class="text-soft">{{ $agenda->judul_agenda }}</span></div>
                <div class="mb-2"><strong class="text-heading">Deskripsi:</strong> <span class="text-soft">{{ $agenda->deskripsi ?? '-' }}</span></div>
            </div>
            <div class="col-md-6">
                <div class="mb-2"><strong class="text-heading">Tanggal:</strong> <span class="text-soft">{{ date('d F Y', strtotime($agenda->tanggal_rapat)) }}</span></div>
                <div class="mb-2"><strong class="text-heading">Waktu:</strong> <span class="text-soft">{{ substr($agenda->waktu_mulai, 0, 5) }} - {{ substr($agenda->waktu_selesai, 0, 5) }} WIB</span></div>
                <div class="mb-2"><strong class="text-heading">Lokasi:</strong> <span class="text-soft">{{ $agenda->lokasi }}</span></div>
            </div>
        </div>
    </div>

    <!-- Notula Form -->
    <div class="glass-card">
        <h5 class="card-title-premium">
            <i class="bi bi-journal-text"></i> {{ $agenda->notula || $agenda->notula_file ? 'Edit Notula' : 'Unggah / Tulis Notula' }}
        </h5>

        <form action="{{ route('sekretaris.agenda.notula.store', $agenda->id_agenda) }}" method="POST" enctype="multipart/form-data" class="mt-3">
            @csrf

            <!-- Opsi 1: Tulis Notula -->
            <div class="mb-4">
                <label for="notula" class="form-label text-softer">Opsi 1: Tulis Notula Rapat</label>
                <textarea name="notula" id="notula" rows="10" class="form-control @error('notula') is-invalid @enderror" placeholder="Tuliskan catatan hasil rapat di sini...&#10;&#10;Contoh:&#10;1. Pembukaan oleh ketua rapat&#10;2. Pembahasan poin A&#10;3. Keputusan yang diambil&#10;4. Penutup">{{ old('notula', $agenda->notula) }}</textarea>
                @error('notula')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Opsi 2: Upload File Notula -->
            <div class="mb-4">
                <label for="notula_file" class="form-label text-softer">Opsi 2: Unggah Berkas Notula (PDF/Word/Gambar)</label>
                <input type="file" name="notula_file" id="notula_file" class="form-control @error('notula_file') is-invalid @enderror">
                @error('notula_file')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                @if($agenda->notula_file)
                    <div class="mt-2 text-success small">
                        <i class="bi bi-file-earmark-check-fill me-1"></i> Berkas saat ini: 
                        <a href="{{ $agenda->notula_file }}" target="_blank" class="text-info font-weight-semibold text-decoration-underline" style="color: #60a5fa !important;">{{ basename($agenda->notula_file) }}</a>
                    </div>
                @endif
                <small class="text-muted mt-1 d-block">Format: PDF, DOC, DOCX, JPG, JPEG, PNG. Maksimal 5MB.</small>
            </div>

            <div class="p-3 bg-secondary bg-opacity-10 border border-secondary border-opacity-10 rounded-3 mb-4">
                <small class="text-muted d-block"><i class="bi bi-info-circle me-1"></i> <strong>Petunjuk:</strong> Anda dapat menulis notula secara langsung, mengunggah berkas saja, atau mengisi keduanya sekaligus. Salah satu dari opsi di atas wajib diisi.</small>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('sekretaris.agenda.index') }}" class="btn btn-outline-premium">Batal</a>
                <button type="submit" class="btn btn-premium">
                    <i class="bi bi-save me-1"></i> Simpan Notula
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
