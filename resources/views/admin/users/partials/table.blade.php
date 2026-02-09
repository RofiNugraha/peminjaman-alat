<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th width="140">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $index => $user)
            <tr>
                <td>{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
                <td>{{ $user->nama }}</td>
                <td>{{ $user->username }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <span class="badge bg-secondary">{{ ucfirst($user->role) }}</span>
                </td>
                <td>
                    <a href="{{ route('users.edit',$user) }}" class="btn btn-sm btn-warning">Edit</a>

                    <form action="{{ route('users.destroy',$user) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('Hapus user?')" class="btn btn-sm btn-danger">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-between align-items-center mb-3 mt-3">
    <form id="perPageForm" class="d-inline">
        <label class="me-2">Data per halaman:</label>
        <select name="per_page" id="per_page" class="form-select d-inline w-auto">
            @foreach([5,10,25,50,100] as $size)
            <option value="{{ $size }}" @selected($perPage==$size)>{{ $size }}</option>
            @endforeach
        </select>
    </form>

    <div>
        {{ $users->links('vendor.pagination.custom-bootstrap') }}
    </div>
</div>