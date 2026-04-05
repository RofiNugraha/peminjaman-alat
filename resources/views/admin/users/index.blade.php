@extends('layouts.app')

@section('title','Manajemen User')

@section('content')
<div class="page-header mb-4">
    <h3 class="mb-1">Manajemen User</h3>
    <p class="mb-0">Kelola data pengguna sistem secara terpusat</p>
</div>

<div class="card mb-3">
    <div class="card-body d-flex flex-wrap justify-content-between align-items-end gap-3">

        <form onsubmit="return false;" class="d-flex flex-wrap gap-2 align-items-end">

            <div>
                <label class="form-label small">Cari</label>
                <input type="text" id="search" name="search" class="form-control" placeholder="Nama / username / email"
                    value="{{ request('search') }}">
            </div>

            <div>
                <label class="form-label small">Role</label>
                <select id="filterRole" name="role" class="form-select">
                    <option value="">Semua</option>
                    <option value="admin">Admin</option>
                    <option value="petugas">Petugas</option>
                    <option value="peminjam">Peminjam</option>
                </select>
            </div>

            <div>
                <label class="form-label small">Urutan</label>
                <select id="direction" name="direction" class="form-select">
                    <option value="desc">Terbaru</option>
                    <option value="asc">Terlama</option>
                </select>
            </div>

        </form>

        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i>
            Tambah Petugas
        </a>

    </div>
</div>

@if(session('success'))
<div class="alert alert-success mb-3">
    {{ session('success') }}
</div>
@endif

<div class="card">
    <div class="card-body p-0">
        <div id="user-table">
            @include('admin.users.partials.table')
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/admin-users.js') }}"></script>

<script>
document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', function() {
        let id = this.dataset.id;

        Swal.fire({
            title: 'Yakin?',
            text: "Data tidak akan hilang permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    });
});
</script>
@endpush
@endsection