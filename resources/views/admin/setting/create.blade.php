@extends('layouts.app')

@section('title','Tambah Setting')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Tambah Landing Page</h3>
        <p class="text-muted">Buat konfigurasi landing page</p>
    </div>

    <a href="{{ route('setting.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="card">
    <div class="card-body">

        <form id="formSetting" action="{{ route('setting.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Nama Aplikasi</label>
                    <input type="text" name="nama_aplikasi" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Hero Title</label>
                    <input type="text" name="hero_title" class="form-control">
                </div>

                <div class="col-12">
                    <label class="form-label">Hero Subtitle</label>
                    <textarea name="hero_subtitle" class="form-control"></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Logo Utama</label>
                    <input type="file" name="logo_ungu" class="form-control" id="logoUngu">
                    <img id="previewUngu" class="mt-2 d-none" width="100">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Logo Kedua</label>
                    <input type="file" name="logo_putih" class="form-control" id="logoPutih">
                    <img id="previewPutih" class="mt-2 d-none" width="100">
                </div>

            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('setting.index') }}" class="btn btn-secondary">Batal</a>
                <button id="btnSubmit" class="btn btn-primary">Simpan</button>
            </div>

        </form>

    </div>
</div>

@push('scripts')
<script>
function preview(input, target) {
    const file = input.files[0];

    if (file) {
        $(target)
            .attr('src', URL.createObjectURL(file))
            .removeClass('d-none')
            .addClass('rounded shadow-sm');
    }
}

$('#logoUngu').change(function() {
    preview(this, '#previewUngu');
});
$('#logoPutih').change(function() {
    preview(this, '#previewPutih');
});

$('#formSetting').submit(function() {
    $('#btnSubmit').prop('disabled', true).text('Menyimpan...');
});
</script>
@endpush
@endsection