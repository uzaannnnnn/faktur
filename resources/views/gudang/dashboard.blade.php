@extends('layouts.app')

@section('content')
    <div x-data="{ modalOpen: false, modalData: {} }">
        <div class="space-y-8">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Halo {{ Auth::user()->name }} 👋</h1>
                <p class="mt-1 text-sm text-gray-600">Anda login sebagai Staff Gudang. Berikut adalah tugas Anda hari ini.
                </p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-md">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Pesanan Siap Kirim</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 w-12">#</th>
                                <th scope="col" class="px-6 py-3">No. Faktur</th>
                                <th scope="col" class="px-6 py-3">Customer</th>
                                <th scope="col" class="px-6 py-3">Tanggal</th>
                                <th scope="col" class="px-6 py-3">Faktur</th>
                                <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $key => $order)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium">{{ $key + 1 }}</td>
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $order->no }}</td>
                                    <td class="px-6 py-4">{{ $order->nama_customer }}</td>
                                    <td class="px-6 py-4">
                                        {{ \Carbon\Carbon::parse($order->created_at)->isoFormat('DD MMM YYYY') }}</td>
                                    <td class="px-6 py-4">
                                        <a href="{{ asset('storage/pdf/' . $order->file_faktur) }}" target="_blank"
                                            class="font-medium text-blue-600 hover:underline">Lihat File</a>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button type="button"
                                            @click="modalOpen = true; modalData = {
                                            type: 'success',
                                            title: 'Konfirmasi Pengiriman',
                                            message: 'Anda yakin ingin menandai pesanan untuk faktur #' + '{{ $order->no }}' + ' sebagai terkirim?',
                                            buttonText: 'Ya, Kirim Pesanan',
                                            action: '{{ route('gudang.kirim', $order->id) }}'
                                        }"
                                            class="inline-flex items-center gap-2 px-3 py-1.5 bg-green-600 text-white text-xs font-semibold rounded-md hover:bg-green-700 transition-colors">
                                            <i class="icon-send"></i>
                                            <span>Kirim</span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-10 text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <i class="icon-check-circle text-4xl text-green-400 mb-2"></i>
                                            <span>Tidak ada pesanan yang perlu dikirim saat ini.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-md">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Daftar Return Masuk</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 w-12">#</th>
                                <th scope="col" class="px-6 py-3">No. Faktur</th>
                                <th scope="col" class="px-6 py-3">Customer</th>
                                <th scope="col" class="px-6 py-3">Nota Return</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                                <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($returns as $key => $ret)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium">{{ $key + 1 }}</td>
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $ret->no }}</td>
                                    <td class="px-6 py-4">{{ $ret->nama_customer }}</td>
                                    <td class="px-6 py-4">
                                        <a href="{{ asset('storage/return-nota/' . $ret->status_nota) }}" target="_blank"
                                            class="font-medium text-blue-600 hover:underline">{{ $ret->status_nota }}</a>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center capitalize px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">{{ $ret->status_return }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button type="button"
                                            @click="modalOpen = true; modalData = {
                                            type: 'confirm',
                                            title: 'Konfirmasi Selesaikan Return',
                                            message: 'Anda yakin ingin menandai return untuk faktur #' + '{{ $ret->no }}' + ' sebagai selesai?',
                                            buttonText: 'Ya, Selesaikan',
                                            action: '{{ route('gudang.return.done', $ret->id) }}'
                                        }"
                                            class="inline-flex items-center gap-2 px-3 py-1.5 bg-indigo-600 text-white text-xs font-semibold rounded-md hover:bg-indigo-700 transition-colors">
                                            <i class="icon-check"></i>
                                            <span>Selesai</span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-10 text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <i class="icon-package text-4xl text-gray-400 mb-2"></i>
                                            <span>Tidak ada data return masuk.</span>
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
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full sm:mx-0 sm:h-10 sm:w-10"
                                :class="{ 'bg-green-100': modalData.type === 'success', 'bg-indigo-100': modalData
                                        .type === 'confirm' }">
                                <i
                                    :class="{ 'text-green-600 icon-send': modalData
                                        .type === 'success', 'text-indigo-600 icon-info': modalData
                                            .type === 'confirm' }"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" x-text="modalData.title"></h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500" x-text="modalData.message"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <form :action="modalData.action" method="POST" class="w-full sm:w-auto">
                            @csrf
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white sm:ml-3 sm:w-auto sm:text-sm"
                                :class="{ 'bg-green-600 hover:bg-green-700': modalData
                                    .type === 'success', 'bg-indigo-600 hover:bg-indigo-700': modalData
                                        .type === 'confirm' }"
                                x-text="modalData.buttonText"></button>
                        </form>
                        <button @click="modalOpen = false" type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
