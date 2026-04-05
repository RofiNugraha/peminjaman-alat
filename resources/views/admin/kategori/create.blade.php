@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
<div class="page-header mb-4">
    <h3 class="mb-1">Tambah Kategori</h3>
    <p class="mb-0">Tambahkan kategori baru ke dalam sistem</p>
</div>

@if ($errors->any())
<div class="alert alert-danger mb-3">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="card">
    <div class="card-body">

        <form id="formKategori" method="POST" action="{{ route('kategori.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nama Kategori</label>
                <input type="text" name="nama_kategori"
                    class="form-control @error('nama_kategori') is-invalid @enderror" value="{{ old('nama_kategori') }}"
                    placeholder="Contoh: Elektronik">

                @error('nama_kategori')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3"
                    placeholder="Deskripsi singkat kategori"></textarea>

                @error('keterangan')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button id="btnSubmit" class="btn btn-primary">
                    Simpan
                </button>
                <a href="{{ route('kategori.index') }}" class="btn btn-secondary">
                    Batal
                </a>
            </div>

        </form>

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