@extends('layouts.app')

@section('content')
<h4 class="mb-3">Tambah Petugas</h4>
<p class="text-muted mb-4">Isi data pengguna dengan lengkap</p>

<form method="POST" action="{{ route('users.store') }}">
    @csrf

    <div class="mb-3">
        <label class="form-label">Nama Lengkap</label>
        <input name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}">

        @error('nama')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Username</label>
        <input name="username" class="form-control @error('username') is-invalid @enderror"
            value="{{ old('username') }}">

        @error('username')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
            value="{{ old('email') }}">

        @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-4">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">

        @error('password')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-flex gap-2">
        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
    </div>
</form>

@push('scripts')
<script>
$('#formUser').submit(function() {
    $('#btnSubmit')
        .prop('disabled', true)
        .html('Menyimpan...');
});
</script>
@endpush
@endsection