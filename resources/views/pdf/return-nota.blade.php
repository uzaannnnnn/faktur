<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Nota Return - {{ $order->no }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
        }

        .invoice-box {
            width: 100%;
            margin: auto;
            padding: 20px;
        }

        .header {
            width: 100%;
            margin-bottom: 30px;
        }

        .header td {
            padding: 5px 0;
            vertical-align: top;
        }

        .header .logo {
            width: 150px;
        }

        .header .invoice-title {
            font-size: 32px;
            font-weight: bold;
            text-align: right;
        }

        .details {
            width: 100%;
            margin-bottom: 20px;
        }

        .details td {
            padding: 5px 0;
            vertical-align: top;
        }

        .details .billed-to {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .section-title {
            font-weight: bold;
            font-size: 14px;
            margin-top: 30px;
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
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
            font-size: 11px;
            color: #555;
        }

        .item-table tbody td {
            border-bottom: 1px solid #ddd;
            padding: 12px 8px;
        }

        .item-table tbody tr:last-child td {
            border-bottom: none;
        }

        .item-table .numeric {
            text-align: right;
        }

        .totals-table {
            width: 40%;
            float: right;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .totals-table td {
            padding: 8px;
        }

        .totals-table .label {
            text-align: right;
            font-weight: bold;
        }

        .totals-table .grand-total {
            font-size: 16px;
            font-weight: bold;
            color: #000;
            border-top: 2px solid #333;
        }

        .footer {
            position: fixed;
            bottom: 0px;
            left: 0px;
            right: 0px;
            height: 50px;
            font-size: 11px;
            color: #777;
            text-align: center;
            line-height: 20px;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
    </style>
</head>

<body>
    <div class="footer">
        Terima kasih atas kerja sama Anda.<br>
        Dokumen ini dicetak oleh sistem dan sah tanpa tanda tangan.
    </div>

    <main class="invoice-box">
        <table class="header">
            <tr>
                <td>
                    <h3 style="font-weight: bold; margin:0;">PHARMACY</h3>
                    <div style="font-size:11px;">Jalan Perusahaan No. 123, Bandung, Indonesia<br>pharmacy@company.com |
                        (021)
                        123-4567</div>
                </td>
                <td class="invoice-title" style="color: #d9534f;">
                    NOTA RETURN
                </td>
            </tr>
        </table>

        <table class="details">
            <tr>
                <td style="width: 50%;">
                    <div class="billed-to">DETAIL CUSTOMER:</div>
                    <strong>{{ $order->nama_customer }}</strong><br>
                    {{ $order->customer->alamat ?? 'Alamat tidak tersedia' }}
                </td>
                <td style="width: 50%; text-align: right;">
                    <strong>Referensi Faktur No:</strong> {{ $order->no }}<br>
                    <strong>Tanggal Return:</strong> {{ \Carbon\Carbon::now()->isoFormat('DD MMMM YYYY') }}<br>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding-top: 15px;">
                    <strong style="font-weight: bold;">Alasan Return:</strong><br>
                    <div style="font-size: 12px;">{{ $order->alasan_return ?? 'Tidak ada alasan yang diberikan.' }}
                    </div>
                </td>
            </tr>
        </table>

        <div class="section-title">Detail Barang Diretur:</div>

        <table class="item-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No.</th>
                    <th>Nama Obat</th>
                    <th class="numeric" style="width: 20%;">Harga Satuan</th>
                    <th class="numeric" style="width: 10%;">Qty</th>
                    <th class="numeric" style="width: 20%;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->pesanan as $i => $item)
                    @php $obat = App\Models\Obat::find($item['id']); @endphp
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>
                            <strong style="font-weight: bold;">{{ $obat->nama_obat ?? 'Obat tidak ditemukan' }}</strong>
                            <div style="font-size:10px; color:#666;">No. Batch: {{ $obat->no_batch ?? '-' }}</div>
                        </td>
                        <td class="numeric">Rp{{ number_format($item['harga'], 0, ',', '.') }}</td>
                        <td class="numeric">{{ $item['quantity'] }}</td>
                        <td class="numeric">Rp{{ number_format($item['harga'] * $item['quantity'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totals-table">
            <tr>
                <td class="label">Total Nilai Return</td>
                <td class="numeric grand-total">Rp{{ number_format($order->total, 0, ',', '.') }}</td>
            </tr>
        </table>
    </main>
</body>

</html>
