@extends('layouts.app')

@section('content')
    <div x-data="{ modalOpen: false, modalData: {} }">
        <div class="space-y-8">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Halo {{ Auth::user()->name }} 👋</h1>
                <p class="mt-1 text-sm text-gray-600">Selamat datang di dashboard pesanan Anda.</p>
            </div>

            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Pesanan Sedang Dikirim</h2>
                @if ($activeOrders->isNotEmpty())
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @foreach ($activeOrders as $order)
                            <div class="bg-white p-5 rounded-xl shadow-md space-y-4 flex flex-col justify-between">
                                <div>
                                    <p class="text-xs text-gray-500">No. Faktur</p>
                                    <a href="{{ asset('storage/pdf/' . $order->file_faktur) }}" target="_blank"
                                        class="font-semibold text-blue-600 hover:underline">{{ $order->no }}</a>
                                </div>
                                <div class="flex gap-3">
                                    <form action="{{ route('order.terima', $order->id) }}" method="POST" class="flex-1">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="w-full inline-flex justify-center items-center gap-2 px-3 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition-colors">
                                            <i class="icon-check"></i>
                                            <span>Diterima</span>
                                        </button>
                                    </form>
                                    <button type="button"
                                        @click="modalOpen = true; modalData = { orderId: {{ $order->id }}, noFaktur: '{{ $order->no }}', action: '{{ route('orders.return') }}' }"
                                        class="flex-1 inline-flex justify-center items-center gap-2 px-3 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-colors">
                                        <i class="icon-refresh-cw"></i>
                                        <span>Return</span>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 bg-white rounded-xl shadow-md">
                        <p class="text-gray-500">Tidak ada pesanan yang sedang dalam pengiriman.</p>
                    </div>
                @endif
            </div>

            <div class="bg-white p-6 rounded-xl shadow-md">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Riwayat Pesanan</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">#</th>
                                <th scope="col" class="px-6 py-3">Faktur</th>
                                <th scope="col" class="px-6 py-3">Tagihan</th>
                                <th scope="col" class="px-6 py-3">Pembayaran</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                                <th scope="col" class="px-6 py-3 text-center">Bukti Bayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $key => $order)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4">{{ $key + 1 }}</td>
                                    <td class="px-6 py-4">
                                        <a href="{{ asset('storage/pdf/' . $order->file_faktur) }}" target="_blank"
                                            class="font-semibold text-blue-600 hover:underline">{{ $order->no }}</a>
                                        <div class="text-xs text-gray-400">
                                            {{ \Carbon\Carbon::parse($order->created_at)->isoFormat('DD MMM YYYY') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($order->metode === 'termin' && $order->status_bayar === 'belum bayar')
                                            <div class="font-medium text-gray-800">Rp{{ number_format($order->total) }}
                                            </div>
                                            <div class="text-xs text-red-500">Jatuh Tempo:
                                                {{ \Carbon\Carbon::parse(Auth::user()->jatuh_tempo)->isoFormat('DD MMM YYYY') }}
                                            </div>
                                        @else
                                            <span>-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($order->status_bayar === 'lunas')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Lunas</span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Belum
                                                Bayar</span>
                                        @endif
                                        <p class="text-xs text-gray-400 capitalize">{{ $order->metode }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        @switch($order->status)
                                            @case('proses')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Proses</span>
                                            @break

                                            @case('dikirim')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Dikirim</span>
                                            @break

                                            @case('diterima')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">Diterima</span>
                                            @break

                                            @case('return')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Return</span>
                                            @break
                                        @endswitch
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($order->bukti_bayar)
                                            <a href="{{ asset('storage/' . $order->bukti_bayar) }}" target="_blank"
                                                class="font-medium text-blue-600 hover:underline">Lihat Bukti</a>
                                        @elseif ($order->status_bayar === 'belum bayar' || $order->bukti_bayar === null)
                                            <form action="{{ route('order.upload.bukti', $order->id) }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <label
                                                    class="cursor-pointer inline-flex items-center px-3 py-1.5 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-md hover:bg-indigo-200 transition-colors">
                                                    <span>Upload</span>
                                                    <input type="file" name="bukti_bayar" class="hidden"
                                                        onchange="this.form.submit()">
                                                </label>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-10 text-gray-500">
                                            <div class="flex flex-col items-center">
                                                <i class="icon-shopping-cart text-4xl mb-2"></i>
                                                <span>Anda belum memiliki riwayat pesanan.</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
                role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                        class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="modalOpen = false"
                        aria-hidden="true"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>
                    <div x-show="modalOpen" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <form :action="modalData.action" method="POST">
                            @csrf
                            <input type="hidden" name="order_id" :value="modalData.orderId">
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Form Pengajuan Return
                                </h3>
                                <p class="text-sm text-gray-500 mt-1">Untuk faktur: <strong
                                        x-text="modalData.noFaktur"></strong></p>
                                <div class="mt-4">
                                    <label for="alasan_return" class="block text-sm font-medium  text-gray-700">Alasan Return
                                        <span class="text-red-500">*</span></label>
                                    <textarea id="alasan_return" name="alasan_return" rows="4"
                                        class="mt-1 block w-full p-2 rounded-md border-gray-300 shadow-md focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        placeholder="Jelaskan alasan Anda mengajukan return untuk pesanan ini..." required></textarea>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="submit"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">Ajukan
                                    Return</button>
                                <button @click="modalOpen = false" type="button"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection
