let debounceTimer;

function loadData(url = null) {
    let search = document.getElementById('search')?.value ?? '';
    let status = document.getElementById('status')?.value ?? '';
    let tahun = document.getElementById('tahun_ajaran')?.value ?? '';
    let perPage = document.getElementById('per_page')?.value ?? 10;
    let order = document.getElementById('order')?.value ?? 'desc';

    let params = new URLSearchParams({
        search: search,
        status: status,
        tahun_ajaran: tahun,
        per_page: perPage,
        order: order,
    });

    let fullUrl = url ?? `?${params.toString()}`;

    fetch(fullUrl, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.text())
    .then(html => {
        document.getElementById('siswa-table').innerHTML = html;
    });
}

document.getElementById('search').addEventListener('keyup', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => loadData(), 400);
});

document.getElementById('status').addEventListener('change', () => loadData());

document.getElementById('order').addEventListener('change', () => loadData());

document.addEventListener('change', function(e) {
    if (e.target.id === 'per_page') {
        loadData();
    }
});

document.addEventListener('click', function(e) {
    const link = e.target.closest('.pagination a');

    if (link) {
        e.preventDefault();

        const url = link.getAttribute('href');
        if (url) {
            loadData(url);
        }
    }
});

const mode = document.getElementById('mode');
const tahun = document.getElementById('tahun_ajaran');

function toggleTahunAjaran() {
    if (mode.value === 'replace') {
        tahun.required = true;
    } else {
        tahun.required = false;
    }
}

mode.addEventListener('change', toggleTahunAjaran);
toggleTahunAjaran();