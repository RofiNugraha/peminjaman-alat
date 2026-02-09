<h3>Dashboard Peminjam</h3>
<p class="text-muted">Aktivitas peminjaman Anda</p>

<div class="row">
    <div class="col-md-4">
        <div class="card border-info mb-3">
            <div class="card-body">
                <h5>Total Peminjaman</h5>
                <h2>{{ $totalPinjam ?? 0 }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-warning mb-3">
            <div class="card-body">
                <h5>Masih Dipinjam</h5>
                <h2>{{ $aktif ?? 0 }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-success mb-3">
            <div class="card-body">
                <h5>Sudah Dikembalikan</h5>
                <h2>{{ $selesai ?? 0 }}</h2>
            </div>
        </div>
    </div>
</div>