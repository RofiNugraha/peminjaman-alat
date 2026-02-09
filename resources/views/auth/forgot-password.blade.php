@extends('layouts.auth')

@section('title', 'Lupa Password')
@section('heading', 'Lupa Password')

@section('content')
<form method="POST" action="{{ route('send.otp') }}">
    @csrf

    <div class="mb-3">
        <label class="form-label">Email Terdaftar</label>
        <input type="email" name="email" class="form-control" required>
        @error('email')
        <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <button class="btn btn-warning w-100">Kirim OTP</button>

    <p class="text-center mt-3">
        <a href="{{ route('login') }}">Kembali ke Login</a>
    </p>
</form>
@endsection