@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Detail Alat</h3>
        <p class="text-muted mb-0">Informasi lengkap alat</p>
    </div>

    <a href="{{ route('alat.index') }}" class="btn btn-secondary">
        Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">

        <div class="row g-4">

            <div class="col-md-4 text-center">
                <img src="{{ asset('storage/'.$alat->gambar) }}" class="rounded shadow-sm" width="200">
            </div>

            <div class="col-md-8">
                <div class="row gy-3">

                    <div class="col-md-6">
                        <label class="form-label small text-muted">Kode</label>
                        <div class="fw-medium">{{ $alat->kode_alat }}</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small text-muted">Nama</label>
                        <div class="fw-medium">{{ $alat->nama_alat }}</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small text-muted">Kategori</label>
                        <div>{{ $alat->kategoris->nama_kategori }}</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small text-muted">Stok</label>
                        <div>{{ $alat->stok }}</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small text-muted">Denda</label>
                        <div>Rp {{ number_format($alat->denda_per_hari) }}</div>
                    </div>

                </div>
            </div>

        </div>

    </div>
</div>
@endsection