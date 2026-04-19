<table>
    <thead>
        <tr>
            <th colspan="7" style="font-weight:bold; text-align:center;">
                DATA PEMINJAMAN
            </th>
        </tr>

        <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Peminjam</th>
            <th>Alat</th>
            <th>Tanggal</th>
            <th>Status</th>
            <th>Denda</th>
        </tr>
    </thead>

    <tbody>
        @forelse($data as $i => $row)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $row->kode_peminjaman }}</td>
            <td>{{ $row->user->nama }}</td>

            <td>
                @foreach($row->items as $item)
                {{ $item->alat->nama_alat }} ({{ $item->qty }})<br>
                @endforeach
            </td>

            <td>
                {{ optional($row->tgl_pinjam)->format('d-m-Y') }} -
                {{ optional($row->tgl_kembali)->format('d-m-Y') }}
            </td>

            <td>{{ ucfirst($row->status) }}</td>

            <td>
                {{ number_format($row->total_denda,0,',','.') }}<br>
                <small>{{ strtoupper($row->status_denda) }}</small>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7">Tidak ada data</td>
        </tr>
        @endforelse
    </tbody>
</table>