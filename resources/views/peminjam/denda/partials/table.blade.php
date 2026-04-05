<div class="table-responsive">
    <table class="table table-modern align-middle mb-0">
        <thead>
            <tr>
                <th width="60">No</th>
                <th>Kode</th>
                <th>Alat</th>
                <th>Kondisi</th>
                <th width="120">Terlambat</th>
                <th width="160">Total Denda</th>
                <th width="140">Status</th>
                <th width="100" class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php
            $no = ($peminjamans->currentPage() - 1) * $peminjamans->perPage() + 1;

            $statusColors = [
            'belum' => 'danger',
            'lunas' => 'success',
            'tidak_ada' => 'secondary'
            ];
            @endphp

            @forelse ($peminjamans as $p)
            <tr>
                <td class="text-muted">{{ $no++ }}</td>

                <td>
                    <div class="fw-semibold">{{ $p->kode_peminjaman }}</div>
                    <small class="text-muted">
                        {{ $p->tgl_pinjam->format('d M Y') }}
                    </small>
                </td>

                <td>
                    <div class="small text-muted">
                        @foreach ($p->items as $item)
                        <div>
                            {{ $item->alat->nama_alat }}
                            <span class="text-muted">({{ $item->qty }})</span>
                        </div>
                        @endforeach
                    </div>
                </td>

                <td>
                    @if($p->pengembalian && $p->pengembalian->items->count())
                    @foreach($p->pengembalian->items as $item)
                    <div class="mb-1">
                        <span class="badge bg-{{ 
                                $item->kondisi == 'baik' ? 'success' : 
                                ($item->kondisi == 'rusak' ? 'warning' : 'danger') 
                            }} bg-opacity-10 text-{{ 
                                $item->kondisi == 'baik' ? 'success' : 
                                ($item->kondisi == 'rusak' ? 'warning' : 'danger') 
                            }}">
                            {{ ucfirst($item->kondisi) }}
                        </span>
                    </div>
                    @endforeach
                    @else
                    <span class="text-muted">-</span>
                    @endif
                </td>

                <td>
                    @if($p->pengembalian && $p->pengembalian->hari_telat > 0)
                    <span class="badge bg-warning bg-opacity-10 text-warning">
                        {{ $p->pengembalian->hari_telat }} hari
                    </span>
                    @else
                    <span class="text-muted">-</span>
                    @endif
                </td>

                <td class="fw-semibold text-danger">
                    Rp {{ number_format($p->total_denda, 0, ',', '.') }}
                </td>

                <td>
                    <span
                        class="badge bg-{{ $statusColors[$p->status_denda] }} bg-opacity-10 text-{{ $statusColors[$p->status_denda] }}">
                        {{ $p->status_denda === 'belum' ? 'Belum Dibayar' : ucfirst($p->status_denda) }}
                    </span>
                </td>

                <td class="text-center">
                    <a href="{{ route('peminjam.denda.show', $p->id) }}" class="btn btn-sm btn-light border">
                        <i class="bi bi-eye"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center text-muted py-4">
                    Tidak ada data denda
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