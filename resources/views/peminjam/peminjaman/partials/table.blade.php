<div class="table-responsive">
    <table class="table table-modern align-middle mb-0">
        <thead>
            <tr>
                <th width="60">No</th>
                <th>Alat</th>
                <th>Kategori</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th width="140">Status</th>
                <th width="100" class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php
            $no = ($peminjamanItems->currentPage()-1) * $peminjamanItems->perPage() + 1;
            @endphp

            @forelse ($peminjamanItems as $item)
            <tr>
                <td class="text-muted">{{ $no++ }}</td>

                <td class="fw-semibold">{{ $item->alat->nama_alat }}</td>

                <td class="text-muted">
                    {{ $item->alat->kategoris->nama_kategori }}
                </td>

                <td>
                    {{ \Carbon\Carbon::parse($item->peminjaman->tgl_pinjam)->format('d M Y') }}
                </td>

                <td>
                    {{ \Carbon\Carbon::parse($item->peminjaman->tgl_kembali)->format('d M Y') }}
                </td>

                <td>
                    @php
                    $colors = [
                    'menunggu' => 'warning',
                    'disetujui' => 'success',
                    'ditolak' => 'danger',
                    'dibatalkan' => 'secondary',
                    'dikembalikan' => 'info',
                    'kadaluarsa' => 'dark'
                    ];
                    @endphp

                    <span
                        class="badge bg-{{ $colors[$item->peminjaman->status] ?? 'secondary' }} bg-opacity-10 text-{{ $colors[$item->peminjaman->status] ?? 'secondary' }}">
                        {{ ucfirst($item->peminjaman->status) }}
                    </span>
                </td>

                <td class="text-center">
                    <a href="{{ route('peminjam.peminjaman.show', $item->peminjaman->id) }}"
                        class="btn btn-sm btn-light border">
                        <i class="bi bi-eye"></i>
                    </a>
                </td>
            </tr>

            @empty
            <tr>
                <td colspan="7" class="text-center text-muted py-4">
                    Tidak ada data peminjaman
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
            <option value="{{ $size }}" @selected($perPage==$size)>{{ $size }}</option>
            @endforeach
        </select>
    </div>

    <div>
        {{ $peminjamanItems->links('vendor.pagination.custom') }}
    </div>

</div>