<table>
    <thead>
        <tr>
            <th colspan="7" style="text-align:center; font-weight:bold;">
                LAPORAN PEMINJAMAN
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
        @foreach($data as $i => $row)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $row->kode_peminjaman }}</td>
            <td>{{ $row->user->nama }}</td>
            <td>
                @foreach($row->items as $item)
                {{ $item->alat->nama_alat }} ({{ $item->qty }})
                @endforeach
            </td>
            <td>
                {{ $row->tgl_pinjam }} - {{ $row->tgl_kembali }}
            </td>
            <td>{{ $row->status }}</td>
            <td>{{ $row->total_denda }}</td>
        </tr>
        @endforeach
    </tbody>
</table>