@extends('layouts.app')

@section('content')
    <div x-data="obatPage()">
        <div class="space-y-6">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <h1 class="text-2xl font-semibold text-gray-900">Data Obat</h1>
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    @if (auth()->user()->role === 'gudang')
                        <a href="add-obat"
                            class="inline-flex items-center justify-center gap-2 w-full sm:w-auto px-4 py-2 bg-blue-600 text-white font-semibold text-sm rounded-lg hover:bg-blue-700 transition-colors whitespace-nowrap">
                            <i class="icon-plus"></i>
                            <span>Tambah Obat</span>
                        </a>
                    @endif
                    <form method="GET" action="{{ route('obats') }}" class="w-full sm:w-auto">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="icon-search text-gray-400"></i>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari data..."
                                class="w-full max-w-xs pl-10 pr-4 py-2 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-md">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">#</th>
                                <th scope="col" class="px-6 py-3">Nama Obat</th>
                                <th scope="col" class="px-6 py-3">No Batch</th>
                                <th scope="col" class="px-6 py-3">Distributor/Pabrik</th>
                                <th scope="col" class="px-6 py-3 text-center">Qty</th>
                                <th scope="col" class="px-6 py-3">Harga</th>
                                <th scope="col" class="px-6 py-3">ED</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($obats as $obat)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        {{ ($obats->currentPage() - 1) * $obats->perPage() + $loop->iteration }}</td>
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $obat->nama_obat }}</td>
                                    <td class="px-6 py-4 text-gray-500">{{ $obat->no_batch }}</td>
                                    <td class="px-6 py-4 text-gray-500">
                                        <div class="font-medium text-gray-800">{{ $obat->distributor }}</div>
                                        <div class="text-xs text-gray-400">{{ $obat->pabrik }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="inline-flex items-center gap-2">
                                            <span>{{ $obat->quantity }}</span>
                                            @if (auth()->user()->role === 'gudang')
                                                <button @click="openModal('quantity', {{ json_encode($obat) }})"
                                                    type="button"
                                                    class="text-gray-400 hover:text-blue-600 transition-colors">
                                                    <i class="icon-edit-3 text-sm"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500">
                                        <div class="inline-flex items-center gap-2">
                                            <span>Rp{{ number_format($obat->harga, 0, ',', '.') }}</span>
                                            <button @click="openModal('harga', {{ json_encode($obat) }})" type="button"
                                                class="text-gray-400 hover:text-blue-600 transition-colors"><i
                                                    class="icon-edit-3 text-sm"></i></button>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500">
                                        {{ \Carbon\Carbon::parse($obat->ed)->isoFormat('MMM YYYY') }}</td>
                                    <td class="px-6 py-4">
                                        @if ($obat->status === 'tersedia')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Tersedia</span>
                                        @elseif ($obat->status === 'habis')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Habis</span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Kadaluarsa</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-10 text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <i class="icon-package text-4xl mb-2"></i>
                                            <span>Data obat tidak ditemukan.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($obats->hasPages())
                    <div class="mt-6">
                        {{ $obats->appends(request()->query())->links() }}
                    </div>
                @endif
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
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                    <form
                        :action="modalType === 'quantity' ? '{{ route('obats.update') }}' : '{{ route('obats.update.harga') }}'"
                        method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" :value="editData.id">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900"
                                x-text="modalType === 'quantity' ? 'Ubah Quantity' : 'Ubah Harga'"></h3>
                            <p class="text-sm text-center text-gray-500 mt-2" x-text="editData.nama_obat"></p>
                            <div class="mt-4">
                                <div x-show="modalType === 'quantity'">
                                    <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                                    <input type="number" name="quantity" id="quantity"
                                        x-model.number="editData.quantity"
                                        class="mt-1 block w-full p-2 rounded-md border-gray-300 shadow-md focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        required>
                                </div>
                                <div x-show="modalType === 'harga'">
                                    <label for="harga" class="block text-sm font-medium text-gray-700">Harga
                                        (Rp)</label>
                                    <input type="number" name="harga" id="harga" x-model.number="editData.harga"
                                        class="mt-1 block w-full p-2 rounded-md border-gray-300 shadow-md focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        required min="0">
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                            <button @click="modalOpen = false" type="button"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function obatPage() {
            return {
                modalOpen: false,
                modalType: '', // 'quantity' or 'harga'
                editData: {
                    id: null,
                    nama_obat: '',
                    quantity: 0,
                    harga: 0
                },

                openModal(type, obat) {
                    this.modalType = type;
                    this.editData.id = obat.id;
                    this.editData.nama_obat = obat.nama_obat;
                    this.editData.quantity = obat.quantity;
                    this.editData.harga = obat.harga;
                    this.modalOpen = true;
                }
            }
        }
    </script>
@endsection
