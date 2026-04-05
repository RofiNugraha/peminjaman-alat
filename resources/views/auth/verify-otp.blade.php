@extends('layouts.auth')

@section('title', 'Verifikasi OTP')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">

        <div class="auth-left d-none d-md-flex">
            <div class="auth-overlay">
                <h5 class="brand-title">Verifikasi</h5>
                <p class="brand-subtitle">
                    Masukkan kode OTP yang telah dikirim ke email Anda.
                </p>
            </div>
        </div>

        <div class="auth-right">
            <div class="auth-header">
                <h4 class="fw-semibold mb-1">Verifikasi OTP</h4>
                <p class="text-muted small">Periksa email Anda</p>
            </div>

            <form method="POST" action="{{ route('otp.verify') }}">
                @csrf
                <input type="hidden" name="email" value="{{ request('email') }}">

                <div class="mb-4">
                    <label class="form-label">Kode OTP</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-shield-lock"></i>
                        </span>
                        <input type="text" name="otp" class="form-control @error('otp') is-invalid @enderror"
                            placeholder="Masukkan kode OTP">
                    </div>
                    @error('otp')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <button class="btn btn-primary w-100 btn-login">
                    Verifikasi
                </button>
            </form>
        </div>

    </div>
</div>
@endsection