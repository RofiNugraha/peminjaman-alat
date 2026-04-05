let debounceTimer = null;

function getParams(page = 1) {
    return {
        search: document.getElementById('search')?.value ?? '',
        role: document.getElementById('filterRole')?.value ?? '',
        sort_by: document.getElementById('sortBy')?.value ?? 'created_at',
        direction: document.getElementById('direction')?.value ?? 'desc',
        per_page: document.getElementById('per_page')?.value ?? 10,
        page: page
    };
}

function fetchUsers(page = 1) {
    const params = getParams(page);
    const url = `${window.location.pathname}?${new URLSearchParams(params)}`;

    fetch(url, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.text())
    .then(html => {
        document.getElementById('user-table').innerHTML = html;
        bindEvents();
    });
}

function bindEvents() {
    document.querySelectorAll('.pagination a').forEach(link => {
        link.onclick = function (e) {
            e.preventDefault();

            const page = new URL(this.href).searchParams.get('page');
            fetchUsers(page);
        };
    });

    const perPage = document.getElementById('per_page');
    if (perPage) {
        perPage.onchange = () => fetchUsers();
    }

    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.onclick = function () {
            let id = this.dataset.id;

            Swal.fire({
                title: 'Yakin?',
                text: "Data tidak akan hilang permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Hapus'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        };
    });
}

document.getElementById('search')?.addEventListener('keyup', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => fetchUsers(), 400);
});

['filterRole', 'sortBy', 'direction'].forEach(id => {
    document.getElementById(id)?.addEventListener('change', () => fetchUsers());
});

document.addEventListener('DOMContentLoaded', () => {
    bindEvents();
});