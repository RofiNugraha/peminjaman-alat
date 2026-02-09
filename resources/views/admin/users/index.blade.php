@extends('layouts.app')

@section('title','Manajemen User')

@section('content')
<div id="mainContent" class="main-content">
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0">Manajemen User</h4>

            <div class="d-flex flex-wrap align-items-center gap-2">
                <form method="GET" class="d-flex gap-2">
                    <input type="text" id="search" class="form-control" placeholder="Cari nama, username, atau email..."
                        style="width: 280px;" autocomplete="off">

                    <select id="filterRole" name="role" class="form-select" style="width: 120px">
                        <option value="">Role</option>
                        <option value="admin">Admin</option>
                        <option value="petugas">Petugas</option>
                        <option value="peminjam">Peminjam</option>
                    </select>

                    <select id="sortBy" class="form-select" style="width: 160px">
                        <option value="created_at">Tanggal Dibuat</option>
                        <option value="nama">Nama</option>
                        <option value="username">Username</option>
                        <option value="email">Email</option>
                    </select>

                    <select id="direction" class="form-select" style="width: 110px">
                        <option value="desc">Terbaru</option>
                        <option value="asc">Terlama</option>
                    </select>
                </form>

                <a href="{{ route('users.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg"></i> Tambah User
                </a>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="card-body">
                    <div id="user-table">
                        @include('admin.users.partials.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/admin-users.js') }}"></script>
@endpush
@endsection