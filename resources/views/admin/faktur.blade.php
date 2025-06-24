@extends('layouts.app')
@section('content')
    <h3>Faktur</h3>
    <div class="main-content-wrap mt-5">

        <div class="wg-box">

            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>No. Faktur</th>
                            <th>Customer</th>
                            <th>Alamat Customer</th>
                            <th>Tanggal Faktur</th>
                            <th>Faktur</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $index => $order)
                            <tr>
                                <td>{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}</td>
                                <td>{{ $order->no }}</td>
                                <td>{{ $order->nama_customer }}</td>
                                <td>{{ $order->customer->alamat ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d-m-Y') }}</td>
                                <td>
                                    <a href="{{ asset('storage/pdf/' . $order->file_faktur) }}"
                                        class="text-decoration-underline" target="_blank">
                                        {{ $order->file_faktur }}
                                    </a>
                                </td>
                                <td>
                                    @if ($order->status === 'proses')
                                        <span class="badge p-3 fs-5 bg-primary text-light">Proses</span>
                                    @elseif ($order->status === 'dikirim')
                                        <span class="badge p-3 fs-5 bg-info text-light">Dikirim</span>
                                    @elseif ($order->status === 'diterima')
                                        <span class="badge p-3 fs-5 bg-success text-light">Diterima</span>
                                    @elseif ($order->status === 'return')
                                        <span class="badge p-3 fs-5 bg-danger text-light">Return</span> |
                                        @if ($order->status === 'return')
                                            @if ($order->status_nota)
                                                <a href="{{ asset('storage/return-nota/' . $order->status_nota) }}"
                                                    target="_blank" class="btn btn-info p-2">
                                                    Lihat Nota Return
                                                </a>
                                            @else
                                                <form action="{{ route('orders.return.generate', $order->id) }}"
                                                    method="POST" style="display:inline-block;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-warning p-2">Buat Nota
                                                        Return</button>
                                                </form>
                                            @endif
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada faktur.</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            <div class="mt-4">
                {{ $orders->links() }}
            </div>


        </div>
    </div>
    </div>
@endsection
