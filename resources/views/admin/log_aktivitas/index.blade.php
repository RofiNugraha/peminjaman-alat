@extends('layouts.app')

@section('title', 'Log Aktivitas Sistem')

@section('content')
<div id="mainContent" class="main-content">
    <div class="container-fluid px-4 py-4">
        <h4 class="fw-bold mb-3">Log Aktivitas Sistem</h4>

        <div class="d-flex gap-2 flex-wrap mb-3">
            <input type="text" id="search" class="form-control" placeholder="Cari nama atau aktivitas..."
                style="width:220px">

            <input type="date" id="date_from" class="form-control" style="width:160px">
            <input type="date" id="date_to" class="form-control" style="width:160px">

            <select id="direction" class="form-select" style="width:130px">
                <option value="desc">Terbaru</option>
                <option value="asc">Terlama</option>
            </select>
        </div>

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