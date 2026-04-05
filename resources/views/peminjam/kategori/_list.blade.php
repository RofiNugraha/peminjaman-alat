@if($kategoris->count())
<div class="row g-3">

    @foreach ($kategoris as $kategori)
    <div class="col-xl-3 col-lg-4 col-md-6">
        <div class="card h-100">

            <div class="card-body d-flex flex-column">

                <div class="mb-2">
                    <h6 class="fw-semibold mb-1">{{ $kategori->nama_kategori }}</h6>
                    <p class="text-muted small mb-0">
                        {{ $kategori->keterangan ?? '-' }}
                    </p>
                </div>

                <div class="mt-auto">

                    <div class="mb-3">
                        <span class="badge bg-primary bg-opacity-10 text-primary">
                            {{ $kategori->total_stok }} tersedia
                        </span>
                    </div>

                    <a href="{{ route('peminjam.kategori.show', $kategori->id) }}" class="btn btn-primary w-100 btn-sm">
                        Lihat Alat
                    </a>

                </div>

            </div>

        </div>
    </div>
    @endforeach

</div>
@else
<div class="text-center text-muted py-5">
    Tidak ada kategori ditemukan
</div>
@endif

<div class="d-flex flex-wrap justify-content-between align-items-center mt-4 pt-3 border-top">

    <div class="small text-muted">
        @if($kategoris->count())
        Menampilkan {{ $kategoris->firstItem() }} - {{ $kategoris->lastItem() }} dari {{ $kategoris->total() }} data
        @else
        Menampilkan 0 data
        @endif
    </div>

    <div>
        {{ $kategoris->links('vendor.pagination.custom') }}
    </div>

</div>