@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">

        <div class="auth-left d-none d-md-flex">
            <div class="auth-overlay">
                <h5 class="brand-title">Password Baru</h5>
                <p class="brand-subtitle">
                    Buat password baru untuk akun Anda.
                </p>
            </div>
        </div>

        <div class="auth-right">
            <div class="auth-header">
                <h4 class="fw-semibold mb-1">Reset Password</h4>
                <p class="text-muted small">Gunakan password yang kuat</p>
            </div>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">

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
                            onclick="togglePassword('password','eye1')">
                            <i id="eye1" class="bi bi-eye"></i>
                        </button>
                    </div>
                    @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">Konfirmasi Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input type="password" name="password_confirmation" id="password2" class="form-control"
                            placeholder="Ulangi password">
                        <button type="button" class="btn btn-outline-secondary"
                            onclick="togglePassword('password2','eye2')">
                            <i id="eye2" class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <button class="btn btn-primary w-100 btn-login">
                    Simpan Password
                </button>
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