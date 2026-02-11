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
                <th>Nama Kategori</th>
                <th>Keterangan</th>
                <th width="140">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($kategoris as $i => $kategori)
            <tr>
                <td>{{ $loop->iteration + ($kategoris->currentPage() - 1) * $kategoris->perPage() }}
                </td>
                <td>{{ $kategori->nama_kategori }}</td>
                <td>{{ $kategori->keterangan ?? '-' }}</td>
                <td>
                    <a href="{{ route('kategori.edit', $kategori->id) }}" class="btn btn-sm btn-warning">
                        Edit
                    </a>

                    <form action="{{ route('kategori.destroy', $kategori->id) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
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
                    Data kategori belum tersedia
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

    <div>
        {{ $kategoris->links('vendor.pagination.custom') }}
    </div>
</div>