@extends('layouts.app')

@section('title','Data Siswa')

@section('content')
<div class="page-header mb-4">
    <h3 class="mb-1">Data Siswa</h3>
    <p class="mb-0">Kelola dan sinkronisasi data siswa</p>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if(session()->has('failures'))
<div class="alert alert-danger">
    <strong>Data gagal diimport:</strong>
    <ul class="mb-0 mt-2">
        @foreach(session('failures') as $failure)
        <li>Baris {{ $failure->row() }}: {{ implode(', ', $failure->errors()) }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="card mb-3">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-semibold mb-0">Import Data</h6>

            <a href="{{ asset('template/data_siswa_template.xlsx') }}" class="btn btn-secondary">
                Download Template
            </a>
        </div>

        <div class="alert alert-light border small">
            Gunakan template yang tersedia. Data lama akan diperbarui berdasarkan NISN dan
            siswa yang tidak ada dalam file akan menjadi nonaktif.
        </div>

        <form action="{{ route('data_siswa.import') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small">File Excel</label>
                    <input type="file" name="file" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label small">Tahun Ajaran</label>
                    <input type="text" name="tahun_ajaran" class="form-control" placeholder="Contoh: 2025/2026">
                </div>

                <div class="col-md-3 d-grid">
                    <button class="btn btn-primary">
                        Import Data
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body d-flex flex-wrap justify-content-between align-items-end gap-3">
        <div class="d-flex flex-wrap gap-2 align-items-end">
            <div>
                <label class="form-label small">Cari</label>
                <input id="search" type="text" class="form-control" placeholder="Nama / NISN / kelas">
            </div>

            <div>
                <label class="form-label small">Status</label>
                <select id="status" class="form-select">
                    <option value="">Semua</option>
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>

            <div>
                <label class="form-label small">Urutan</label>
                <select id="order" class="form-select">
                    <option value="desc">Terbaru</option>
                    <option value="asc">Terlama</option>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div id="siswa-table">
            @include('admin.data_siswa.partials.table')
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/data-siswa.js') }}"></script>
@endpush
@endsection