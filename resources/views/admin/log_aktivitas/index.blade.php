@extends('layouts.app')

@section('title', 'Log Aktivitas Sistem')

@section('content')
<div class="page-header mb-4">
    <h3 class="mb-1">Log Aktivitas Sistem</h3>
    <p class="mb-0">Riwayat aktivitas pengguna dalam sistem</p>
</div>

<div class="card mb-3">
    <div class="card-body">

        <form onsubmit="return false;" class="row g-3 align-items-end">

            <div class="col-md-4">
                <label class="form-label small">Cari</label>
                <input type="text" id="search" class="form-control" placeholder="Nama pengguna atau aktivitas">
            </div>

            <div class="col-md-2">
                <label class="form-label small">Dari Tanggal</label>
                <input type="date" id="date_from" class="form-control">
            </div>

            <div class="col-md-2">
                <label class="form-label small">Sampai Tanggal</label>
                <input type="date" id="date_to" class="form-control">
            </div>

            <div class="col-md-2">
                <label class="form-label small">Urutan</label>
                <select id="direction" class="form-select">
                    <option value="desc">Terbaru</option>
                    <option value="asc">Terlama</option>
                </select>
            </div>

        </form>

    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div id="logTable">
            @include('admin.log_aktivitas.partials.table', ['logs' => $logs])
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let debounceTimer = null;

function getPerPage() {
    return document.getElementById('per_page')?.value ?? 10;
}

function fetchLogs(page = 1) {
    const params = {
        search: document.getElementById('search').value,
        date_from: document.getElementById('date_from').value,
        date_to: document.getElementById('date_to').value,
        direction: document.getElementById('direction').value,
        per_page: getPerPage(),
        page: page
    };

    const url = `{{ route('admin.log_aktivitas.index') }}?${new URLSearchParams(params)}`;

    fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.text())
        .then(html => {
            document.getElementById('logTable').innerHTML = html;
            bindPagination();
        });
}

function bindPagination() {
    document.querySelectorAll('#logTable .pagination a').forEach(link => {
        link.onclick = function(e) {
            e.preventDefault();
            const page = new URL(this.href).searchParams.get('page') || 1;
            fetchLogs(page);
        };
    });

    document.querySelector('#logTable #per_page')?.addEventListener('change', () => fetchLogs(1));
}

document.addEventListener('input', e => {
    if (['search', 'date_from', 'date_to', 'direction'].includes(e.target.id)) {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => fetchLogs(1), 400);
    }
});

document.addEventListener('DOMContentLoaded', bindPagination);
</script>
@endpush