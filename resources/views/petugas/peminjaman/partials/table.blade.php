<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Nama Peminjam</th>
                <th>Alat</th>
                <th>Kategori</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
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
                <td>
                    @foreach ($p->items as $item)
                    <div>{{ $item->alat->kategori->nama_kategori }}</div>
                    @endforeach
                </td>
                <td>{{ \Carbon\Carbon::parse($p->tgl_pinjam)->format('d M Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($p->tgl_kembali)->format('d M Y') }}</td>
                <td class="d-flex gap-1">
                    <form action="{{ route('petugas.peminjaman.approve', $p->id) }}" method="POST">
                        @csrf
                        @php
                        $stokCukup = $p->items->every(fn($i) => $i->alat->stok >= $i->qty);
                        @endphp
                        @if ($stokCukup)
                        <button class="btn btn-success btn-sm"
                            onclick="return confirm('Yakin ingin menyetujui pengajuan ini?')" @disabled($p->status !==
                            'menunggu')>
                            Setujui
                        </button>
                        @else
                        <button class="btn btn-secondary btn-sm" disabled>Stok Tidak Cukup</button>
                        @endif
                    </form>

                    <form action="{{ route('petugas.peminjaman.reject', $p->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-danger btn-sm"
                            onclick="return confirm('Yakin ingin menolak pengajuan ini?')" @disabled($p->status !==
                            'menunggu')>
                            Tolak
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center text-muted py-3">Tidak ada data peminjaman.</td>
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