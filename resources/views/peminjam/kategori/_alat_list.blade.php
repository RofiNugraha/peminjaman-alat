<div class="row g-3">

    @foreach ($alats as $alat)
    <div class="col-xl-3 col-lg-4 col-md-6">
        <div class="card h-100">

            <div class="text-center pt-3">
                <img src="{{ asset('storage/'.$alat->gambar) }}" class="rounded-circle" width="80" height="80"
                    style="object-fit:cover;">
            </div>

            <div class="card-body d-flex flex-column text-center">

                <h6 class="fw-semibold mb-1">{{ $alat->nama_alat }}</h6>

                <div class="small text-muted mb-3">
                    Stok: {{ $alat->stok }} <br>
                    Denda: Rp {{ number_format($alat->denda_per_hari) }}/hari
                </div>

                <div class="mt-auto">
                    @if ($alat->stok > 0)
                    <a href="{{ route('peminjam.peminjaman.create', $alat->id) }}" class="btn btn-primary w-100 btn-sm">
                        Pinjam
                    </a>
                    @else
                    <button class="btn btn-secondary w-100 btn-sm" disabled>
                        Stok Habis
                    </button>
                    @endif
                </div>

            </div>

        </div>
    </div>
    @endforeach

</div>

<div class="d-flex flex-wrap justify-content-between align-items-center mt-4 pt-3 border-top">

    <div class="small text-muted">
        @if($alats->count())
        Menampilkan {{ $alats->firstItem() }} - {{ $alats->lastItem() }} dari {{ $alats->total() }} data
        @else
        Menampilkan 0 data
        @endif
    </div>

    <div>
        {{ $alats->links('vendor.pagination.custom') }}
    </div>

</div>