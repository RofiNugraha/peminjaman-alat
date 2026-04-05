@extends('layouts.app')

@section('title', 'Edit Alat')

@section('content')
<div class="page-header mb-4">
    <h3 class="mb-1">Edit Alat</h3>
    <p class="mb-0 text-muted">Perbarui data alat</p>
</div>

<div class="card">
    <div class="card-body">

        <form id="formAlat" action="{{ route('alat.update',$alat->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Kategori</label>
                    <select name="id_kategori" class="form-select">
                        @foreach ($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" @selected($alat->id_kategori==$kategori->id)>
                            {{ $kategori->nama_kategori }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nama Alat</label>
                    <input type="text" name="nama_alat" value="{{ old('nama_alat',$alat->nama_alat) }}"
                        class="form-control @error('nama_alat') is-invalid @enderror">
                    @error('nama_alat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Stok</label>
                    <input type="number" name="stok" min="0" value="{{ old('stok',$alat->stok) }}" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Denda per Hari</label>
                    <input type="number" name="denda_per_hari" min="0"
                        value="{{ old('denda_per_hari',$alat->denda_per_hari) }}" class="form-control">
                </div>

                <div class="col-12">
                    <label class="form-label">Gambar</label>
                    <input type="file" name="gambar" class="form-control">

                    <div class="mt-3">
                        <img src="{{ asset('storage/'.$alat->gambar) }}" width="120" class="rounded">
                    </div>
                </div>

            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('alat.index') }}" class="btn btn-secondary">Batal</a>
                <button id="btnSubmit" class="btn btn-primary">Simpan Perubahan</button>
            </div>

        </form>

    </div>
</div>

@push('scripts')
<script>
$('#gambar').change(function() {

    const file = this.files[0];

    if (file) {

        $('#preview')
            .attr('src', URL.createObjectURL(file))
            .removeClass('d-none');

    }

});

$('#formAlat').submit(function() {
    $('#btnSubmit')
        .prop('disabled', true)
        .html('Menyimpan...');
});
</script>
@endpush
@endsection