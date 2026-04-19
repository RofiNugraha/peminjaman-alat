<div class="table-responsive">
    <table class="table table-modern align-middle mb-0">
        <thead>
            <tr>
                <th width="60">No</th>
                <th>Kode</th>
                <th>Peminjam</th>
                <th>Alat</th>
                <th width="150">Total Denda</th>
                <th width="140">Status</th>
                <th width="120" class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($peminjamans as $index => $p)
            <tr>
                <td class="text-muted">
                    {{ ($peminjamans->currentPage() - 1) * $peminjamans->perPage() + $index + 1 }}
                </td>

                <td class="fw-semibold">{{ $p->kode_peminjaman }}</td>

                <td>{{ $p->user->profilSiswa->dataSiswa->nama ?? $p->user->nama ?? '-' }}</td>

                <td class="small text-muted">
                    @foreach ($p->items as $item)
                    <div>{{ $item->alat->nama_alat }} ({{ $item->qty }})</div>
                    @endforeach
                </td>

                <td class="fw-semibold text-danger">
                    Rp {{ number_format($p->total_denda, 0, ',', '.') }}
                </td>

                <td>
                    @php
                    $colors = [
                    'belum' => 'danger',
                    'lunas' => 'success',
                    'tidak_ada' => 'secondary'
                    ];
                    @endphp

                    <span
                        class="badge bg-{{ $colors[$p->status_denda] }} bg-opacity-10 text-{{ $colors[$p->status_denda] }}">
                        {{ $p->status_denda === 'belum' ? 'Belum Dibayar' : ucfirst($p->status_denda) }}
                    </span>
                </td>

                <td class="text-center">
                    <div class="d-flex justify-content-center">
                        <a href="{{ route('admin.denda.show', $p->id) }}" class="btn btn-sm btn-light border">
                            <i class="bi bi-eye"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @endforeach

            @if($peminjamans->isEmpty())
            <tr>
                <td colspan="7" class="text-center text-muted py-4">
                    Tidak ada data denda
                </td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

<div class="d-flex flex-wrap justify-content-between align-items-center p-3 border-top">
    <div class="d-flex align-items-center gap-2">
        <span class="small text-muted">Data per halaman</span>
        <select id="per_page" class="form-select form-select-sm w-auto">
            @foreach ([5,10,25,50,100] as $size)
            <option value="{{ $size }}" @selected($perPage==$size)>
                {{ $size }}
            </option>
            @endforeach
        </select>
    </div>

    <div>
        {{ $peminjamans->links('vendor.pagination.custom') }}
    </div>
</div>