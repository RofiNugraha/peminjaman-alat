<div class="table-responsive">
    <table class="table table-modern align-middle mb-0">
        <thead>
            <tr>
                <th width="60">No</th>
                <th>Pengguna</th>
                <th width="120">Role</th>
                <th>Aktivitas</th>
                <th width="180">Waktu</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($logs as $index => $log)
            <tr>
                <td class="text-muted">
                    {{ ($logs->currentPage() - 1) * $logs->perPage() + $index + 1 }}
                </td>

                <td class="fw-semibold">
                    {{ $log->users->nama ?? '-' }}
                </td>

                <td>
                    @php
                    $colors = [
                    'admin' => 'primary',
                    'petugas' => 'success',
                    'peminjam' => 'warning'
                    ];
                    $role = $log->users->role ?? null;
                    @endphp

                    @if($role)
                    <span
                        class="badge bg-{{ $colors[$role] ?? 'secondary' }} bg-opacity-10 text-{{ $colors[$role] ?? 'secondary' }}">
                        {{ ucfirst($role) }}
                    </span>
                    @else
                    <span class="text-muted">-</span>
                    @endif
                </td>

                <td>
                    {{ $log->aktivitas }}
                </td>

                <td class="text-muted">
                    {{ \Carbon\Carbon::parse($log->waktu)->format('d M Y H:i') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center text-muted py-4">
                    Tidak ada aktivitas yang tercatat
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex flex-wrap justify-content-between align-items-center p-3 border-top">

    <div class="d-flex align-items-center gap-2">
        <span class="small text-muted">Data per halaman</span>
        <select id="per_page" class="form-select form-select-sm w-auto">
            @foreach([5,10,25,50,100] as $size)
            <option value="{{ $size }}" @selected($logs->perPage() == $size)>
                {{ $size }}
            </option>
            @endforeach
        </select>
    </div>

    <div>
        {{ $logs->links('vendor.pagination.custom') }}
    </div>

</div>