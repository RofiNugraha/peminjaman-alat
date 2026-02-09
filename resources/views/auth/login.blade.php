@extends('layouts.auth')

@section('title', 'Login')
@section('heading', 'Login')

@section('content')
<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
    </div>

    <div class="mb-3">
        <label class="form-label">Password</label>
        <div class="input-group">
            <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                <i id="eyeIcon" class="bi bi-eye"></i>
            </button>
        </div>
        <small class="text-muted">
            Password minimal 8 karakter, gunakan kombinasi huruf & angka.
        </small>
    </div>

    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('password.request') }}">Lupa Password?</a>
    </div>

    <button class="btn btn-primary w-100">Login</button>

    <p class="text-center mt-3">
        Belum punya akun?
        <a href="{{ route('register') }}">Daftar</a>
    </p>
</form>

<script>
function togglePassword() {
    const input = document.getElementById('password');
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>
@endsection