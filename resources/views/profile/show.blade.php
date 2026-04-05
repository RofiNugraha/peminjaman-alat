@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="page-header mb-4">
    <h3 class="mb-1">Profil Saya</h3>
    <p class="mb-0">Kelola informasi akun dan keamanan</p>
</div>

@if (session('success'))
<div class="alert alert-success mb-3">
    {{ session('success') }}
</div>
@endif

@if ($errors->any())
<div class="alert alert-danger mb-3">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="row g-4">

    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-body">

                <h6 class="fw-semibold mb-3">Informasi Akun</h6>

                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                            value="{{ old('nama', $user->nama) }}">

                        @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                            value="{{ old('username', $user->username) }}">

                        @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}">

                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button class="btn btn-primary">Simpan Perubahan</button>

                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-body">

                <h6 class="fw-semibold mb-3">Keamanan Akun</h6>

                <form action="{{ route('profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Password Lama</label>
                        <input type="password" name="current_password"
                            class="form-control @error('current_password') is-invalid @enderror">

                        @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="new_password"
                            class="form-control @error('new_password') is-invalid @enderror">

                        @error('new_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="new_password_confirmation" class="form-control">
                    </div>

                    <button class="btn btn-primary">Simpan Password</button>

                </form>
            </div>
        </div>
    </div>

</div>

@if($user->role === 'peminjam')
<div class="card mt-4">
    <div class="card-body">

        <h6 class="fw-semibold mb-4">Profil Siswa</h6>

        <form action="{{ route('profile.siswa.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="text-center mb-4">
                <img src="{{ optional($user->profilSiswa)->foto ? asset('storage/'.$user->profilSiswa->foto) : asset('storage/profil/default.png') }}"
                    class="foto-profile mb-2">

                <div class="mx-auto" style="max-width:250px">
                    <input type="file" name="foto" class="form-control">
                </div>

                <div class="mt-2">
                    @php
                    $status = optional($user->profilSiswa->dataSiswa ?? null)->status;
                    @endphp

                    <span class="badge {{ $status === 'aktif' ? 'bg-success' : 'bg-secondary' }}">
                        {{ $status ?? 'Tidak diketahui' }}
                    </span>
                </div>
            </div>

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">NISN</label>
                    <input type="text" name="nisn" id="nisn" class="form-control @error('nisn') is-invalid @enderror"
                        value="{{ old('nisn', optional($user->profilSiswa)->nisn) }}">

                    @error('nisn')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" id="nama" class="form-control"
                        value="{{ optional($user->profilSiswa->dataSiswa ?? null)->nama }}" readonly>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Kelas</label>
                    <input type="text" id="kelas" class="form-control"
                        value="{{ optional($user->profilSiswa->dataSiswa ?? null)->kelas }}" readonly>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Jurusan</label>
                    <input type="text" id="jurusan" class="form-control"
                        value="{{ optional($user->profilSiswa->dataSiswa ?? null)->jurusan }}" readonly>
                </div>

                <div class="col-md-6">
                    <label class="form-label">No HP</label>
                    <input type="text" name="no_hp" class="form-control"
                        value="{{ old('no_hp', optional($user->profilSiswa)->no_hp) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">No HP Orang Tua</label>
                    <input type="text" name="no_hp_ortu" class="form-control"
                        value="{{ old('no_hp_ortu', optional($user->profilSiswa)->no_hp_ortu) }}">
                </div>

                <div class="col-12">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control"
                        rows="3">{{ old('alamat', optional($user->profilSiswa)->alamat) }}</textarea>
                </div>

                <div class="col-12">
                    <button class="btn btn-primary">Simpan Profil Siswa</button>
                </div>

            </div>

        </form>

    </div>
</div>
@endif

@push('scripts')
<script>
document.getElementById('nisn')?.addEventListener('keyup', function() {
    let nisn = this.value;

    if (nisn.length >= 5) {
        fetch(`/get-siswa/${nisn}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('nama').value = data?.nama ?? '';
                document.getElementById('kelas').value = data?.kelas ?? '';
                document.getElementById('jurusan').value = data?.jurusan ?? '';
            });
    }
});

document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function() {
        let btn = this.querySelector('button[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.innerText = 'Menyimpan...';
        }
    });
});
</script>
@endpush

@endsection