let debounceTimer = null;

function fetchUsers(url = null) {
    const params = {
        search: document.getElementById('search')?.value ?? '',
        role: document.getElementById('filterRole')?.value ?? '',
        sort_by: document.getElementById('sortBy')?.value ?? 'created_at',
        direction: document.getElementById('direction')?.value ?? 'desc',
        per_page: document.getElementById('per_page')?.value ?? 10,
    };

    const query = new URLSearchParams(params).toString();

    fetch(url ?? `${window.location.pathname}?${query}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.text())
    .then(html => {
        document.getElementById('user-table').innerHTML = html;
        bindEvents();
    });
}

function bindEvents() {
    const perPage = document.getElementById('per_page');
    if (perPage) {
        perPage.onchange = () => fetchUsers();
    }

    document.querySelectorAll('.pagination a').forEach(link => {
        link.onclick = function (e) {
            e.preventDefault();
            fetchUsers(this.getAttribute('href'));
        };
    });
}

document.getElementById('search')?.addEventListener('keyup', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(fetchUsers, 400);
});

['filterRole', 'sortBy', 'direction'].forEach(id => {
    document.getElementById(id)?.addEventListener('change', fetchUsers);
});

document.addEventListener('DOMContentLoaded', () => {
    bindEvents();
});