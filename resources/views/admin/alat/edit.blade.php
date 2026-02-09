@extends('layouts.app')

@section('title', 'Edit Alat')

@section('content')
<div id="mainContent" class="main-content">
    <div class="container-fluid px-4 py-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Edit Alat</h5>

                <form action="{{ route('alat.update', $alat->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="id_kategori" class="form-select">
                            @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori->id }}"
                                {{ $alat->id_kategori == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Alat</label>
                        <input type="text" name="nama_alat" value="{{ $alat->nama_alat }}" class="form-control">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stok</label>
                            <input type="number" name="stok" min="0" value="{{ $alat->stok }}" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kondisi</label>
                            <select name="kondisi" class="form-select">
                                <option value="Baik" {{ $alat->kondisi === 'Baik' ? 'selected' : '' }}>Baik</option>
                                <option value="Rusak" {{ $alat->kondisi === 'Rusak' ? 'selected' : '' }}>Rusak</option>
                                <option value="Hilang" {{ $alat->kondisi === 'Hilang' ? 'selected' : '' }}>Hilang
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gambar</label>
                        <input type="file" name="gambar" class="form-control">
                        <small class="text-muted">Kosongkan jika tidak ingin mengganti</small>

                        <div class="mt-2">
                            <img src="{{ asset('storage/'.$alat->gambar) }}" width="120" class="rounded">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('alat.index') }}" class="btn btn-secondary">Kembali</a>
                        <button class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection