@extends('layouts.app')

@section('title', 'Edit Agenda Rapat')

@section('content')
<div class="container-fluid p-0">

    <div class="row mb-4">
        <div class="col-12">
            <h3 class="text-heading font-weight-bold">Edit Agenda Rapat</h3>
            <p class="text-secondary">Perbarui rincian agenda rapat yang telah dibuat.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="glass-card">
                
                <h5 class="card-title-premium">
                    <i class="bi bi-pencil-square"></i> Formulir Perubahan Agenda
                </h5>

                <form action="{{ route('sekretaris.agenda.update', $agenda->id_agenda) }}" method="POST" class="mt-4">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="judul_agenda" class="form-label text-secondary">Judul Agenda <span class="text-danger">*</span></label>
                        <input type="text" name="judul_agenda" id="judul_agenda" class="form-control @error('judul_agenda') is-invalid @enderror" placeholder="Contoh: Rapat Koordinasi Anggaran" value="{{ old('judul_agenda', $agenda->judul_agenda) }}" required>
                        @error('judul_agenda')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label text-secondary">Deskripsi Agenda</label>
                        <textarea name="deskripsi" id="deskripsi" rows="4" class="form-control @error('deskripsi') is-invalid @enderror" placeholder="Tuliskan pokok pembahasan atau agenda rapat detail di sini...">{{ old('deskripsi', $agenda->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3 g-3">
                        <div class="col-md-4">
                            <label for="tanggal_rapat" class="form-label text-secondary">Tanggal Rapat <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_rapat" id="tanggal_rapat" class="form-control @error('tanggal_rapat') is-invalid @enderror" value="{{ old('tanggal_rapat', $agenda->tanggal_rapat) }}" required>
                            @error('tanggal_rapat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="waktu_mulai" class="form-label text-secondary">Jam Mulai <span class="text-danger">*</span></label>
                            <input type="time" name="waktu_mulai" id="waktu_mulai" class="form-control @error('waktu_mulai') is-invalid @enderror" value="{{ old('waktu_mulai', substr($agenda->waktu_mulai, 0, 5)) }}" required>
                            @error('waktu_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="waktu_selesai" class="form-label text-secondary">Jam Selesai <span class="text-danger">*</span></label>
                            <input type="time" name="waktu_selesai" id="waktu_selesai" class="form-control @error('waktu_selesai') is-invalid @enderror" value="{{ old('waktu_selesai', substr($agenda->waktu_selesai, 0, 5)) }}" required>
                            @error('waktu_selesai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="lokasi" class="form-label text-secondary">Lokasi Rapat <span class="text-danger">*</span></label>
                        <input type="text" name="lokasi" id="lokasi" class="form-control @error('lokasi') is-invalid @enderror" placeholder="Contoh: Ruang Rapat MPM / Zoom Meeting" value="{{ old('lokasi', $agenda->lokasi) }}" required>
                        @error('lokasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('sekretaris.agenda.index') }}" class="btn btn-outline-premium">Batal</a>
                        <button type="submit" class="btn btn-premium">Simpan Perubahan</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

</div>
@endsection
