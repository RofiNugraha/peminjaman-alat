<div class="table-responsive">
    <table class="table table-modern align-middle mb-0">
        <thead>
            <tr>
                <th width="60">No</th>
                <th>Nama Aplikasi</th>
                <th>Hero Title</th>
                <th width="140" class="text-center">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse($settings as $index => $item)
            <tr>

                <td class="text-muted">
                    {{ ($settings->currentPage() - 1) * $settings->perPage() + $index + 1 }}
                </td>

                <td class="fw-semibold text-truncate" style="max-width:200px;">
                    {{ $item->nama_aplikasi }}
                </td>

                <td class="text-muted text-truncate" style="max-width:300px;">
                    {{ $item->hero_title }}
                </td>

                <td class="text-center">
                    <div class="d-flex justify-content-center gap-1">

                        <a href="{{ route('setting.show',$item->id) }}" class="btn btn-sm btn-light border">
                            <i class="bi bi-eye"></i>
                        </a>

                        <a href="{{ route('setting.edit',$item->id) }}" class="btn btn-sm btn-light border">
                            <i class="bi bi-pencil"></i>
                        </a>

                        <form action="{{ route('setting.destroy',$item->id) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')

                            <button type="button" class="btn btn-sm btn-light border btn-delete">
                                <i class="bi bi-trash text-danger"></i>
                            </button>
                        </form>

                    </div>
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center text-muted py-4">
                    Data setting belum tersedia
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>