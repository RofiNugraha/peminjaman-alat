<div class="table-responsive">
    <table class="table table-modern align-middle mb-0">
        <thead>
            <tr>
                <th width="60">No</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th width="140">Status</th>
                <th width="100" class="text-center">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($peminjamans as $index => $p)

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

            <tr>
                <td class="text-muted">
                    {{ ($peminjamans->currentPage() - 1) * $peminjamans->perPage() + $index + 1 }}
                </td>

                <td class="fw-semibold">{{ $p->kode_peminjaman }}</td>

                <td>
                    {{ $p->user->profilSiswa->dataSiswa->nama ?? $p->user->nama ?? '-' }}
                </td>

                <td class="text-muted">
                    {{ $p->tgl_pinjam->format('d M Y') }}
                </td>

                <td class="text-muted">
                    {{ $p->tgl_kembali->format('d M Y') }}
                </td>

                <td>
                    <span
                        class="badge bg-{{ $colors[$p->status] ?? 'secondary' }} bg-opacity-10 text-{{ $colors[$p->status] ?? 'secondary' }}">
                        {{ ucfirst($p->status) }}
                    </span>
                </td>

                <td class="text-center">
                    <a href="{{ route('admin.peminjaman.show',$p->id) }}" class="btn btn-sm btn-light border">
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
            <option value="{{ $size }}" @selected($peminjamans->perPage() == $size)>
                {{ $size }}
            </option>
            @endforeach
        </select>
    </div>

    <div>
        {{ $peminjamans->links('vendor.pagination.custom') }}
    </div>

</div>