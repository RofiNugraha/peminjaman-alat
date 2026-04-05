@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">

        <!-- LEFT -->
        <div class="auth-left d-none d-md-flex">
            <div class="auth-overlay">
                <h5 class="brand-title">Sistem Peminjaman</h5>
                <p class="brand-subtitle">
                    Buat akun untuk mulai menggunakan sistem peminjaman alat elektronik.
                </p>
            </div>
        </div>

        <!-- RIGHT -->
        <div class="auth-right">

            <div class="auth-header">
                <h4 class="fw-semibold mb-1">Daftar Akun</h4>
                <p class="text-muted small">Isi data dengan benar</p>
            </div>

            <form method="POST" action="{{ route('register.store') }}">
                @csrf

                <!-- Nama -->
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-person"></i>
                        </span>
                        <input type="text" name="nama" value="{{ old('nama') }}"
                            class="form-control @error('nama') is-invalid @enderror"
                            placeholder="Masukkan nama lengkap">
                    </div>
                    @error('nama')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Username -->
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-person-badge"></i>
                        </span>
                        <input type="text" name="username" value="{{ old('username') }}"
                            class="form-control @error('username') is-invalid @enderror"
                            placeholder="Masukkan username">
                    </div>
                    @error('username')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan email">
                    </div>
                    @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input type="password" name="password" id="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Masukkan password">
                        <button type="button" class="btn btn-outline-secondary"
                            onclick="togglePassword('password','eyePassword')">
                            <i id="eyePassword" class="bi bi-eye"></i>
                        </button>
                    </div>
                    @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Minimal 8 karakter.</small>
                </div>

                <!-- Konfirmasi Password -->
                <div class="mb-4">
                    <label class="form-label">Konfirmasi Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input type="password" name="password_confirmation" id="password2" class="form-control"
                            placeholder="Ulangi password">
                        <button type="button" class="btn btn-outline-secondary"
                            onclick="togglePassword('password2','eyePassword2')">
                            <i id="eyePassword2" class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Button -->
                <button class="btn btn-primary w-100 btn-login">
                    Daftar
                </button>

                <!-- Login -->
                <p class="text-center mt-4 small">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="fw-medium">
                        Login
                    </a>
                </p>

            </form>
        </div>
    </div>
</div>

<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
}
</script>
@endsection