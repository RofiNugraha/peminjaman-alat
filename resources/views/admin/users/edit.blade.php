@extends('layouts.app')

@section('content')
<div id="mainContent" class="main-content">
    <div class="container-fluid px-4 py-4">
        <h4>Edit User Role</h4>

        <form method="POST" action="{{ route('users.update', $user) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Nama Lengkap</label>
                <input type="text" class="form-control" value="{{ $user->nama }}" disabled>
            </div>

            <div class="mb-3">
                <label>Username</label>
                <input type="text" class="form-control" value="{{ $user->username }}" disabled>
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" class="form-control" value="{{ $user->email }}" disabled>
            </div>

            <div class="mb-3">
                <label>Role</label>
                <select name="role" class="form-control" required>
                    @foreach(['admin','petugas','peminjam'] as $role)
                    <option value="{{ $role }}" @selected($user->role === $role)>
                        {{ ucfirst($role) }}
                    </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update Role</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection