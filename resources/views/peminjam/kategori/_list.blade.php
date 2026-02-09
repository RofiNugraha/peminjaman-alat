<div class="row g-4">
    @forelse ($kategoris as $kategori)
    <div class="col-md-3">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body text-center">
                <h4 class="fw-semibold mb-2">
                    {{ $kategori->nama_kategori }}
                </h4>

                <p class="text-muted mb-3">
                    {{ $kategori->keterangan }}
                </p>

                <p class="text-muted mb-3">
                    {{ $kategori->total_stok }} alat tersedia
                </p>

                @if ($kategori->total_stok > 0)
                <a href="{{ route('peminjam.kategori.show', $kategori->id) }}" class="btn btn-primary btn-sm">
                    Lihat Alat
                </a>
                @else
                <button class="btn btn-secondary btn-sm" disabled>
                    Stok Habis
                </button>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center text-muted">
        Tidak ada kategori tersedia
    </div>
    @endforelse
</div>