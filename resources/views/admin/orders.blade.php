@extends('layouts.app')
@section('content')
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Orders (Diterima & Lunas)</h3>
        </div>

        <div class="wg-box">
            <div class="wg-table table-all-user">
                <div class="table-responsive">
                    <div class="mb-3">
                        <a href="{{ route('orders.export.pdf') }}" class="btn btn-danger me-2">Export PDF</a>
                        <a href="{{ route('orders.export.excel') }}" class="btn btn-success">Export Excel</a>
                    </div>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No Faktur</th>
                                <th>Nama</th>
                                <th>Metode</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Faktur</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                @if ($order->status === 'diterima' && $order->status_bayar === 'lunas')
                                    <tr>
                                        <td>{{ $order->no }}</td>
                                        <td>{{ $order->nama_customer }}</td>
                                        <td class="text-capitalize">{{ $order->metode }}</td>
                                        <td>{{ $order->status }} & {{ $order->status_bayar }}</td>
                                        <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d-m-Y') }}</td>
                                        <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                        <td>
                                            <a href="{{ asset('storage/pdf/' . $order->file_faktur) }}"
                                                class="text-decoration-underline" target="_blank">
                                                {{ $order->file_faktur }}
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Belum ada order diterima & lunas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="divider"></div>
            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
@endsection
