<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Peminjaman</title>
    <style>
    body {
        font-family: sans-serif;
        font-size: 12px;
        color: #333;
    }

    h2 {
        margin-bottom: 5px;
    }

    .text-center {
        text-align: center;
    }

    .mb-10 {
        margin-bottom: 10px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    .table th,
    .table td {
        border: 1px solid #ddd;
        padding: 6px;
    }

    .table th {
        background: #f5f5f5;
    }

    .small {
        font-size: 11px;
        color: #666;
    }
    </style>
</head>

<body>

    <div class="text-center mb-10">
        <h2>Laporan Peminjaman</h2>
        <div class="small">
            Dicetak pada: {{ now()->format('d M Y H:i') }}
        </div>
    </div>

    <table class="table">
        <tr>
            <td>Total Transaksi</td>
            <td>{{ $data->count() }}</td>
        </tr>
        <tr>
            <td>Total Denda</td>
            <td>Rp {{ number_format($data->sum('total_denda')) }}</td>
        </tr>
        <tr>
            <td>Peminjaman Telat</td>
            <td>{{ $data->filter(fn($d) => $d->is_telat)->count() }}</td>
        </tr>
    </table>

    <table class="table">
        <thead>
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
                    {{ $item->alat->nama_alat }} ({{ $item->qty }})<br>
                    @endforeach
                </td>
                <td class="small">
                    Pinjam: {{ $row->tgl_pinjam }}<br>
                    Kembali: {{ $row->tgl_kembali }}<br>
                    @if($row->pengembalian)
                    Dikembalikan: {{ $row->pengembalian->tgl_dikembalikan }}
                    @endif
                </td>
                <td>{{ ucfirst($row->status) }}</td>
                <td>
                    Rp {{ number_format($row->total_denda,0,',','.') }}
                    <br>
                    <span class="small">
                        {{ strtoupper($row->status_denda) }}
                    </span>
                </td>
            </tr>
            @endforeach

            @if($data->isEmpty())
            <tr>
                <td colspan="7" class="text-center">Tidak ada data</td>
            </tr>
            @endif
        </tbody>
    </table>


</body>

</html>