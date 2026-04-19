<div class="card">
    <div class="card-body">

        <h6 class="fw-semibold mb-3">Aktivitas Terbaru</h6>

        <div class="table-responsive">
            <table class="table table-modern align-middle mb-0">
                <thead>
                    <tr>
                        <th>Aktivitas</th>
                        <th width="140">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($aktivitasTerbaru as $log)
                    <tr>
                        <td>{{ $log->aktivitas }}</td>
                        <td class="text-muted small">
                            {{ \Carbon\Carbon::parse($log->waktu)->format('d M Y H:i') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="text-center text-muted">
                            Tidak ada aktivitas
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>