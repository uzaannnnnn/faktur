<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Faktur - {{ $order->no }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
            padding: 8px;
        }
    </style>
</head>

<body>
    <h2>Faktur: {{ $order->no }}</h2>
    <p><strong>Customer:</strong> {{ $order->nama_customer }}</p>
    <p><strong>Alamat:</strong> {{ $order->customer->alamat ?? '-' }}</p>
    <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($order->created_at)->format('d-m-Y') }}</p>
    <p><strong>Metode Pembayaran:</strong> {{ $order->metode }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Obat</th>
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

    <h3 style="text-align: right">Total: Rp {{ number_format($order->total, 0, ',', '.') }}</h3>
</body>

</html>
