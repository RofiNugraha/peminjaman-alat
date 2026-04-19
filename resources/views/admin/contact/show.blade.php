@extends('layouts.app')

@section('title','Detail Contact')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3>Detail Contact</h3>
        <p class="text-muted">Informasi lengkap</p>
    </div>

    <a href="{{ route('contact.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="card">
    <div class="card-body">

        <div class="row gy-3">

            <div class="col-md-6">
                <label class="form-label small text-muted">Nama Sekolah</label>
                <div class="fw-medium">{{ $contact->nama_sekolah }}</div>
            </div>

            <div class="col-md-6">
                <label class="form-label small text-muted">Email</label>
                <div>{{ $contact->email }}</div>
            </div>

            <div class="col-md-6">
                <label class="form-label small text-muted">Telepon</label>
                <div>{{ $contact->telepon }}</div>
            </div>

            <div class="col-md-6">
                <label class="form-label small text-muted">Instagram</label>
                <div>{{ $contact->instagram }}</div>
            </div>

            <div class="col-md-6">
                <label class="form-label small text-muted">Website</label>
                <div>{{ $contact->website }}</div>
            </div>

            <div class="col-12">
                <label class="form-label small text-muted">Alamat</label>
                <div>{{ $contact->alamat }}</div>
            </div>

        </div>

    </div>
</div>

@endsection