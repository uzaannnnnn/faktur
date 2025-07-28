@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Orders Selesai (Diterima & Lunas)</h1>
            <p class="mt-1 text-sm text-gray-600">Lihat dan ekspor riwayat semua pesanan yang telah selesai dan lunas.</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
                <h2 class="text-lg font-semibold text-gray-800">Riwayat Pesanan</h2>
                <div class="flex items-center gap-3">
                    <a href="{{ route('orders.export.pdf') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white font-semibold text-sm rounded-lg hover:bg-red-700 transition-colors">
                        <i class="icon-file-text"></i>
                        <span>Export PDF</span>
                    </a>
                    <a href="{{ route('orders.export.excel') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white font-semibold text-sm rounded-lg hover:bg-green-700 transition-colors">
                        <i class="icon-grid"></i>
                        <span>Export Excel</span>
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">No. Faktur</th>
                            <th scope="col" class="px-6 py-3">Customer</th>
                            <th scope="col" class="px-6 py-3">Tanggal</th>
                            <th scope="col" class="px-6 py-3">Total</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">File</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            {{-- Asumsi controller sudah memfilter, jika tidak, tambahkan if di sini --}}
                            {{-- @if ($order->status === 'diterima' && $order->status_bayar === 'lunas') --}}
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $order->no }}</td>
                                <td class="px-6 py-4">{{ $order->nama_customer }}</td>
                                <td class="px-6 py-4">
                                    {{ \Carbon\Carbon::parse($order->created_at)->isoFormat('DD MMM YYYY') }}</td>
                                <td class="px-6 py-4 font-medium">Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1.5">
                                        <span
                                            class="inline-flex items-center w-fit px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Diterima</span>
                                        <span
                                            class="inline-flex items-center w-fit px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">Lunas</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ asset('storage/pdf/' . $order->file_faktur) }}"
                                        class="font-medium text-blue-600 hover:underline" target="_blank">
                                        Lihat Faktur
                                    </a>
                                </td>
                            </tr>
                            {{-- @endif --}}
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-10 text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="icon-archive text-4xl mb-2"></i>
                                        <span>Belum ada data pesanan yang selesai.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($orders->hasPages())
                <div class="mt-6">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
