@extends('layouts.app')

@section('title','Detail User')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Detail User</h3>
        <p class="text-muted mb-0">Informasi lengkap pengguna</p>
    </div>

    <a href="{{ route('users.index') }}" class="btn btn-secondary">
        Kembali
    </a>
</div>

@php
$colors = [
'admin' => 'primary',
'petugas' => 'success',
'peminjam' => 'warning'
];

$profil = $user->profilSiswa ?? null;
@endphp

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card text-center">
            <div class="card-body">

                <div class="mb-3">
                    <img src="{{ $profil && $profil->foto ? asset('storage/'.$profil->foto) : asset('storage/profil/default.png') }}"
                        class="rounded-circle shadow-sm" width="120" height="120" style="object-fit: cover;">
                </div>

                <h5 class="fw-semibold mb-1">{{ $user->nama }}</h5>

                <span class="badge bg-{{ $colors[$user->role] }} bg-opacity-10 text-{{ $colors[$user->role] }} mb-2">
                    {{ ucfirst($user->role) }}
                </span>

                <p class="text-muted small mb-0">
                    {{ $user->email }}
                </p>

            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">

                <h6 class="fw-semibold mb-3">Informasi Akun</h6>

                <div class="row gy-3">

                    <div class="col-md-6">
                        <label class="form-label small text-muted">Nama</label>
                        <div class="fw-medium">{{ $user->nama }}</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small text-muted">Username</label>
                        <div>{{ $user->username }}</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small text-muted">Email</label>
                        <div>{{ $user->email }}</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small text-muted">Role</label>
                        <div>
                            <span
                                class="badge bg-{{ $colors[$user->role] }} bg-opacity-10 text-{{ $colors[$user->role] }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small text-muted">Tanggal Dibuat</label>
                        <div>
                            {{ $user->created_at->format('d M Y H:i') }}
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    @if($user->role === 'peminjam')
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h6 class="fw-semibold mb-3">Data Siswa</h6>

                @php
                $dataSiswa = null;
                if($profil && $profil->nisn){
                $dataSiswa = \App\Models\DataSiswa::where('nisn',$profil->nisn)->first();
                }
                @endphp

                <div class="row gy-3">

                    <div class="col-md-4">
                        <label class="form-label small text-muted">NISN</label>
                        <div>{{ $profil->nisn ?? '-' }}</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small text-muted">Nama Siswa</label>
                        <div>{{ $dataSiswa->nama ?? '-' }}</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small text-muted">Kelas</label>
                        <div>{{ $dataSiswa->kelas ?? '-' }}</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small text-muted">Jurusan</label>
                        <div>{{ $dataSiswa->jurusan ?? '-' }}</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small text-muted">Tahun Angkatan</label>
                        <div>{{ $dataSiswa->tahun_angkatan ?? '-' }}</div>
                    </div>

                </div>

                <hr class="my-4">

                <h6 class="fw-semibold mb-3">Profil Siswa</h6>

                <div class="row gy-3">

                    <div class="col-md-4">
                        <label class="form-label small text-muted">No HP</label>
                        <div>{{ $profil->no_hp ?? '-' }}</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small text-muted">No HP Orang Tua</label>
                        <div>{{ $profil->no_hp_ortu ?? '-' }}</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small text-muted">Alamat</label>
                        <div>{{ $profil->alamat ?? '-' }}</div>
                    </div>

                </div>

            </div>
        </div>
    </div>
    @endif

</div>
@endsection