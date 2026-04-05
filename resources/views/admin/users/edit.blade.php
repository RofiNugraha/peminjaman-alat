@extends('layouts.app')

@section('content')
<h4 class="mb-3">Edit Role User</h4>
<p class="text-muted mb-4">Ubah hak akses pengguna</p>

<div class="card">
    <div class="card-body">

        <form method="POST" action="{{ route('users.update',$user) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input class="form-control" value="{{ $user->nama }}" disabled>
            </div>

            <div class="mb-3">
                <label class="form-label">Username</label>
                <input class="form-control" value="{{ $user->username }}" disabled>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input class="form-control" value="{{ $user->email }}" disabled>
            </div>

            <div class="mb-4">
                <label class="form-label">Role</label>
                <select name="role" class="form-select">
                    @foreach(['petugas','peminjam'] as $role)
                    <option value="{{ $role }}" @selected($user->role === $role)>
                        {{ ucfirst($role) }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="d-flex gap-2">
                <button class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
            </div>

        </form>

    </div>
</div>

@push('scripts')
<script>
$('#formUser').submit(function() {
    $('#btnSubmit')
        .prop('disabled', true)
        .html('Menyimpan...');
});
</script>
@endpush
@endsection