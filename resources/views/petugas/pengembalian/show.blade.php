@extends('layouts.app')

@section('title', 'Detail Pengembalian')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Detail Pengembalian</h3>
        <p class="text-muted mb-0">Informasi lengkap proses pengembalian alat</p>
    </div>

    <a href="{{ route('petugas.pengembalian.index') }}" class="btn btn-secondary">
        Kembali
    </a>
</div>

@php
$user = $peminjaman->user;
$profil = $user->profilSiswa ?? null;
$dataSiswa = $profil->dataSiswa ?? null;

$colors = [
'disetujui' => 'success',
'dikembalikan' => 'info'
];
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
                                class="badge bg-{{ $colors[$peminjaman->status] ?? 'secondary' }} bg-opacity-10 text-{{ $colors[$peminjaman->status] ?? 'secondary' }}">
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

                @if($hariTelat > 0)
                <div class="alert alert-warning mt-3 mb-0">
                    Terlambat <b>{{ $hariTelat }} hari</b> • Estimasi denda:
                    <b>Rp {{ number_format($estimasiDendaTelat,0,',','.') }}</b>
                </div>
                @endif

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

    <!-- FORM PENGEMBALIAN -->
    @if(!$peminjaman->pengembalian)
    <div class="col-12">
        <form id="formPengembalian" method="POST" action="{{ route('petugas.pengembalian.store',$peminjaman->id) }}">
            @csrf

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="alert alert-info">
                            Isi kondisi alat dengan benar:
                            <ul class="mb-0">
                                <li>Total (Baik + Rusak + Hilang) harus sama dengan jumlah</li>
                                <li>Jika ada rusak/hilang → wajib isi denda</li>
                            </ul>
                        </div>

                        <h6 class="fw-semibold mb-3">Daftar Alat</h6>

                        <div class="table-responsive">
                            <table class="table table-modern align-middle">
                                <thead>
                                    <tr>
                                        <th>Nama Alat</th>
                                        <th width="100">Qty</th>
                                        <th width="180">Kondisi</th>
                                        <th width="200">Denda</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($peminjaman->items as $item)
                                    <tr class="row-item">
                                        <td class="fw-medium">{{ $item->alat->nama_alat }}</td>
                                        <td class="qty-max">{{ $item->qty }}</td>

                                        <td>
                                            <div class="d-flex flex-column gap-1">
                                                <input type="number" name="qty_baik[{{ $item->id }}]"
                                                    class="form-control qty-input" placeholder="Baik" min="0"
                                                    max="{{ $item->qty }}">

                                                <input type="number" name="qty_rusak[{{ $item->id }}]"
                                                    class="form-control qty-input qty-rusak" placeholder="Rusak" min="0"
                                                    max="{{ $item->qty }}">

                                                <input type="number" name="qty_hilang[{{ $item->id }}]"
                                                    class="form-control qty-input qty-hilang" placeholder="Hilang"
                                                    min="0" max="{{ $item->qty }}">
                                            </div>

                                            <small class="text-danger error-msg d-none"></small>
                                        </td>

                                        <td>
                                            <input type="text" name="denda_rusak[{{ $item->id }}]"
                                                class="form-control denda-rusak mb-2" placeholder="Denda rusak">

                                            <input type="text" name="denda_hilang[{{ $item->id }}]"
                                                class="form-control denda-hilang" placeholder="Denda hilang">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </div>
    @endif

    <!-- HASIL PENGEMBALIAN -->
    @if($peminjaman->pengembalian)
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h6 class="fw-semibold mb-3">Data Pengembalian</h6>

                <div class="row gy-3 mb-3">

                    <div class="col-md-4">
                        <label class="form-label small text-muted">Tanggal Dikembalikan</label>
                        <div>{{ \Carbon\Carbon::parse($peminjaman->pengembalian->tgl_dikembalikan)->format('d M Y') }}
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
                                <td>{{ $item->alat->nama_alat }}</td>
                                <td>{{ $item->qty }}</td>
                                <td>
                                    @php
                                    $color = $item->kondisi == 'baik' ? 'success' : ($item->kondisi == 'rusak' ?
                                    'warning' : 'danger');
                                    @endphp
                                    <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }}">
                                        {{ ucfirst($item->kondisi) }}
                                    </span>
                                </td>
                                <td>Rp {{ number_format($item->denda,0,',','.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 fw-semibold">
                    Total Denda:
                    <span class="text-danger">
                        Rp {{ number_format($peminjaman->total_denda,0,',','.') }}
                    </span>
                </div>

            </div>
        </div>
    </div>
    @endif

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

@if(!$peminjaman->pengembalian)
<div class="sticky-action-bar">
    <div class="container-fluid d-flex justify-content-end gap-2">

        <button type="button" onclick="confirmSubmit()" class="btn btn-primary btn-sm">
            Konfirmasi Pengembalian
        </button>

    </div>
</div>
@endif

<script>
function formatRupiah(angka) {
    return angka.replace(/\D/g, '')
        .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function parseRupiah(value) {
    return parseInt(value.replace(/\./g, '')) || 0;
}

document.querySelectorAll('.denda-rusak, .denda-hilang').forEach(input => {
    input.addEventListener('input', function() {
        this.value = formatRupiah(this.value);
    });
});

document.querySelectorAll('.row-item').forEach(row => {

    const inputs = row.querySelectorAll('.qty-input');

    inputs.forEach(input => {
        input.addEventListener('input', () => validateRow(row));
    });

});

function validateRow(row) {
    const qtyInputs = row.querySelectorAll('.qty-input');
    const maxQty = parseInt(row.querySelector('.qty-max').innerText);

    let total = 0;
    qtyInputs.forEach(i => total += parseInt(i.value || 0));

    const rusak = row.querySelector('.qty-rusak');
    const hilang = row.querySelector('.qty-hilang');

    const dendaRusak = row.querySelector('.denda-rusak');
    const dendaHilang = row.querySelector('.denda-hilang');

    const errorEl = row.querySelector('.error-msg');

    let error = '';

    if (total !== maxQty) {
        error = 'Total kondisi harus = ' + maxQty;
    }

    if (rusak.value > 0 && parseRupiah(dendaRusak.value) <= 0) {
        error = 'Denda rusak wajib diisi';
    }

    if (hilang.value > 0 && parseRupiah(dendaHilang.value) <= 0) {
        error = 'Denda hilang wajib diisi';
    }

    if (error) {
        row.classList.add('table-danger');
        errorEl.classList.remove('d-none');
        errorEl.innerText = error;
        return false;
    } else {
        row.classList.remove('table-danger');
        errorEl.classList.add('d-none');
        return true;
    }
}

function confirmSubmit() {
    let valid = true;

    document.querySelectorAll('.row-item').forEach(row => {
        if (!validateRow(row)) {
            valid = false;
        }
    });

    if (!valid) {
        Swal.fire('Error', 'Periksa kembali input yang salah', 'error');
        return;
    }

    Swal.fire({
        title: 'Selesaikan Pengembalian?',
        text: "Data tidak bisa diubah setelah disimpan",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, simpan',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('formPengembalian').submit();
        }
    });
}
</script>
@endsection