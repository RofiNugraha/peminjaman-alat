@extends('layouts.auth')

@section('title', 'Lupa Password')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-left d-none d-md-flex">
            <div class="auth-overlay">
                <h5 class="brand-title">Reset Password</h5>
                <p class="brand-subtitle">
                    Masukkan email untuk menerima kode verifikasi.
                </p>
            </div>
        </div>

        <div class="auth-right">
            <div class="auth-header">
                <h4 class="fw-semibold mb-1">Lupa Password</h4>
                <p class="text-muted small">Kami akan mengirimkan kode OTP</p>
            </div>

            <form method="POST" action="{{ route('send.otp') }}">
                @csrf

                <div class="mb-4">
                    <label class="form-label">Email Terdaftar</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            placeholder="Masukkan email">
                    </div>
                    @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <button class="btn btn-primary w-100 btn-login">
                    Kirim OTP
                </button>

                <p class="text-center mt-4 small">
                    <a href="{{ route('login') }}" class="link-muted">
                        Kembali ke login
                    </a>
                </p>
            </form>
        </div>

    </div>
</div>
@endsection