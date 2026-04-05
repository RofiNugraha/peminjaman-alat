<div class="table-responsive">
    <table class="table table-modern align-middle mb-0">
        <thead>
            <tr>
                <th width="60">No</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Email</th>
                <th width="120">Role</th>
                <th width="140" class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $index => $user)
            <tr>
                <td class="text-muted">
                    {{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}
                </td>

                <td class="fw-semibold">{{ $user->nama }}</td>
                <td>{{ $user->username }}</td>
                <td class="text-muted">{{ $user->email }}</td>

                <td>
                    @php
                    $colors = [
                    'admin' => 'primary',
                    'petugas' => 'success',
                    'peminjam' => 'warning'
                    ];
                    @endphp

                    <span class="badge bg-{{ $colors[$user->role] }} bg-opacity-10 text-{{ $colors[$user->role] }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </td>

                <td class="text-center">
                    <div class="d-flex justify-content-center gap-1">

                        <a href="{{ route('users.show',$user) }}" class="btn btn-sm btn-light border">
                            <i class="bi bi-eye"></i>
                        </a>

                        <a href="{{ $user->role === 'admin' ? '#' : route('users.edit',$user) }}"
                            class="btn btn-sm btn-light border {{ $user->role === 'admin' ? 'disabled' : '' }}">
                            <i class="bi bi-pencil"></i>
                        </a>

                        <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy',$user) }}"
                            method="POST">
                            @csrf @method('DELETE')

                            <button type="button" class="btn btn-sm btn-light border btn-delete"
                                data-id="{{ $user->id }}"
                                {{ in_array($user->role, ['admin','peminjam']) ? 'disabled' : '' }}>
                                <i class="bi bi-trash text-danger"></i>
                            </button>
                        </form>

                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="d-flex flex-wrap justify-content-between align-items-center p-3 border-top">

    <div class="d-flex align-items-center gap-2">
        <span class="small text-muted">Data per halaman</span>
        <select id="per_page" name="per_page" class="form-select form-select-sm w-auto">
            @foreach([5,10,25,50,100] as $size)
            <option value="{{ $size }}" @selected($perPage==$size)>
                {{ $size }}
            </option>
            @endforeach
        </select>
    </div>

    <div>
        {{ $users->links('vendor.pagination.custom') }}
    </div>

</div>