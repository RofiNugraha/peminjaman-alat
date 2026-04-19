<div class="table-responsive">
    <table class="table table-modern align-middle mb-0">
        <thead>
            <tr>
                <th width="60">No</th>
                <th>Nama Kategori</th>
                <th>Keterangan</th>
                <th width="120" class="text-center">Jumlah Alat</th>
                <th width="140" class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($kategoris as $index => $kategori)
            <tr>
                <td class="text-muted">
                    {{ ($kategoris->currentPage() - 1) * $kategoris->perPage() + $index + 1 }}
                </td>

                <td class="fw-semibold">
                    {{ $kategori->nama_kategori }}
                </td>

                <td class="text-muted">
                    {{ $kategori->keterangan ?? '-' }}
                </td>

                <td class="text-center">
                    <span class="badge bg-primary bg-opacity-10 text-primary">
                        {{ $kategori->alats_count }}
                    </span>
                </td>

                <td class="text-center">
                    <div class="d-flex justify-content-center gap-1">

                        <a href="{{ route('kategori.show',$kategori->id) }}" class="btn btn-sm btn-light border">
                            <i class="bi bi-eye"></i>
                        </a>

                        <a href="{{ route('kategori.edit', $kategori->id) }}" class="btn btn-sm btn-light border">
                            <i class="bi bi-pencil"></i>
                        </a>

                        <button class="btn btn-sm btn-light border btn-delete"
                            data-url="{{ route('kategori.destroy', $kategori->id) }}">
                            <i class="bi bi-trash text-danger"></i>
                        </button>

                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center text-muted py-4">
                    Data kategori belum tersedia
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