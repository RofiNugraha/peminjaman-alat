<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Peminjaman</title>
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

    <h3>Laporan Peminjaman</h3>

    <p>
        Periode:
        {{ $from ?? '-' }} s/d {{ $to ?? '-' }}
    </p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Tgl Pinjam</th>
                <th>Deadline</th>
                <th>Tgl Dikembalikan</th>
                <th>Status</th>
                <th>Denda</th>
                <th>Status Denda</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peminjamans as $i => $p)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>
                    {{ $p->user->nama ?? '-' }}
                </td>
                <td>
                    {{ $p->tgl_pinjam }}
                </td>
                <td>
                    {{ $p->tgl_kembali }}
                </td>
                <td>
                    {{ $p->pengembalian->tgl_dikembalikan ?? '-' }}
                </td>
                <td>
                    {{ $p->status }}
                </td>
                <td class="text-right">
                    @php
                    $denda = optional(optional($p->pengembalian)->denda);
                    @endphp

                    Rp {{ number_format($denda->total_denda ?? 0) }}
                </td>
                <td>
                    {{ optional(optional($p->pengembalian)->denda)->status ?? '-' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>