@extends('layouts.app')

@section('title', 'Detail Denda')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Detail Denda</h3>
        <p class="text-muted mb-0">Informasi lengkap denda peminjaman</p>
    </div>

    <a href="{{ route('petugas.denda.index') }}" class="btn btn-secondary">
        Kembali
    </a>
</div>

@php
$user = $peminjaman->user;
$profil = $user->profilSiswa ?? null;
$dataSiswa = $profil->dataSiswa ?? null;

$statusColor = [
'belum' => 'danger',
'lunas' => 'success',
'tidak_ada' => 'secondary'
];

$kondisiColor = fn($k) => $k == 'baik' ? 'success' : ($k == 'rusak' ? 'warning' : 'danger');
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

    <!-- INFORMASI DENDA -->
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-body">

                <h6 class="fw-semibold mb-3">Informasi Denda</h6>

                <div class="row gy-3">

                    <div class="col-md-6">
                        <label class="form-label small text-muted">Kode Peminjaman</label>
                        <div class="fw-medium">{{ $peminjaman->kode_peminjaman }}</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small text-muted">Status Denda</label>
                        <div>
                            <span
                                class="badge bg-{{ $statusColor[$peminjaman->status_denda] }} bg-opacity-10 text-{{ $statusColor[$peminjaman->status_denda] }}">
                                {{ ucfirst($peminjaman->status_denda) }}
                            </span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small text-muted">Total Denda</label>
                        <div class="fw-semibold text-danger">
                            Rp {{ number_format($peminjaman->total_denda,0,',','.') }}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small text-muted">Tanggal Pinjam</label>
                        <div>{{ $peminjaman->tgl_pinjam->format('d M Y') }}</div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- DATA PENGEMBALIAN -->
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
                                <th width="80">Qty</th>
                                <th width="120">Kondisi</th>
                                <th width="150">Denda</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($peminjaman->pengembalian->items as $item)
                            <tr>
                                <td class="fw-medium">{{ $item->alat->nama_alat ?? '-' }}</td>
                                <td>{{ $item->qty }}</td>
                                <td>
                                    <span
                                        class="badge bg-{{ $kondisiColor($item->kondisi) }} bg-opacity-10 text-{{ $kondisiColor($item->kondisi) }}">
                                        {{ ucfirst($item->kondisi) }}
                                    </span>
                                </td>
                                <td>Rp {{ number_format($item->denda,0,',','.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 d-flex flex-wrap gap-2">

                    @if($peminjaman->status_denda !== 'lunas')
                    <form id="formIngatkan" action="{{ route('petugas.denda.ingatkan', $peminjaman->id) }}"
                        method="POST">
                        @csrf
                        <button type="button" class="btn btn-secondary btn-sm" id="btnIngatkan">
                            Kirim Pengingat
                        </button>
                    </form>

                    <form id="formLunas" action="{{ route('petugas.denda.lunas', $peminjaman->id) }}" method="POST">
                        @csrf
                        <button type="button" class="btn btn-primary btn-sm" id="btnLunas">
                            Tandai Lunas
                        </button>
                    </form>
                    @endif

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

@push('scripts')
<script>
document.getElementById('btnIngatkan')?.addEventListener('click', function() {
    Swal.fire({
        title: 'Kirim pengingat?',
        text: 'Notifikasi akan dikirim ke peminjam.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Kirim'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('formIngatkan').submit();
        }
    });
});

document.getElementById('btnLunas')?.addEventListener('click', function() {
    Swal.fire({
        title: 'Konfirmasi',
        text: 'Tandai denda sebagai lunas?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('formLunas').submit();
        }
    });
});
</script>
@endpush
@endsection