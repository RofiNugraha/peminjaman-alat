<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Alat</th>
                <th>Tanggal Pinjam</th>
                <th>Jatuh Tempo</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php $no = ($peminjamans->currentPage()-1) * $peminjamans->perPage() + 1; @endphp
            @forelse ($peminjamans as $p)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $p->user->nama }}</td>
                <td>
                    @foreach ($p->items as $item)
                    <div>{{ $item->alat->nama_alat }} ({{ $item->qty }})</div>
                    @endforeach
                </td>
                <td>{{ \Carbon\Carbon::parse($p->tgl_pinjam)->format('d M Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($p->tgl_kembali)->format('d M Y') }}</td>
                <td>
                    <form action="{{ route('petugas.pengembalian.store', $p->id) }}" method="POST"
                        onsubmit="return confirm('Apakah Anda yakin ingin menyelesaikan pengembalian ini?')">
                        @csrf
                        <button class="btn btn-primary btn-sm">Selesai</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-muted py-3">Tidak ada data pengembalian.</td>
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
            <option value="{{ $size }}" @selected($perPage==$size)>{{ $size }}</option>
            @endforeach
        </select>
    </div>
    <div>
        {{ $peminjamans->links('vendor.pagination.custom') }}
    </div>
</div>