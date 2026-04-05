<div class="table-responsive">
    <table class="table table-modern align-middle mb-0">
        <thead>
            <tr>
                <th width="60">No</th>
                <th>NISN</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Jurusan</th>
                <th>Angkatan</th>
                <th>Tahun Ajaran</th>
                <th width="120">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $siswa)
            <tr>
                <td class="text-muted">
                    {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                </td>

                <td>{{ $siswa->nisn }}</td>

                <td class="fw-semibold">
                    {{ $siswa->nama }}
                </td>

                <td>{{ $siswa->kelas }}</td>
                <td>{{ $siswa->jurusan }}</td>
                <td>{{ $siswa->tahun_angkatan }}</td>

                <td class="text-muted">
                    {{ $siswa->tahun_ajaran }}
                </td>

                <td>
                    <span class="badge 
                        {{ $siswa->status == 'aktif' 
                            ? 'bg-success bg-opacity-10 text-success' 
                            : 'bg-secondary bg-opacity-10 text-secondary' }}">
                        {{ ucfirst($siswa->status) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="d-flex flex-wrap justify-content-between align-items-center p-3 border-top">

    <div class="d-flex align-items-center gap-2">
        <span class="small text-muted">Data per halaman</span>
        <select id="per_page" name="per_page" class="form-select form-select-sm w-auto">
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