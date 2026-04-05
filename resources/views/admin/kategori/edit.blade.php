@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
<div class="page-header mb-4">
    <h3 class="mb-1">Edit Kategori</h3>
    <p class="mb-0">Perbarui informasi kategori dengan benar</p>
</div>

@if ($errors->any())
<div class="alert alert-danger mb-3">
    <ul class="mb-0 ps-3">
        @foreach ($errors->all() as $error)
        <li class="small">{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="card">
    <div class="card-body">

        <form id="formKategori" method="POST" action="{{ route('kategori.update', $kategori) }}">
            @csrf
            @method('PUT')

            <div class="row g-3">

                <div class="col-12">
                    <label class="form-label">Nama Kategori</label>
                    <input type="text" name="nama_kategori"
                        class="form-control @error('nama_kategori') is-invalid @enderror"
                        value="{{ old('nama_kategori', $kategori->nama_kategori) }}" placeholder="Contoh: Elektronik"
                        required>

                    <div class="form-text">
                        Maksimal 100 karakter dan harus unik.
                    </div>

                    @error('nama_kategori')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" rows="3" class="form-control @error('keterangan') is-invalid @enderror"
                        placeholder="Deskripsi singkat kategori (opsional)">{{ old('keterangan', $kategori->keterangan) }}</textarea>

                    @error('keterangan')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="d-flex flex-wrap justify-content-between align-items-center mt-4 gap-2">
                <a href="{{ route('kategori.index') }}" class="btn btn-secondary">
                    Kembali
                </a>

                <button id="btnSubmit" class="btn btn-primary">
                    Simpan Perubahan
                </button>
            </div>

        </form>

    </div>
</div>
</div>

@push('scripts')
<script>
$('#formKategori').submit(function() {
    $('#btnSubmit')
        .prop('disabled', true)
        .html('Menyimpan...');
});
</script>
@endpush
@endsection