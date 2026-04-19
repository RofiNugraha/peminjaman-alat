@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3>Detail Testimonial</h3>
        <p class="text-muted">Informasi lengkap</p>
    </div>

    <a href="{{ route('testimonial.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="card">
    <div class="card-body">

        <div class="mb-3">
            <label class="form-label small text-muted">Nama</label>
            <div class="fw-medium">{{ $testimonial->nama }}</div>
        </div>

        <div class="mb-3">
            <label class="form-label small text-muted">Pesan</label>
            <div>{{ $testimonial->pesan }}</div>
        </div>

        <div>
            <label class="form-label small text-muted">Status</label>
            <div>
                <span class="badge {{ $testimonial->is_approved ? 'bg-success':'bg-warning' }}">
                    {{ $testimonial->is_approved ? 'Approve':'Pending' }}
                </span>
            </div>
        </div>

    </div>
</div>

@endsection