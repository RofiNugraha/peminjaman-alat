<div class="page-header mb-4">
    <h3 class="mb-1">Dashboard Admin</h3>
    <p class="mb-0">Ringkasan data sistem</p>
</div>

<div class="row g-3 mb-4">

    <div class="col-md-3 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="small text-muted mb-1">Total User</div>
                <div class="fs-4 fw-semibold">{{ $totalUser ?? 0 }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="small text-muted mb-1">Total Alat</div>
                <div class="fs-4 fw-semibold">{{ $totalAlat ?? 0 }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="small text-muted mb-1">Peminjaman Aktif</div>
                <div class="fs-4 fw-semibold">{{ $peminjamanAktif ?? 0 }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="small text-muted mb-1">Total Denda</div>
                <div class="fs-4 fw-semibold">
                    Rp {{ number_format($totalDenda ?? 0, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

</div>

<div class="card mb-4">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-semibold mb-0">Peminjaman per Bulan</h6>
            <span class="small text-muted">Tahun ini</span>
        </div>

        <canvas id="chartPeminjaman" height="90"></canvas>

    </div>
</div>

<div class="row g-4">

    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">

                <h6 class="fw-semibold mb-3">Aktivitas Terbaru</h6>

                <div class="table-responsive">
                    <table class="table table-modern align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Pengguna</th>
                                <th>Aktivitas</th>
                                <th width="120">Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($aktivitasTerbaru as $log)
                            <tr>
                                <td class="fw-medium">{{ $log->users->nama ?? '-' }}</td>
                                <td>{{ $log->aktivitas }}</td>
                                <td class="text-muted small">
                                    {{ \Carbon\Carbon::parse($log->waktu)->format('d M H:i') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">
                                    Tidak ada aktivitas
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">

                <h6 class="fw-semibold mb-3">Alat Terpopuler</h6>

                <div class="table-responsive">
                    <table class="table table-modern align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Nama Alat</th>
                                <th width="100" class="text-end">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topAlat as $alat)
                            <tr>
                                <td class="fw-medium">{{ $alat->nama_alat }}</td>
                                <td class="text-end">
                                    <span class="badge bg-primary bg-opacity-10 text-primary">
                                        {{ $alat->total }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">
                                    Tidak ada data
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function() {

    const ctx = document.getElementById('chartPeminjaman');

    if (!ctx) return;

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($bulanLabel),
            datasets: [{
                data: @json($dataBulanan),
                tension: 0.3,
                fill: true,
                borderWidth: 2
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

});
</script>