@if(session('error'))
<div class="alert alert-danger mb-3">
    {{ session('error') }}
</div>
@endif

@if(session('success'))
<div class="alert alert-success mb-3">
    {{ session('success') }}
</div>
@endif

<div class="table-responsive">
    <table class="table table-modern align-middle mb-0">
        <thead>
            <tr>
                <th width="60">No</th>
                <th width="80">Gambar</th>
                <th>Nama Alat</th>
                <th>Kategori</th>
                <th width="100">Stok</th>
                <th width="160">Denda / Hari</th>
                <th width="140" class="text-center">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($alats as $index => $alat)
            <tr>
                <td class="text-muted">
                    {{ ($alats->currentPage() - 1) * $alats->perPage() + $index + 1 }}
                </td>

                <td>
                    <img src="{{ asset('storage/'.$alat->gambar) }}" class="rounded" width="50" height="50"
                        style="object-fit: cover;">
                </td>

                <td class="fw-semibold">
                    {{ $alat->nama_alat }}
                </td>

                <td class="text-muted">
                    {{ $alat->kategoris->nama_kategori ?? '-' }}
                </td>

                <td>
                    <span class="badge bg-light text-dark border">
                        {{ $alat->stok }}
                    </span>
                </td>

                <td class="fw-medium">
                    Rp {{ number_format($alat->denda_per_hari, 0, ',', '.') }}
                </td>

                <td class="text-center">
                    <div class="d-flex justify-content-center gap-1">

                        <a href="{{ route('alat.show',$alat->id) }}" class="btn btn-sm btn-light border">
                            <i class="bi bi-eye"></i>
                        </a>

                        <a href="{{ route('alat.edit',$alat->id) }}" class="btn btn-sm btn-light border">
                            <i class="bi bi-pencil"></i>
                        </a>

                        <form action="{{ route('alat.destroy',$alat->id) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-sm btn-light border">
                                <i class="bi bi-trash text-danger"></i>
                            </button>
                        </form>

                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center text-muted py-4">
                    Data alat belum tersedia
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
        {{ $alats->links('vendor.pagination.custom') }}
    </div>

</div>