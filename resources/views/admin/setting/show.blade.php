@extends('layouts.app')

@section('title','Detail Setting')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3>Detail Landing Page</h3>
        <p class="text-muted">Informasi lengkap</p>
    </div>

    <a href="{{ route('setting.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="card">
    <div class="card-body">

        <div class="row g-4">

            <div class="col-md-4 text-center">
                <img src="{{ asset('storage/'.$setting->logo_ungu) }}" class="rounded shadow-sm mb-3" width="150">

                <img src="{{ asset('storage/'.$setting->logo_putih) }}" class="rounded shadow-sm" width="150">
            </div>

            <div class="col-md-8">
                <div class="row gy-3">

                    <div class="col-md-6">
                        <label class="form-label small text-muted">Nama Aplikasi</label>
                        <div class="fw-medium">{{ $setting->nama_aplikasi }}</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small text-muted">Hero Title</label>
                        <div>{{ $setting->hero_title }}</div>
                    </div>

                    <div class="col-12">
                        <label class="form-label small text-muted">Subtitle</label>
                        <div>{{ $setting->hero_subtitle }}</div>
                    </div>

                </div>
            </div>

        </div>

    </div>
</div>
@endsection