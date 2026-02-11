@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif
@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Gambar</th>
                <th>Nama Alat</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Kondisi</th>
                <th>Denda / Hari</th>
                <th width="140">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($alats as $alat)
            <tr>
                <td>{{ $loop->iteration + ($alats->currentPage() - 1) * $alats->perPage() }}</td>
                <td>
                    <img src="{{ asset('storage/'.$alat->gambar) }}" class="rounded" width="60"
                        alt="{{ $alat->nama_alat }}">
                </td>
                <td>{{ $alat->nama_alat }}</td>
                <td>{{ $alat->kategori->nama_kategori ?? '-' }}</td>
                <td>{{ $alat->stok }}</td>
                <td>
                    <span class="badge
                                    @if($alat->kondisi === 'Baik') bg-success
                                    @elseif($alat->kondisi === 'Rusak') bg-warning
                                    @else bg-danger
                                    @endif">
                        {{ $alat->kondisi }}
                    </span>
                </td>
                <td>Rp {{ number_format($alat->denda_per_hari, 0, ',', '.') }}</td>
                <td>
                    <a href="{{ route('alat.edit', $alat->id) }}" class="btn btn-sm btn-warning">
                        Edit
                    </a>

                    <form action="{{ route('alat.destroy', $alat->id) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Yakin ingin menghapus alat ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center text-muted">
                    Data alat belum tersedia
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
            <option value="{{ $size }}" @selected($perPage==$size)>
                {{ $size }}
            </option>
            @endforeach
        </select>

    </div>

    <div class="mt-4">
        {{ $alats->links('vendor.pagination.custom') }}
    </div>
</div>