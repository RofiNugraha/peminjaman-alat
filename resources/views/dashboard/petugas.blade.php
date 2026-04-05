<div class="page-header mb-4">
    <h3>Dashboard Petugas</h3>
    <p>Peminjaman & pengembalian</p>
</div>

<div class="row g-4">

    <div class="col-md-3 col-sm-6">
        <div class="card dashboard-card d-flex">
            <div class="card-indicator indicator-warning"></div>
            <div class="card-body">
                <h6>Menunggu</h6>
                <h3>{{ $menunggu ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="card dashboard-card d-flex">
            <div class="card-indicator indicator-success"></div>
            <div class="card-body">
                <h6>Disetujui</h6>
                <h3>{{ $disetujui ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="card dashboard-card d-flex">
            <div class="card-indicator indicator-primary"></div>
            <div class="card-body">
                <h6>Pengembalian Hari Ini</h6>
                <h3>{{ $pengembalianHariIni ?? 0 }}</h3>
            </div>
        </div>
    </div>

</div>