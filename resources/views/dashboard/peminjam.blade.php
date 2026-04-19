<div class="page-header mb-4">
    <h3 class="mb-1">Dashboard Peminjam</h3>
    <p class="mb-0">Aktivitas peminjaman Anda</p>
</div>

<div class="row g-3 mb-4">

    <div class="col-md-4 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="small text-muted mb-1">Total Peminjaman</div>
                <div class="fs-4 fw-semibold">{{ $totalPinjam ?? 0 }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="small text-muted mb-1">Masih Dipinjam</div>
                <div class="fs-4 fw-semibold">{{ $aktif ?? 0 }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="small text-muted mb-1">Sudah Dikembalikan</div>
                <div class="fs-4 fw-semibold">{{ $selesai ?? 0 }}</div>
            </div>
        </div>
    </div>

</div>