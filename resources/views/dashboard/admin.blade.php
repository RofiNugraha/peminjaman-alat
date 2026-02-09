<div class="mb-4">
    <h3 class="fw-bold">Dashboard Admin</h3>
    <p class="text-muted mb-0">Ringkasan data sistem</p>
</div>

<div class="row g-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-primary text-white">
            <div class="card-body">
                <h6>Total User</h6>
                <h3>{{ $totalUser ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-success text-white">
            <div class="card-body">
                <h6>Total Alat</h6>
                <h3>{{ $totalAlat ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-warning">
            <div class="card-body">
                <h6>Peminjaman Aktif</h6>
                <h3>{{ $peminjamanAktif ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-danger text-white">
            <div class="card-body">
                <h6>Log Aktivitas</h6>
                <h3>{{ $totalLog ?? 0 }}</h3>
            </div>
        </div>
    </div>
</div>