@extends('layouts.app')

@section('title','Tambah Contact')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3>Tambah Contact</h3>
        <p class="text-muted">Input data kontak sekolah</p>
    </div>

    <a href="{{ route('contact.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="card">
    <div class="card-body">

        <form id="formContact" action="{{ route('contact.store') }}" method="POST">
            @csrf

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Nama Sekolah</label>
                    <input type="text" name="nama_sekolah" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Telepon</label>
                    <input type="text" name="telepon" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Website</label>
                    <input type="text" name="website" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Instagram</label>
                    <input type="text" name="instagram" class="form-control">
                </div>

                <div class="col-12">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control"></textarea>
                </div>

            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('contact.index') }}" class="btn btn-secondary">Batal</a>
                <button id="btnSubmit" class="btn btn-primary">Simpan</button>
            </div>

        </form>

    </div>
</div>

<script>
$('#formContact').submit(function() {
    $('#btnSubmit').prop('disabled', true).text('Menyimpan...');
});
</script>

@endsection