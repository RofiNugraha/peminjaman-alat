<div class="page-header mb-4">
    <h3>Dashboard Admin</h3>
    <p>Ringkasan data sistem</p>
</div>

<div class="row g-4">

    <div class="col-md-3 col-sm-6">
        <div class="card dashboard-card d-flex flex-row align-items-stretch">
            <div class="card-indicator indicator-primary"></div>
            <div class="card-body">
                <h6>Total User</h6>
                <h3>{{ $totalUser ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="card dashboard-card d-flex flex-row align-items-stretch">
            <div class="card-indicator indicator-success"></div>
            <div class="card-body">
                <h6>Total Alat</h6>
                <h3>{{ $totalAlat ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="card dashboard-card d-flex flex-row align-items-stretch">
            <div class="card-indicator indicator-warning"></div>
            <div class="card-body">
                <h6>Peminjaman Aktif</h6>
                <h3>{{ $peminjamanAktif ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="card dashboard-card d-flex flex-row align-items-stretch">
            <div class="card-indicator indicator-danger"></div>
            <div class="card-body">
                <h6>Log Aktivitas</h6>
                <h3>{{ $totalLog ?? 0 }}</h3>
            </div>
        </div>
    </div>

</div>