<table>
    <thead>
        <tr>
            <th>Kode</th>
            <th>Peminjam</th>
            <th>Alat</th>
            <th>Qty</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>

        @forelse($rows as $r)
        <tr>
            <td>{{ $r['kode'] }}</td>
            <td>{{ $r['nama'] }}</td>
            <td>{{ $r['alat'] }}</td>
            <td>{{ $r['qty'] }}</td>
            <td>{{ $r['tgl'] }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="5">Tidak ada data</td>
        </tr>
        @endforelse

    </tbody>
</table>