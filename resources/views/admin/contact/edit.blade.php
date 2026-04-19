@extends('layouts.app')

@section('title','Edit Contact')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3>Edit Contact</h3>
        <p class="text-muted">Perbarui data</p>
    </div>

    <a href="{{ route('contact.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="card">
    <div class="card-body">

        <form id="formContact" action="{{ route('contact.update',$contact->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Nama Sekolah</label>
                    <input type="text" name="nama_sekolah" value="{{ $contact->nama_sekolah }}" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ $contact->email }}" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Telepon</label>
                    <input type="text" name="telepon" value="{{ $contact->telepon }}" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Website</label>
                    <input type="text" name="website" value="{{ $contact->website }}" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Instagram</label>
                    <input type="text" name="instagram" value="{{ $contact->instagram }}" class="form-control">
                </div>

                <div class="col-12">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control">{{ $contact->alamat }}</textarea>
                </div>

            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('contact.index') }}" class="btn btn-secondary">Batal</a>
                <button id="btnSubmit" class="btn btn-primary">Simpan Perubahan</button>
            </div>

        </form>

    </div>
</div>

@endsection