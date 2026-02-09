@extends('layouts.auth')

@section('title', 'Verifikasi OTP')
@section('heading', 'Verifikasi OTP')

@section('content')
<form method="POST" action="{{ route('otp.verify') }}">
    @csrf

    <input type="hidden" name="email" value="{{ request('email') }}">

    <div class="mb-3">
        <label class="form-label">Kode OTP</label>
        <input type="text" name="otp" class="form-control" required>
        @error('otp')
        <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <button class="btn btn-primary w-100">Verifikasi</button>
</form>
@endsection