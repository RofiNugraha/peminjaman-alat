<table>
    <tr>
        <th colspan="2">RINGKASAN LAPORAN</th>
    </tr>
    <tr>
        <td>Total Transaksi</td>
        <td>{{ $total }}</td>
    </tr>
    <tr>
        <td>Total Denda</td>
        <td>{{ $totalDenda }}</td>
    </tr>
    <tr>
        <td>Jumlah Telat</td>
        <td>{{ $telat }}</td>
    </tr>

    <tr>
        <td colspan="2"></td>
    </tr>

    <tr>
        <th>Status</th>
        <th>Jumlah</th>
    </tr>
    @foreach($status as $k => $v)
    <tr>
        <td>{{ $k }}</td>
        <td>{{ $v }}</td>
    </tr>
    @endforeach
</table>