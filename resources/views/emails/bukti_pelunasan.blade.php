<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Bukti Pelunasan Denda</title>
    <style>
    :root {
        --color-primary: #4F46E5;
        --color-primary-hover: #4338CA;

        --color-sidebar: #1E293B;
        --color-sidebar-hover: #334155;

        --color-bg: #F8FAFC;
        --color-border: #E2E8F0;
        --color-text-muted: #64748B;
    }

    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 12px;
        color: #333;
    }

    .btn-primary {
        background: var(--color-primary);
        border: none;
        font-weight: 500;
    }

    .btn-primary:hover {
        background: var(--color-primary-hover);
    }

    .btn-secondary {
        background: #e5e7eb;
        border: none;
    }

    .btn-danger {
        background: #ef4444;
        border: none;
    }

    .form-control {
        height: 42px;
        font-size: 0.9rem;
    }

    .form-control:focus {
        border-color: var(--color-primary);
        box-shadow: 0 0 0 0.15rem rgba(79, 70, 229, 0.15);
    }

    .table-modern {
        font-size: 0.9rem;
    }

    .table-modern thead {
        background: #f1f5f9;
    }

    .table-modern th {
        font-weight: 600;
        padding: 12px;
    }

    .table-modern td {
        padding: 12px;
        vertical-align: middle;
    }

    .table-modern tbody tr:hover {
        background: #f9fafb;
    }

    .card {
        border-radius: 12px;
        border: 1px solid var(--color-border);
        transition: all 0.2s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.05);
    }

    .card-body {
        padding: 20px;
    }

    label.form-label {
        margin-bottom: 2px;
    }

    .container-fluid {
        max-width: 1400px;
        margin: 0 auto;
    }

    .container {
        padding: 24px;
    }

    .header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .title {
        font-size: 20px;
        font-weight: bold;
    }

    .subtitle {
        font-size: 12px;
        color: #777;
    }

    .box {
        border: 1px solid #ddd;
        padding: 12px;
        border-radius: 6px;
        margin-bottom: 20px;
    }

    .label {
        font-size: 11px;
        color: #888;
    }

    .value {
        font-weight: 600;
        margin-bottom: 6px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    th {
        background: #f5f5f5;
        text-align: left;
        padding: 8px;
        font-size: 11px;
    }

    td {
        padding: 8px;
        border-bottom: 1px solid #eee;
    }

    .text-right {
        text-align: right;
    }

    .total {
        margin-top: 15px;
        font-size: 14px;
        font-weight: bold;
        text-align: right;
        color: #d9534f;
    }

    .footer {
        margin-top: 30px;
        font-size: 11px;
        text-align: center;
        color: #888;
    }

    .badge {
        display: inline-block;
        padding: 4px 8px;
        background: #d4edda;
        color: #155724;
        border-radius: 4px;
        font-size: 10px;
        font-weight: bold;
    }
    </style>
</head>

<body>

    <div class="container">

        <div class="header">
            <div>
                <div class="title">BUKTI PELUNASAN DENDA</div>
                <div class="subtitle">Sistem Peminjaman Alat</div>
            </div>

            <div style="text-align:right;">
                <div class="label">Tanggal</div>
                <div class="value">{{ now()->format('d M Y') }}</div>

                <div class="label">No. Transaksi</div>
                <div class="value">{{ $peminjaman->kode_peminjaman }}</div>
            </div>
        </div>

        <div class="box">
            <div class="label">Nama Peminjam</div>
            <div class="value">{{ $peminjaman->user->nama }}</div>

            <div class="label">Email</div>
            <div class="value">{{ $peminjaman->user->email }}</div>
        </div>

        <div class="box">
            <div class="label">Detail Denda</div>

            <table>
                <thead>
                    <tr>
                        <th>Nama Alat</th>
                        <th class="text-right">Baik</th>
                        <th class="text-right">Rusak</th>
                        <th class="text-right">Hilang</th>
                        <th class="text-right">Denda</th>
                    </tr>
                </thead>
                <tbody>
                    @if($peminjaman->pengembalian && $peminjaman->pengembalian->items)
                    @foreach($peminjaman->pengembalian->items as $item)
                    <tr>
                        <td>{{ $item->alat->nama_alat ?? '-' }}</td>
                        <td class="text-right">{{ $item->qty_baik }}</td>
                        <td class="text-right">{{ $item->qty_rusak }}</td>
                        <td class="text-right">{{ $item->qty_hilang }}</td>
                        <td class="text-right">
                            Rp {{ number_format($item->denda,0,',','.') }}
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="4" style="text-align:center;">
                            Tidak ada data pengembalian
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>

            <!-- DENDA TELAT -->
            @if($peminjaman->pengembalian->denda_telat > 0)
            <div class="row gy-3 mb-3" style="margin-top:10px;">

                <div class="col-md-4">
                    Tanggal Dikembalikan:
                    <strong>{{ \Carbon\Carbon::parse($peminjaman->pengembalian->tgl_dikembalikan)->format('d M Y') }}</strong>
                </div>

                <div class="col-md-4">
                    Hari Telat:
                    <strong>{{ $peminjaman->pengembalian->hari_telat }} hari</strong>
                </div>

                <div class="col-md-4">
                    Denda keterlambatan:
                    <strong>
                        Rp {{ number_format($peminjaman->pengembalian->denda_telat,0,',','.') }}
                    </strong>
                </div>

            </div>
            @endif

            <!-- TOTAL -->
            <div class="total">
                Total Dibayar:
                Rp {{ number_format($peminjaman->total_denda,0,',','.') }}
            </div>

            <div style="margin-top:10px;">
                Status:
                <span class="badge">LUNAS</span>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="footer">
            Bukti ini sah dan dihasilkan oleh sistem secara otomatis.
            Terima kasih telah melakukan pembayaran.
        </div>

    </div>

</body>

</html>