@extends('layouts.app')

@section('title', 'Kelola Profil & Keamanan')

@section('content')
<div class="container-fluid p-0">

    <div class="row mb-4">
        <div class="col-12">
            <h3 class="text-heading font-weight-bold">Pengaturan Profil & Keamanan</h3>
            <p class="text-secondary">Kelola data keanggotaan Anda dan atur kata sandi akun.</p>
        </div>
    </div>

    <div class="row g-4">
        
        <!-- Profile Info Form Column -->
        <div class="col-lg-6">
            <div class="glass-card">
                <h5 class="card-title-premium">
                    <i class="bi bi-person-fill text-primary"></i> Edit Informasi Profil
                </h5>

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                    @csrf
                    
                    <!-- Profile Photo Frame -->
                    <div class="d-flex align-items-center mb-4 flex-wrap gap-3">
                        <div class="position-relative">
                            @if($anggota->foto_anggota)
                                <img src="{{ $anggota->foto_anggota }}" alt="Foto Profile" class="rounded-circle border border-4 border-primary" style="width: 90px; height: 90px; object-fit: cover;" id="profile-preview">
                            @else
                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center border border-4 border-primary text-white font-weight-bold" style="width: 90px; height: 90px; font-size: 2.2rem;" id="profile-preview-placeholder">
                                    {{ strtoupper(substr($user->username, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div>
                            <label for="foto_anggota" class="form-label text-secondary font-weight-semibold">Ubah Foto Profil</label>
                            <input type="file" name="foto_anggota" id="foto_anggota" class="form-control form-control-sm @error('foto_anggota') is-invalid @enderror" onchange="previewImage(event)">
                            <small class="text-muted d-block mt-1">Maksimal 2 MB dengan format JPG, JPEG, atau PNG.</small>
                            @error('foto_anggota')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="nim" class="form-label text-secondary">NIM</label>
                        <input type="text" id="nim" class="form-control border-secondary border-opacity-10 text-muted bg-secondary bg-opacity-5" value="{{ $anggota->nim }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label for="jabatan" class="form-label text-secondary">Jabatan</label>
                        <input type="text" id="jabatan" class="form-control border-secondary border-opacity-10 text-muted bg-secondary bg-opacity-5" value="{{ $anggota->jabatan }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label for="nama_anggota" class="form-label text-secondary font-weight-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama_anggota" id="nama_anggota" class="form-control @error('nama_anggota') is-invalid @enderror" value="{{ old('nama_anggota', $anggota->nama_anggota) }}" required>
                        @error('nama_anggota')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email_astra" class="form-label text-secondary font-weight-semibold">Email Astra <span class="text-danger">*</span></label>
                        <input type="email" name="email_astra" id="email_astra" class="form-control @error('email_astra') is-invalid @enderror" value="{{ old('email_astra', $anggota->email_astra) }}" required>
                        @error('email_astra')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="no_hp" class="form-label text-secondary font-weight-semibold">Nomor HP / WhatsApp <span class="text-danger">*</span></label>
                        <input type="text" name="no_hp" id="no_hp" class="form-control @error('no_hp') is-invalid @enderror" value="{{ old('no_hp', $anggota->no_hp) }}" required>
                        @error('no_hp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-premium w-100">Simpan Informasi Profil</button>
                </form>
            </div>
        </div>

        <!-- Security / Password Column -->
        <div class="col-lg-6">
            <div class="glass-card h-100">
                <h5 class="card-title-premium">
                    <i class="bi bi-shield-lock-fill text-warning"></i> Ganti Password Akun
                </h5>

                <form action="{{ route('profile.password') }}" method="POST" class="mt-4">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="current_password" class="form-label text-secondary font-weight-semibold">Password Saat Ini <span class="text-danger">*</span></label>
                        <input type="password" name="current_password" id="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="••••••••" required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label text-secondary font-weight-semibold">Password Baru <span class="text-danger">*</span></label>
                        <input type="password" name="new_password" id="new_password" class="form-control @error('new_password') is-invalid @enderror" placeholder="Minimal 8 karakter" required>
                        @error('new_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="new_password_confirmation" class="form-label text-secondary font-weight-semibold">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" placeholder="Ulangi password baru" required>
                    </div>

                    <button type="submit" class="btn btn-warning w-100 font-weight-bold text-dark">Ubah Password Akun</button>
                </form>
            </div>
        </div>

    </div>

</div>
@endsection

@section('scripts')
<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('profile-preview');
            const placeholder = document.getElementById('profile-preview-placeholder');
            
            if (output) {
                output.src = reader.result;
            } else if (placeholder) {
                // If there was no image, replace placeholder with img element
                const img = document.createElement('img');
                img.id = 'profile-preview';
                img.className = 'rounded-circle border border-4 border-primary';
                img.style.width = '90px';
                img.style.height = '90px';
                img.style.objectFit = 'cover';
                img.src = reader.result;
                placeholder.replaceWith(img);
            }
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection
