<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Pesanan Selesai</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }

        .report-container {
            width: 100%;
            margin: auto;
        }

        .report-header {
            width: 100%;
            margin-bottom: 25px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
        }

        .report-header td {
            padding: 5px 0;
            vertical-align: middle;
        }

        .report-title {
            font-size: 22px;
            font-weight: bold;
            color: #000;
            text-align: right;
        }

        .report-subtitle {
            font-size: 12px;
            color: #555;
            text-align: right;
        }

        .item-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .item-table thead th {
            background-color: #f2f2f2;
            border-bottom: 2px solid #ddd;
            padding: 10px 8px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            color: #555;
        }

        .item-table tbody td {
            border-bottom: 1px solid #eee;
            padding: 9px 8px;
        }

        .item-table .numeric {
            text-align: right;
        }

        .footer {
            position: fixed;
            bottom: -20px;
            /* Disesuaikan agar tidak terlalu mepet */
            left: 0px;
            right: 0px;
            height: 50px;
            font-size: 10px;
            color: #777;
            text-align: center;
        }

        .footer .page-number:before {
            /* Pustaka PDF seperti dompdf akan otomatis mengganti ini dengan nomor halaman */
            content: "Halaman " counter(page);
        }
    </style>
</head>

<body>
    <div class="footer">
        Laporan ini dibuat oleh sistem pada {{ \Carbon\Carbon::now()->isoFormat('DD MMMM YYYY, HH:mm') }}
        <br>
        <span class="page-number"></span>
    </div>

    <main class="report-container">
        <table class="report-header">
            <tr>
                <td>
                    <h3 style="font-weight: bold; margin:0;">PHARMACY</h3>
                    <div style="font-size:11px;">Laporan Data Penjualan</div>
                </td>
                <td class="report-title">
                    Pesanan Selesai
                    <div class="report-subtitle">(Diterima & Lunas)</div>
                </td>
            </tr>
        </table>

        <table class="item-table">
            <thead>
                <tr>
                    <th style="width: 20%;">No. Faktur</th>
                    <th>Customer</th>
                    <th style="width: 12%;">Metode</th>
                    <th class="numeric" style="width: 18%;">Nilai Total</th>
                    <th class="numeric" style="width: 15%;">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->no }}</td>
                        <td>{{ $order->nama_customer }}</td>
                        <td class="text-capitalize">{{ $order->metode }}</td>
                        <td class="numeric">Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                        <td class="numeric">{{ \Carbon\Carbon::parse($order->created_at)->isoFormat('DD MMM YYYY') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>
</body>

</html>
