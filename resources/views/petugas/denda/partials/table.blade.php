<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Alat</th>
                <th>Total Denda</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php
            $no = ($peminjamans->currentPage() - 1) * $peminjamans->perPage() + 1;
            @endphp

            @forelse ($peminjamans as $p)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $p->user->nama }}</td>
                <td>
                    @foreach ($p->items as $item)
                    <div>{{ $item->alat->nama_alat }} ({{ $item->qty }})</div>
                    @endforeach
                </td>
                <td>
                    <strong>Rp {{ number_format($p->total_denda) }}</strong>
                </td>
                <td>
                    @if ($p->status_denda === 'belum')
                    <span class="badge bg-danger">Belum Dibayar</span>
                    @elseif ($p->status_denda === 'lunas')
                    <span class="badge bg-success">Lunas</span>
                    @else
                    <span class="badge bg-secondary">Tidak Ada</span>
                    @endif
                </td>
                <td class="d-flex gap-1">
                    @if ($p->status_denda !== 'lunas')
                    <form action="{{ route('petugas.denda.ingatkan', $p->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-warning btn-sm"
                            onclick="return confirm('Kirim pengingat denda ke peminjam?')">
                            Ingatkan
                        </button>
                    </form>

                    <form action="{{ route('petugas.denda.lunas', $p->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-success btn-sm" onclick="return confirm('Tandai denda sebagai lunas?')">
                            Tandai Lunas
                        </button>
                    </form>
                    @else
                    <span class="text-muted">Selesai</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-muted py-3">
                    Tidak ada data denda.
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