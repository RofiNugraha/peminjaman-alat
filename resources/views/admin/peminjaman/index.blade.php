@extends('layouts.app')

@section('title', 'Monitoring Peminjaman')

@section('content')
<div id="mainContent" class="main-content">
    <div class="container-fluid px-4 py-4">
        <h4 class="fw-bold mb-3">Monitoring Peminjaman Seluruh Pengguna</h4>

        <ul class="nav nav-tabs mb-3" id="peminjamanTabs">
            <li class="nav-item">
                <button class="nav-link {{ $tab === 'aktif' ? 'active' : '' }}" data-tab="aktif">Aktif</button>
            </li>
            <li class="nav-item">
                <button class="nav-link {{ $tab === 'nonaktif' ? 'active' : '' }}" data-tab="nonaktif">Nonaktif</button>
            </li>
        </ul>

        <div class="d-flex gap-2 flex-wrap mb-3">
            <input type="text" id="search" class="form-control" placeholder="Cari alat atau pengguna..."
                style="width:200px">

            <select id="status" class="form-select" style="width:160px"></select>

            <input type="date" id="date_from" class="form-control" style="width:160px">
            <input type="date" id="date_to" class="form-control" style="width:160px">

            <select id="direction" class="form-select" style="width:120px">
                <option value="desc">Terbaru</option>
                <option value="asc">Terlama</option>
            </select>
        </div>

        <div id="peminjamanTable">
            @include('admin.peminjaman.partials.table', ['peminjamanItems' => $peminjamanItems, 'tab' => $tab])
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let debounceTimer = null;
let currentTab = '{{ $tab }}';

const statusOptions = {
    aktif: [{
            value: '',
            text: 'Semua Status'
        },
        {
            value: 'menunggu',
            text: 'Menunggu'
        },
        {
            value: 'disetujui',
            text: 'Disetujui'
        }
    ],
    nonaktif: [{
            value: '',
            text: 'Semua Status'
        },
        {
            value: 'dibatalkan',
            text: 'Dibatalkan'
        },
        {
            value: 'ditolak',
            text: 'Ditolak'
        },
        {
            value: 'kadaluarsa',
            text: 'Kadaluarsa'
        },
        {
            value: 'dikembalikan',
            text: 'Dikembalikan'
        }
    ]
};

function updateStatusOptions() {
    const select = document.getElementById('status');
    select.innerHTML = '';
    statusOptions[currentTab].forEach(opt => {
        const option = document.createElement('option');
        option.value = opt.value;
        option.textContent = opt.text;
        select.appendChild(option);
    });
}

function getPerPage() {
    return document.getElementById('per_page')?.value ??
        document.querySelector('#peminjamanTable #per_page')?.value ??
        10;
}

function fetchPeminjaman(page = 1) {
    const params = {
        tab: currentTab,
        search: document.getElementById('search').value,
        status: document.getElementById('status').value,
        date_from: document.getElementById('date_from').value,
        date_to: document.getElementById('date_to').value,
        direction: document.getElementById('direction').value,
        per_page: getPerPage(),
        page: page
    };

    const url = `{{ route('admin.peminjaman.index') }}?${new URLSearchParams(params)}`;

    fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.text())
        .then(html => {
            document.getElementById('peminjamanTable').innerHTML = html;
            bindPagination();
        });
}

function bindPagination() {
    document.querySelectorAll('#peminjamanTable .pagination a').forEach(link => {
        link.onclick = function(e) {
            e.preventDefault();
            const page = new URL(this.href).searchParams.get('page') || 1;
            fetchPeminjaman(page);
        };
    });

    document.querySelector('#peminjamanTable #per_page')?.addEventListener('change', () => fetchPeminjaman(1));
}

document.addEventListener('input', e => {
    if (['search', 'status', 'date_from', 'date_to', 'direction'].includes(e.target.id)) {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => fetchPeminjaman(1), 400);
    }
});

document.getElementById('peminjamanTabs').addEventListener('click', e => {
    if (e.target.classList.contains('nav-link')) {
        document.querySelectorAll('#peminjamanTabs .nav-link').forEach(t => t.classList.remove('active'));
        e.target.classList.add('active');
        currentTab = e.target.dataset.tab;
        updateStatusOptions();
        fetchPeminjaman(1);
    }
});

document.addEventListener('DOMContentLoaded', () => {
    updateStatusOptions();
    bindPagination();
});

document.getElementById('per_page')?.addEventListener('change', () => fetchPeminjaman(1));
</script>
@endpush