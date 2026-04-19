@extends('layouts.app')

@section('title', 'Testimonial')

@section('content')

<div class="page-header mb-4">
    <h3 class="mb-1">Testimonial</h3>
    <p class="mb-0 text-muted">Kelola testimonial pengguna</p>
</div>

<div class="card mb-3">
    <div class="card-body d-flex flex-wrap justify-content-between align-items-end gap-3">

        <form onsubmit="return false;" class="d-flex flex-wrap gap-2 align-items-end">

            <div>
                <label class="form-label small">Cari</label>
                <input type="text" id="search" class="form-control" placeholder="Nama / pesan">
            </div>

            <div>
                <label class="form-label small">Status</label>
                <select id="status" class="form-select">
                    <option value="">Semua</option>
                    <option value="1">Approve</option>
                    <option value="0">Pending</option>
                </select>
            </div>

        </form>

    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div id="table-data">
            @include('admin.testimonial.partials.table')
        </div>
    </div>
</div>

@push('scripts')
<script>
function loadData(page = 1) {
    $.ajax({
        url: "{{ route('testimonial.index') }}",
        type: "GET",
        data: {
            search: $('#search').val(),
            status: $('#status').val(),
            per_page: $('#per_page').val(),
            page: page
        },
        success: function(res) {
            $('#table-data').html(res);
        }
    });
}

// debounce search
let typing;
$(document).on('keyup', '#search', function() {
    clearTimeout(typing);
    typing = setTimeout(() => loadData(), 300);
});

$(document).on('change', '#status, #per_page', function() {
    loadData();
});

// pagination
$(document).on('click', '.pagination a', function(e) {
    e.preventDefault();
    const page = new URL(this.href).searchParams.get('page');
    loadData(page);
});

// DELETE
$(document).on('submit', '.delete-form', function(e) {
    e.preventDefault();
    let form = this;

    Swal.fire({
        title: 'Hapus testimonial?',
        text: 'Tidak bisa dikembalikan',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Hapus'
    }).then((result) => {
        if (result.isConfirmed) form.submit();
    });
});

// TOGGLE APPROVE
$(document).on('click', '.btn-approve', function() {

    let id = $(this).data('id');

    Swal.fire({
        title: 'Ubah status?',
        text: 'Status testimonial akan diperbarui',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, ubah',
        cancelButtonText: 'Batal'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: `/admin/testimonial/${id}`,
                type: 'POST',
                data: {
                    _method: 'PUT',
                    _token: '{{ csrf_token() }}'
                },
                success: function(res) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Status diperbarui',
                        confirmButtonText: 'OK'
                    });

                    loadData();
                },
                error: function(xhr) {
                    console.log(xhr.responseText);

                    Swal.fire('Error', 'Gagal update', 'error');
                }
            });

        }

    });

});
</script>
@endpush

@endsection