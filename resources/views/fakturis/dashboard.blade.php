@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Halo {{ Auth::user()->name }} 👋</h1>
            <p class="mt-1 text-sm text-gray-600">Anda login sebagai Fakturis, selamat bekerja!</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6">
                <h2 class="text-lg font-semibold text-gray-800">Daftar Faktur Terbaru</h2>
                <a href="add-faktur"
                    class="inline-flex items-center justify-center gap-2 w-full sm:w-auto px-4 py-2 bg-blue-600 text-white font-semibold text-sm rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="icon-plus"></i>
                    <span>Buat Faktur</span>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">#</th>
                            <th scope="col" class="px-6 py-3">No. Faktur</th>
                            <th scope="col" class="px-6 py-3">Customer</th>
                            <th scope="col" class="px-6 py-3">Tanggal</th>
                            <th scope="col" class="px-6 py-3">File</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $index => $order)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    {{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}</td>
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $order->no }}</td>
                                <td class="px-6 py-4">{{ $order->nama_customer }}</td>
                                <td class="px-6 py-4">
                                    {{ \Carbon\Carbon::parse($order->created_at)->isoFormat('DD MMM YYYY') }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ asset('storage/pdf/' . $order->file_faktur) }}"
                                        class="font-medium text-blue-600 hover:underline" target="_blank">
                                        Lihat Faktur
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        @if ($order->status === 'proses')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Proses</span>
                                        @elseif ($order->status === 'dikirim')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800">Dikirim</span>
                                        @elseif ($order->status === 'diterima')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Diterima</span>
                                        @elseif ($order->status === 'return')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Return</span>
                                            @if ($order->status_nota)
                                                <a href="{{ asset('storage/return-nota/' . $order->status_nota) }}"
                                                    target="_blank"
                                                    class="text-indigo-600 hover:text-indigo-900 text-xs font-semibold">Lihat
                                                    Nota</a>
                                            @else
                                                <button
                                                    @click="modalOpen = true; modalData = { action: '{{ route('orders.return.generate', $order->id) }}', no_faktur: '{{ $order->no }}' }"
                                                    type="button"
                                                    class="text-amber-600 hover:text-amber-900 text-xs font-semibold">Buat
                                                    Nota</button>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-10 text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="icon-file-text text-4xl mb-2"></i>
                                        <span>Belum ada data faktur yang tersedia.</span>
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

    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="modalOpen = false"
                aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>
            <div x-show="modalOpen" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="icon-alert-triangle text-yellow-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Konfirmasi Buat Nota
                                Return</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Anda yakin ingin membuat nota return untuk faktur <strong
                                        x-text="modalData.no_faktur"></strong>? Tindakan ini tidak dapat dibatalkan.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form :action="modalData.action" method="POST" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-yellow-600 text-base font-medium text-white hover:bg-yellow-700 sm:ml-3 sm:w-auto sm:text-sm">
                            Ya, Buat Nota
                        </button>
                    </form>
                    <button @click="modalOpen = false" type="button"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
