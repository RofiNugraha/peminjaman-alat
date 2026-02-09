<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Alat</th>
                <th>Kategori</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>
                    <center>Status</center>
                </th>
                <th>
                    <center>Aksi</center>
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
                <td>{{ $item->alat->nama_alat }}</td>
                <td>{{ $item->alat->kategori->nama_kategori }}</td>
                <td>{{ \Carbon\Carbon::parse($item->peminjaman->tgl_pinjam)->format('d M Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($item->peminjaman->tgl_kembali)->format('d M Y') }}</td>
                <td>
                    <center><span class="badge bg-{{ match($item->peminjaman->status){
                        'menunggu'=>'warning',
                        'disetujui'=>'success',
                        'ditolak'=>'danger',
                        'dibatalkan'=>'secondary',
                        'dikembalikan'=>'info',
                        'kadaluarsa'=>'dark',
                        default=>'secondary'
                    } }}">
                            {{ ucfirst($item->peminjaman->status) }}
                        </span></center>
                </td>
                <td>
                    <center>
                        @if ($item->peminjaman->status === 'menunggu')
                        <form action="{{ route('peminjam.peminjaman.batal', $item->peminjaman->id) }}" method="POST"
                            class="d-inline" onsubmit="return confirm('Yakin ingin membatalkan pengajuan ini?')">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-sm btn-outline-danger ms-2">
                                Batalkan
                            </button>
                        </form>
                        @else
                        <button class="btn btn-sm btn-outline-secondary ms-2" disabled data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="Tidak dapat dibatalkan karena status sudah {{ $item->peminjaman->status }}">
                            Batalkan
                        </button>
                        @endif
                    </center>
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-muted">
                    Tidak ada data peminjaman
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-between align-items-center mt-3">
    <div>
        <label class="me-2">Data per halaman:</label>
        <select id="per_page" class="form-select d-inline w-auto">
            @foreach([5,10,25,50,100] as $size)
            <option value="{{ $size }}" @selected($perPage==$size)>{{ $size }}</option>
            @endforeach
        </select>
    </div>

    <div>
        {{ $peminjamanItems->links('vendor.pagination.custom') }}
    </div>
</div>