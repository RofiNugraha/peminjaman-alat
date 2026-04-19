<div class="page-header mb-4">
    <h3 class="mb-1">Dashboard Petugas</h3>
    <p class="mb-0">Peminjaman dan pengembalian</p>
</div>

<div class="row g-3 mb-4">

    <div class="col-md-4 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="small text-muted mb-1">Menunggu</div>
                <div class="fs-4 fw-semibold">{{ $menunggu ?? 0 }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="small text-muted mb-1">Disetujui</div>
                <div class="fs-4 fw-semibold">{{ $disetujui ?? 0 }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="small text-muted mb-1">Pengembalian Hari Ini</div>
                <div class="fs-4 fw-semibold">{{ $pengembalianHariIni ?? 0 }}</div>
            </div>
        </div>
    </div>

</div>