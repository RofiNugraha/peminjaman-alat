@extends('layouts.app')

@section('title', 'Setting')

@section('content')

<div class="page-header mb-4">
    <h3 class="mb-1">Landing Page Settings</h3>
    <p class="mb-0 text-muted">Kelola tampilan landing page</p>
</div>

@if(session('success'))
<div class="alert alert-success mb-3">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger mb-3">
    {{ session('error') }}
</div>
@endif

@php
$settingExists = \App\Models\Setting::exists();
@endphp

@if(!$settingExists)
<div class="card mb-3">
    <div class="card-body d-flex justify-content-between align-items-end">

        <a href="{{ route('setting.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i>
            Tambah Setting
        </a>

    </div>
</div>
@endif

<div class="card">
    <div class="card-body p-0">
        <div id="table-data">
            @include('admin.setting.partials.table')
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {

    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {

            let form = this.closest('form');

            Swal.fire({
                title: 'Yakin hapus?',
                text: "Data tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4F46E5',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });

        });
    });

});
</script>
@endsection