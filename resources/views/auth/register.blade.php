@extends('layouts.auth')

@section('title', 'Register')
@section('heading', 'Registrasi Akun')

@section('content')
<form method="POST" action="{{ route('register.store') }}">
    @csrf

    <div class="mb-3">
        <label class="form-label">Nama Lengkap</label>
        <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap" required>

    </div>

    <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control" placeholder="Username" required>

    </div>

    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" placeholder="example@gmail.com" required>

    </div>

    <div class="mb-3">
        <label class="form-label">Password</label>

        <div class="input-group">
            <input type="password" name="password" id="password" class="form-control" placeholder="Password" required
                minlength="8">
            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password','eyePassword')">
                <i id="eyePassword" class="bi bi-eye"></i>
            </button>
        </div>

        <small class="text-muted">
            Password minimal 8 karakter, gunakan kombinasi huruf & angka.
        </small>
    </div>

    <div class="mb-3">
        <label class="form-label">Konfirmasi Password</label>

        <div class="input-group">
            <input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi Password"
                required>
            <button type="button" class="btn btn-outline-secondary toggle-password">
                <i class="bi bi-eye"></i>
            </button>
        </div>
    </div>

    <div class="alert alert-warning small">
        Pastikan data yang Anda masukkan benar.
    </div>

    <button class="btn btn-success w-100">Daftar</button>

    <p class="text-center mt-3">
        Sudah punya akun?
        <a href="{{ route('login') }}">Login</a>
    </p>
</form>

<script>
document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', function() {
        const input = this.previousElementSibling;
        const icon = this.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    });
});
</script>

@endsection