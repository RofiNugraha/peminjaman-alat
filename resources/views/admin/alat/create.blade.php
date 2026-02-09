@extends('layouts.app')

@section('title', 'Tambah Alat')

@section('content')
<div id="mainContent" class="main-content">
    <div class="container-fluid px-4 py-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Tambah Alat</h5>

                <form action="{{ route('alat.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="id_kategori" class="form-select @error('id_kategori') is-invalid @enderror">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori->id }}"
                                {{ old('id_kategori') == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                            @endforeach
                        </select>
                        @error('id_kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Alat</label>
                        <input type="text" name="nama_alat" value="{{ old('nama_alat') }}"
                            class="form-control @error('nama_alat') is-invalid @enderror">
                        <small class="text-muted">
                            Maksimal 100 karakter.
                        </small>
                        @error('nama_alat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stok</label>
                            <input type="number" name="stok" min="0" value="{{ old('stok') }}"
                                class="form-control @error('stok') is-invalid @enderror">
                            @error('stok') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kondisi</label>
                            <select name="kondisi" class="form-select @error('kondisi') is-invalid @enderror">
                                <option value="">-- Pilih Kondisi --</option>
                                <option value="Baik">Baik</option>
                                <option value="Rusak">Rusak</option>
                                <option value="Hilang">Hilang</option>
                            </select>
                            @error('kondisi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gambar Alat</label>
                        <input type="file" name="gambar" class="form-control @error('gambar') is-invalid @enderror"
                            accept=".jpg,.jpeg,.png">

                        <small class="text-muted">
                            Format yang diperbolehkan: <strong>JPG, JPEG, PNG</strong>.
                            Ukuran maksimal: <strong>2 MB</strong>.
                        </small>

                        @error('gambar')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('alat.index') }}" class="btn btn-secondary">Kembali</a>
                        <button class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection