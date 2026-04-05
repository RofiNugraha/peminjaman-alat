@extends('layouts.app')

@section('title', 'Monitoring Peminjaman')

@section('content')
<div class="page-header mb-4">
    <h3 class="mb-1">Monitoring Peminjaman</h3>
    <p class="mb-0">Pantau seluruh aktivitas peminjaman pengguna</p>
</div>

<ul class="nav nav-tabs mb-3" id="peminjamanTabs">
    <li class="nav-item">
        <button class="nav-link {{ $tab === 'aktif' ? 'active' : '' }}" data-tab="aktif">Aktif</button>
    </li>
    <li class="nav-item">
        <button class="nav-link {{ $tab === 'nonaktif' ? 'active' : '' }}" data-tab="nonaktif">Nonaktif</button>
    </li>
</ul>

<div class="card mb-3">
    <div class="card-body">

        <div class="d-flex flex-wrap gap-3 align-items-end">

            <div>
                <label class="form-label small">Cari</label>
                <input type="text" id="search" class="form-control" placeholder="Kode / nama pengguna">
            </div>

            <div>
                <label class="form-label small">Status</label>
                <select id="status" class="form-select"></select>
            </div>

            <div>
                <label class="form-label small">Dari Tanggal</label>
                <input type="date" id="date_from" class="form-control">
            </div>

            <div>
                <label class="form-label small">Sampai</label>
                <input type="date" id="date_to" class="form-control">
            </div>

            <div>
                <label class="form-label small">Urutan</label>
                <select id="direction" class="form-select">
                    <option value="desc">Terbaru</option>
                    <option value="asc">Terlama</option>
                </select>
            </div>

        </div>

    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div id="peminjamanTable">
            @include('admin.peminjaman.partials.table')
        </div>
    </div>
</div>

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
@endsection