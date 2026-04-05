@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Detail Kategori</h3>
        <p class="text-muted mb-0">Informasi lengkap kategori</p>
    </div>

    <a href="{{ route('kategori.index') }}" class="btn btn-secondary">
        Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">

        <div class="row gy-3">

            <div class="col-md-6">
                <label class="form-label small text-muted">Nama Kategori</label>
                <div class="fw-semibold">{{ $kategori->nama_kategori }}</div>
            </div>

            <div class="col-md-6">
                <label class="form-label small text-muted">Jumlah Alat</label>
                <div>{{ $kategori->alats_count }}</div>
            </div>

            <div class="col-12">
                <label class="form-label small text-muted">Keterangan</label>
                <div>{{ $kategori->keterangan ?? '-' }}</div>
            </div>

        </div>

    </div>
</div>
@endsection