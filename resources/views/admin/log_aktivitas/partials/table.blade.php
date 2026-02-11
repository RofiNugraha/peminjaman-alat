<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Pengguna</th>
                <th>Role</th>
                <th>Aktivitas</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            @php
            $no = ($logs->currentPage() - 1) * $logs->perPage() + 1;
            @endphp

            @forelse ($logs as $log)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $log->user->nama ?? '-' }}</td>
                <td>{{ ucfirst($log->user->role ?? '-') }}</td>
                <td>{{ $log->aktivitas }}</td>
                <td>{{ \Carbon\Carbon::parse($log->waktu)->format('d M Y H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center text-muted py-3">
                    Tidak ada aktivitas yang tercatat.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-between align-items-center mt-3 flex-wrap">
    <div class="d-flex align-items-center gap-2">
        <label for="per_page" class="mb-0">Data per halaman:</label>
        <select id="per_page" class="form-select w-auto">
            @foreach([5,10,25,50,100] as $size)
            <option value="{{ $size }}" @selected($logs->perPage() == $size)>{{ $size }}</option>
            @endforeach
        </select>
    </div>

    <div>
        {{ $logs->links('vendor.pagination.custom') }}
    </div>
</div>