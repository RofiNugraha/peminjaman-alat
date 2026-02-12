<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Denda</title>
    <style>
    body {
        font-family: sans-serif;
        font-size: 12px
    }

    table {
        width: 100%;
        border-collapse: collapse
    }

    th,
    td {
        border: 1px solid #000;
        padding: 6px
    }

    th {
        background: #eee
    }
    </style>
</head>

<body>

    <h3>Laporan Denda</h3>
    <p>
        Periode:
        {{ $from ?? '-' }} s/d {{ $to ?? '-' }}
    </p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Tgl Kembali</th>
                <th>Total Denda</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dendas as $i => $d)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $d->pengembalian->peminjaman->user->nama }}</td>
                <td>{{ $d->pengembalian->tgl_dikembalikan }}</td>
                <td>Rp {{ number_format($d->total_denda) }}</td>
                <td>{{ $d->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>