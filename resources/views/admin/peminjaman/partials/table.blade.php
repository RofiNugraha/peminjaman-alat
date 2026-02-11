<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Nama Pengguna</th>
                <th>Alat</th>
                <th>Kategori</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>
                    <center>Status</center>
                </th>
            </tr>
        </thead>
        <tbody>
            @php
            $no = ($peminjamanItems->currentPage()-1) * $peminjamanItems->perPage() + 1;
            @endphp

            @forelse ($peminjamanItems as $item)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $item->peminjaman->user->nama ?? '-' }}</td>
                <td>{{ $item->alat->nama_alat }}</td>
                <td>{{ $item->alat->kategori->nama_kategori }}</td>
                <td>{{ \Carbon\Carbon::parse($item->peminjaman->tgl_pinjam)->format('d M Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($item->peminjaman->tgl_kembali)->format('d M Y') }}</td>
                <td>
                    <center>
                        <span class="badge bg-{{ match($item->peminjaman->status) {
                                'menunggu' => 'warning',
                                'disetujui' => 'success',
                                'ditolak' => 'danger',
                                'dibatalkan' => 'secondary',
                                'dikembalikan' => 'info',
                                'kadaluarsa' => 'dark',
                                default => 'secondary'
                            } }}">
                            {{ ucfirst($item->peminjaman->status) }}
                        </span>
                    </center>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center text-muted">Tidak ada data peminjaman.</td>
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
            <option value="{{ $size }}" @selected($peminjamanItems->perPage() == $size)>{{ $size }}</option>
            @endforeach
        </select>
    </div>

    <div>
        {{ $peminjamanItems->links('vendor.pagination.custom') }}
    </div>
</div>