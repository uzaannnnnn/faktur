<!DOCTYPE html>
<html>

<head>
    <title>Export Orders PDF</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th,
        td {
            padding: 8px;
            border: 1px solid #000;
            text-align: left;
        }
    </style>
</head>

<body>
    <h3>Data Order (Diterima & Lunas)</h3>
    <table>
        <thead>
            <tr>
                <th>No Faktur</th>
                <th>Customer</th>
                <th>Metode</th>
                <th>Total</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>{{ $order->no }}</td>
                    <td>{{ $order->nama_customer }}</td>
                    <td>{{ $order->metode }}</td>
                    <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                    <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d-m-Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
