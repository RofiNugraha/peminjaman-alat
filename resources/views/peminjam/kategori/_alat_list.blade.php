<div class="row g-4">
    @forelse ($alats as $alat)
    <div class="col-md-3">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body text-center">
                <img src="{{ asset('storage/'.$alat->gambar) }}" class="rounded" width="100"
                    alt="{{ $alat->nama_alat }}">

                <h5 class="fw-semibold mb-2 mt-2">
                    {{ $alat->nama_alat }}
                </h5>

                <p class="text-muted mb-1">
                    Stok: {{ $alat->stok }}
                </p>

                <p class="text-muted mb-2">
                    <span class="badge {{ $alat->kondisi == 'Baik' ? 'bg-success' : 'bg-danger' }}">
                        {{ $alat->kondisi }}
                    </span>
                </p>

                @if ($alat->stok > 0)
                <a href="{{ route('peminjam.peminjaman.create', $alat->id) }}" class="btn btn-primary btn-sm">
                    Ajukan Peminjaman
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
        Tidak ada alat ditemukan
    </div>
    @endforelse
</div>