@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Detail Peminjaman</h3>
        <p class="text-muted mb-0">Informasi lengkap peminjaman</p>
    </div>

    <a href="{{ route('petugas.peminjaman.index') }}" class="btn btn-secondary">
        Kembali
    </a>
</div>

@php
$user = $peminjaman->user;
$profil = $user->profilSiswa ?? null;
$dataSiswa = $profil->dataSiswa ?? null;

$colors = [
'menunggu' => 'warning',
'disetujui' => 'success',
'ditolak' => 'danger',
'dibatalkan' => 'secondary',
'dikembalikan' => 'info',
'kadaluarsa' => 'dark'
];

$isMenunggu = $peminjaman->status === 'menunggu';
$isExpired = $peminjaman->isExpired();
$stokCukup = $peminjaman->items->every(fn($i) => $i->alat->stok >= $i->qty);
@endphp

<div class="row g-4">

    <!-- PROFIL -->
    <div class="col-lg-4">
        <div class="card text-center">
            <div class="card-body">

                <img src="{{ $profil && $profil->foto ? asset('storage/'.$profil->foto) : asset('storage/profil/default.png') }}"
                    class="rounded-circle mb-3" width="110" height="110" style="object-fit:cover;">

                <h5 class="fw-semibold mb-1">{{ $user->nama }}</h5>

                <span class="badge bg-warning bg-opacity-10 text-warning mb-2">
                    Peminjam
                </span>

                <p class="text-muted small mb-0">{{ $user->email }}</p>

            </div>
        </div>
    </div>

    <!-- INFORMASI PEMINJAMAN -->
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-body">

                <h6 class="fw-semibold mb-3">Informasi Peminjaman</h6>

                <div class="row gy-3">

                    <div class="col-md-6">
                        <label class="form-label small text-muted">Kode Peminjaman</label>
                        <div class="fw-medium">{{ $peminjaman->kode_peminjaman }}</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small text-muted">Status</label>
                        <div>
                            <span
                                class="badge bg-{{ $colors[$peminjaman->status] }} bg-opacity-10 text-{{ $colors[$peminjaman->status] }}">
                                {{ ucfirst($peminjaman->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small text-muted">Tanggal Pinjam</label>
                        <div>{{ $peminjaman->tgl_pinjam->format('d M Y') }}</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small text-muted">Tanggal Kembali</label>
                        <div>{{ $peminjaman->tgl_kembali->format('d M Y') }}</div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- ALAT -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h6 class="fw-semibold mb-3">Daftar Alat Dipinjam</h6>

                <div class="table-responsive">
                    <table class="table table-modern align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Nama Alat</th>
                                <th>Kategori</th>
                                <th width="120">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($peminjaman->items as $item)
                            <tr>
                                <td class="fw-medium">{{ $item->alat->nama_alat }}</td>
                                <td class="text-muted">{{ $item->alat->kategoris->nama_kategori ?? '-' }}</td>
                                <td>{{ $item->qty }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <!-- PENGEMBALIAN -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h6 class="fw-semibold mb-3">Data Pengembalian</h6>

                @if($peminjaman->pengembalian)

                <div class="row gy-3 mb-3">

                    <div class="col-md-4">
                        <label class="form-label small text-muted">Tanggal Dikembalikan</label>
                        <div>
                            {{ \Carbon\Carbon::parse($peminjaman->pengembalian->tgl_dikembalikan)->format('d M Y') }}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small text-muted">Hari Telat</label>
                        <div>{{ $peminjaman->pengembalian->hari_telat }} hari</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small text-muted">Denda Telat</label>
                        <div>Rp {{ number_format($peminjaman->pengembalian->denda_telat,0,',','.') }}</div>
                    </div>

                </div>

                <div class="table-responsive">
                    <table class="table table-modern align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Nama Alat</th>
                                <th class="text-center">Baik</th>
                                <th class="text-center">Rusak</th>
                                <th class="text-center">Hilang</th>
                                <th width="150">Denda</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($peminjaman->pengembalian->items as $item)
                            <tr>
                                <td class="fw-medium">
                                    {{ $item->alat->nama_alat ?? '-' }}
                                    <div class="small text-muted">
                                        Total: {{ $item->qty_baik + $item->qty_rusak + $item->qty_hilang }}
                                    </div>
                                </td>

                                <!-- BAIK -->
                                <td class="text-center">
                                    @if($item->qty_baik > 0)
                                    <span class="badge bg-success bg-opacity-10 text-success">
                                        {{ $item->qty_baik }}
                                    </span>
                                    @else
                                    <span class="text-muted">0</span>
                                    @endif
                                </td>

                                <!-- RUSAK -->
                                <td class="text-center">
                                    @if($item->qty_rusak > 0)
                                    <span class="badge bg-warning bg-opacity-10 text-warning">
                                        {{ $item->qty_rusak }}
                                    </span>
                                    @else
                                    <span class="text-muted">0</span>
                                    @endif
                                </td>

                                <!-- HILANG -->
                                <td class="text-center">
                                    @if($item->qty_hilang > 0)
                                    <span class="badge bg-danger bg-opacity-10 text-danger">
                                        {{ $item->qty_hilang }}
                                    </span>
                                    @else
                                    <span class="text-muted">0</span>
                                    @endif
                                </td>

                                <!-- DENDA -->
                                <td>
                                    Rp {{ number_format($item->denda,0,',','.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <div class="fw-semibold">
                        Total Denda:
                        <span class="text-danger">
                            Rp {{ number_format($peminjaman->total_denda,0,',','.') }}
                        </span>
                    </div>

                    <div class="mt-1">
                        Status:
                        <span class="badge bg-{{ 
                            $peminjaman->status_denda == 'lunas' ? 'success' : 
                            ($peminjaman->status_denda == 'belum' ? 'danger' : 'secondary') 
                        }} bg-opacity-10 text-{{ 
                            $peminjaman->status_denda == 'lunas' ? 'success' : 
                            ($peminjaman->status_denda == 'belum' ? 'danger' : 'secondary') 
                        }}">
                            {{ ucfirst($peminjaman->status_denda) }}
                        </span>
                    </div>
                </div>

                @else
                <div class="text-center text-muted py-4">
                    Belum ada data pengembalian
                </div>
                @endif

            </div>
        </div>
    </div>

    <!-- DATA SISWA -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h6 class="fw-semibold mb-3">Data Siswa</h6>

                <div class="row gy-3">

                    <div class="col-md-4">
                        <label class="form-label small text-muted">NISN</label>
                        <div>{{ $dataSiswa->nisn ?? '-' }}</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small text-muted">Nama</label>
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
                        <label class="form-label small text-muted">Angkatan</label>
                        <div>{{ $dataSiswa->tahun_angkatan ?? '-' }}</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small text-muted">Tahun Ajaran</label>
                        <div>{{ $dataSiswa->tahun_ajaran ?? '-' }}</div>
                    </div>

                </div>

                <hr class="my-4">

                <h6 class="fw-semibold mb-3">Kontak</h6>

                <div class="row gy-3">

                    <div class="col-md-4">
                        <label class="form-label small text-muted">No. HP</label>
                        <div>{{ $profil->no_hp ?? '-' }}</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small text-muted">No. HP Orang Tua</label>
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

</div>
<div class="sticky-action-bar">
    <div class="container-fluid d-flex justify-content-between align-items-center flex-wrap gap-2">

        <div class="text-muted small">
            @if(!$isMenunggu)
            Status: <span class="fw-semibold">{{ ucfirst($peminjaman->status) }}</span>
            @elseif($isExpired)
            <span class="text-danger">Pengajuan sudah kadaluarsa</span>
            @endif
        </div>

        <div class="d-flex gap-2">

            @if(!$stokCukup)
            <button class="btn btn-secondary" disabled>
                Stok Tidak Cukup
            </button>
            @else
            <form method="POST" action="{{ route('petugas.peminjaman.approve', $peminjaman->id) }}">
                @csrf
                <button type="button" class="btn btn-success btn-approve btn-sm" @disabled(!$isMenunggu || $isExpired)>
                    Setujui
                </button>
            </form>
            @endif

            <form method="POST" action="{{ route('petugas.peminjaman.reject', $peminjaman->id) }}">
                @csrf
                <button type="button" class="btn btn-danger btn-reject btn-sm" @disabled(!$isMenunggu || $isExpired)>
                    Tolak
                </button>
            </form>

        </div>

    </div>
</div>

@endsection