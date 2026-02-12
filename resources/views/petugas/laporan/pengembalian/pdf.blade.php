<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Pengembalian</title>
    <style>
    body {
        font-family: sans-serif;
        font-size: 12px;
    }

    h3 {
        margin-bottom: 5px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    th,
    td {
        border: 1px solid #000;
        padding: 6px;
        text-align: left;
    }

    th {
        background: #eee;
    }

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }
    </style>
</head>

<body>

    <h3>Laporan Pengembalian</h3>
    <p>Periode: {{ $from ?? '-' }} s/d {{ $to ?? '-' }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Peminjam</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Tgl Dikembalikan</th>
                <th>Hari Telat</th>
                <th>Denda</th>
                <th>Status Denda</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pengembalians as $i => $k)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $k->peminjaman->user->nama ?? '-' }}</td>
                <td>{{ $k->peminjaman->tgl_pinjam }}</td>
                <td>{{ $k->peminjaman->tgl_kembali }}</td>
                <td>{{ $k->tgl_dikembalikan }}</td>
                <td>{{ $k->hari_telat }}</td>
                <td class="text-right">Rp {{ number_format(optional($k->denda)->total_denda ?? 0) }}</td>
                <td>{{ optional($k->denda)->status ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>