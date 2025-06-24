<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Nota Return - {{ $order->no }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 14px;
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .no-border td {
            border: none;
            padding: 4px 8px;
        }

        .total {
            text-align: right;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <h2>Nota Return</h2>

    <table class="no-border">
        <tr>
            <td><strong>No Faktur</strong></td>
            <td>{{ $order->no }}</td>
        </tr>
        <tr>
            <td><strong>Customer</strong></td>
            <td>{{ $order->nama_customer }}</td>
        </tr>
        <tr>
            <td><strong>Tanggal Return</strong></td>
            <td>{{ \Carbon\Carbon::now()->format('d-m-Y') }}</td>
        </tr>
        <tr>
            <td><strong>Alasan Return</strong></td>
            <td>{{ $order->alasan_return }}</td>
        </tr>
    </table>

    <h3 style="margin-top: 30px;">Detail Barang Diretur:</h3>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Obat</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->pesanan as $i => $item)
                @php $obat = App\Models\Obat::find($item['id']); @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $obat->nama_obat }}</td>
                    <td>Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>Rp {{ number_format($item['harga'] * $item['quantity'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3 class="total">Total Return: Rp {{ number_format($order->total, 0, ',', '.') }}</h3>

    <p style="margin-top: 20px;"><strong>Status:</strong> Return</p>
</body>

</html>
