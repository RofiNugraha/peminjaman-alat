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
                        <input type="text" name="nama_alat" value="{{ old('nama_alat', $alat->nama_alat) }}"
                            class="form-control @error('nama_alat') is-invalid @enderror">
                        @error('nama_alat')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stok</label>
                            <input type="number" name="stok" min="0" value="{{ old('stok', $alat->stok) }}"
                                class="form-control @error('stok') is-invalid @enderror">
                            @error('stok')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kondisi</label>
                            <select name="kondisi" class="form-select @error('kondisi') is-invalid @enderror">
                                <option value="Baik" {{ old('kondisi', $alat->kondisi) === 'Baik' ? 'selected' : '' }}>
                                    Baik</option>
                                <option value="Rusak"
                                    {{ old('kondisi', $alat->kondisi) === 'Rusak' ? 'selected' : '' }}>Rusak</option>
                                <option value="Hilang"
                                    {{ old('kondisi', $alat->kondisi) === 'Hilang' ? 'selected' : '' }}>Hilang</option>
                            </select>
                            @error('kondisi')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Denda per Hari (Rp)</label>
                            <input type="number" name="denda_per_hari" min="0"
                                value="{{ old('denda_per_hari', $alat->denda_per_hari) }}"
                                class="form-control @error('denda_per_hari') is-invalid @enderror">
                            <small class="text-muted">
                                Denda keterlambatan per hari.
                            </small>
                            @error('denda_per_hari')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
<script>
document.querySelector('[name="kondisi"]').addEventListener('change', function() {
    const stok = document.querySelector('[name="stok"]');
    if (this.value !== 'Baik') {
        stok.value = 0;
        stok.setAttribute('readonly', true);
    } else {
        stok.removeAttribute('readonly');
    }
});
</script>
@endsection