<h3>Dashboard Petugas</h3>
<p class="text-muted">Peminjaman & Pengembalian</p>

<div class="row">
    <div class="col-md-4">
        <div class="card border-primary mb-3">
            <div class="card-body">
                <h5>Peminjaman Menunggu</h5>
                <h2>{{ $menunggu ?? 0 }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-success mb-3">
            <div class="card-body">
                <h5>Peminjaman Disetujui</h5>
                <h2>{{ $disetujui ?? 0 }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-secondary mb-3">
            <div class="card-body">
                <h5>Pengembalian Hari Ini</h5>
                <h2>{{ $pengembalianHariIni ?? 0 }}</h2>
            </div>
        </div>
    </div>
</div>