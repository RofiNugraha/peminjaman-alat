<div class="table-responsive">
    <table class="table table-modern align-middle mb-0">
        <thead>
            <tr>
                <th width="60">No</th>
                <th>Nama Sekolah</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Instagram</th>
                <th>Website</th>
                <th width="140" class="text-center">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse($contacts as $index => $item)
            <tr>

                <td class="text-muted">
                    {{ ($contacts->currentPage() - 1) * $contacts->perPage() + $index + 1 }}
                </td>

                <td class="fw-semibold">{{ $item->nama_sekolah }}</td>

                <td>{{ $item->email ?? '-' }}</td>
                <td>{{ $item->telepon ?? '-' }}</td>

                <td>
                    @if($item->instagram)
                    <a href="{{ $item->instagram }}" target="_blank">{{ $item->instagram }}</a>
                    @else
                    -
                    @endif
                </td>

                <td>
                    @if($item->website)
                    <a href="{{ $item->website }}" target="_blank">{{ $item->website }}</a>
                    @else
                    -
                    @endif
                </td>

                <td class="text-center">
                    <div class="d-flex justify-content-center gap-1">

                        <a href="{{ route('contact.show',$item->id) }}" class="btn btn-sm btn-light border">
                            <i class="bi bi-eye"></i>
                        </a>

                        <a href="{{ route('contact.edit',$item->id) }}" class="btn btn-sm btn-light border">
                            <i class="bi bi-pencil"></i>
                        </a>

                        <form action="{{ route('contact.destroy',$item->id) }}" method="POST">
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
                <td colspan="7" class="text-center text-muted py-4">
                    Data contact belum tersedia
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>