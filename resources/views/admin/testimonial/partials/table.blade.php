<div class="table-responsive">
    <table class="table table-modern align-middle mb-0">

        <thead>
            <tr>
                <th width="60">No</th>
                <th>Nama</th>
                <th>Pesan</th>
                <th width="120">Status</th>
                <th width="160" class="text-center">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse($data as $index => $item)
            <tr>

                <td class="text-muted">
                    {{ ($data->currentPage() - 1) * $data->perPage() + $index + 1 }}
                </td>

                <td class="fw-semibold text-truncate" style="max-width: 180px;">
                    {{ $item->nama }}
                </td>

                <td class="text-muted text-truncate" style="max-width: 300px;" title="{{ $item->pesan }}">
                    {{ $item->pesan }}
                </td>

                <td>
                    @php
                    $colors = [
                    1 => 'success',
                    0 => 'warning'
                    ];
                    @endphp

                    <span
                        class="badge bg-{{ $colors[$item->is_approved] }} bg-opacity-10 text-{{ $colors[$item->is_approved] }}">
                        {{ $item->is_approved ? 'Approved' : 'Pending' }}
                    </span>
                </td>

                <td class="text-center">
                    <div class="d-flex justify-content-center gap-1">

                        <a href="{{ route('testimonial.show',$item->id) }}" class="btn btn-sm btn-light border">
                            <i class="bi bi-eye"></i>
                        </a>

                        <button class="btn btn-sm btn-light border btn-approve" data-id="{{ $item->id }}">
                            <i class="bi {{ $item->is_approved ? 'bi-x text-danger':'bi-check text-success' }}"></i>
                        </button>

                        <form action="{{ route('testimonial.destroy',$item->id) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-light border">
                                <i class="bi bi-trash text-danger"></i>
                            </button>
                        </form>

                    </div>
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center text-muted py-4">
                    Data testimonial belum ada
                </td>
            </tr>
            @endforelse
        </tbody>

    </table>
</div>

<div class="d-flex justify-content-between align-items-center p-3 border-top">

    <div class="d-flex align-items-center gap-2">
        <span class="small text-muted">Data per halaman</span>
        <select id="per_page" class="form-select form-select-sm w-auto">
            @foreach([5,10,25,50,100] as $size)
            <option value="{{ $size }}" @selected($perPage==$size)>
                {{ $size }}
            </option>
            @endforeach
        </select>
    </div>

    <div>
        {{ $data->links('vendor.pagination.custom') }}
    </div>

</div>