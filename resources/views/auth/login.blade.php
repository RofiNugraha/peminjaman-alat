@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-left d-none d-md-flex">
            <div class="auth-overlay">
                <h5 class="brand-title">Sistem Peminjaman</h5>
                <p class="brand-subtitle">
                    Kelola peminjaman alat elektronik dengan mudah dan teratur.
                </p>
            </div>
        </div>

        <div class="auth-right">
            <div class="auth-header">
                <h4 class="fw-semibold mb-1">Masuk</h4>
                <p class="text-muted small">Silakan login untuk melanjutkan</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-person"></i>
                        </span>
                        <input type="text" name="username" value="{{ old('username') }}"
                            class="form-control @error('username') is-invalid @enderror"
                            placeholder="Masukkan username">
                    </div>
                    @error('username')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>

                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input type="password" name="password" id="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Masukkan password">
                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </button>
                    </div>

                    @error('password')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                    @enderror

                    <small class="text-muted">
                        Minimal 8 karakter.
                    </small>
                </div>

                <div class="d-flex justify-content-between mb-4">
                    <a href="{{ route('password.request') }}" class="link-muted small">
                        Lupa password?
                    </a>
                </div>

                <button class="btn btn-primary w-100 btn-login">
                    Login
                </button>

                <p class="text-center mt-4 small">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="fw-medium">
                        Daftar
                    </a>
                </p>

            </form>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const input = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}
</script>
@endsection