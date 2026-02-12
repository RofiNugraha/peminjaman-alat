@extends('layouts.app')

@section('title', 'Peringatan Denda')

@section('content')
<div id="mainContent" class="main-content">
    <div class="container-fluid px-4 py-4">
        <h4 class="fw-bold mb-3">Peringatan Denda Anda</h4>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body" id="dendaTable">
                @include('peminjam.denda.partials.table', [
                'peminjamans' => $peminjamans,
                'perPage' => $perPage
                ])
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function getPerPage() {
    return document.getElementById('per_page')?.value ?? 10;
}

function fetchDenda(page = 1) {
    const params = {
        per_page: getPerPage(),
        page: page
    };

    const query = new URLSearchParams(params).toString();

    fetch(`{{ route('peminjam.denda.index') }}?${query}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.text())
        .then(html => {
            document.getElementById('dendaTable').innerHTML = html;
            bindPagination();
        });
}

function bindPagination() {
    document.querySelectorAll('#dendaTable .pagination a').forEach(link => {
        link.onclick = function(e) {
            e.preventDefault();
            const page = new URL(this.href).searchParams.get('page');
            fetchDenda(page);
        };
    });

    document.getElementById('per_page')?.addEventListener('change', () => fetchDenda(1));
}

document.addEventListener('DOMContentLoaded', bindPagination);
</script>
@endpush