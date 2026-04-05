<div class="page-header mb-4">
    <h3>Dashboard Peminjam</h3>
    <p>Aktivitas peminjaman Anda</p>
</div>

<div class="row g-4">

    <div class="col-md-3 col-sm-6">
        <div class="card dashboard-card d-flex">
            <div class="card-indicator indicator-success"></div>
            <div class="card-body">
                <h6>Total Peminjaman</h6>
                <h3>{{ $totalPinjam ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="card dashboard-card d-flex">
            <div class="card-indicator indicator-warning"></div>
            <div class="card-body">
                <h6>Masih Dipinjam</h6>
                <h3>{{ $aktif ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="card dashboard-card d-flex">
            <div class="card-indicator indicator-primary"></div>
            <div class="card-body">
                <h6>Sudah Dikembalikan</h6>
                <h3>{{ $selesai ?? 0 }}</h3>
            </div>
        </div>
    </div>

</div>