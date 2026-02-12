<div class="table-responsive">
    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Alat</th>
                <th>Tanggal Pinjam</th>
                <th>Jatuh Tempo</th>
                <th>Hari Telat</th>
                <th>Total Denda</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php
            $no = ($peminjamans->currentPage() - 1) * $peminjamans->perPage() + 1;
            @endphp

            @forelse ($peminjamans as $p)
            <tr>
                <td>{{ $no++ }}</td>

                <td>
                    @foreach ($p->items as $item)
                    <div>{{ $item->alat->nama_alat }} ({{ $item->qty }})</div>
                    @endforeach
                </td>

                <td>{{ \Carbon\Carbon::parse($p->tgl_pinjam)->format('d M Y') }}</td>

                <td>{{ \Carbon\Carbon::parse($p->tgl_kembali)->format('d M Y') }}</td>

                <td>
                    {{ optional($p->pengembalian)->hari_telat ?? 0 }} hari
                </td>

                <td>
                    <strong class="text-danger">
                        Rp {{ number_format($p->total_denda) }}
                    </strong>
                </td>

                <td>
                    @if ($p->status_denda === 'lunas')
                    <span class="badge bg-success">Lunas</span>
                    @else
                    <span class="badge bg-danger">Belum Dibayar</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center text-muted py-3">
                    Anda tidak memiliki denda 🎉
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-between align-items-center mt-3 flex-wrap">
    <div class="d-flex align-items-center gap-2">
        <label>Data per halaman:</label>
        <select id="per_page" class="form-select w-auto">
            @foreach([5,10,25,50,100] as $size)
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