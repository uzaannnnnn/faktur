@extends('layouts.app')

@section('content')
    <div x-data="fakturForm()" x-init="$watch('pesanan', () => calculateTotal());
    $watch('searchQuery', () => filterTable());">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Buat Faktur Baru</h1>
        </div>

        <form id="fakturForm" method="POST" action="{{ route('fakturis.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="bg-white p-6 sm:p-8 rounded-xl shadow-md space-y-8">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="id_customer" class="block text-sm font-medium text-gray-700 mb-1">Nama Customer <span
                                class="text-red-500">*</span></label>
                        <select name="id_customer" id="id_customer"
                            class="w-full max-w-sm p-2 rounded-lg border-gray-300 shadow-md focus:border-blue-500 focus:ring-blue-500"
                            required>
                            <option value="">Pilih Customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="metode" class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran <span
                                class="text-red-500">*</span></label>
                        <select name="metode" id="metode"
                            class="w-full max-w-sm p-2 rounded-lg border-gray-300 shadow-md focus:border-blue-500 focus:ring-blue-500"
                            required>
                            <option value="">Pilih Metode</option>
                            <option value="tunai">Tunai</option>
                            <option value="termin">Termin</option>
                        </select>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-8">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <h3 class="text-lg font-semibold text-gray-800">Pilih Obat</h3>
                        <div class="relative w-full sm:w-auto sm:max-w-xs">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="icon-search text-gray-400"></i>
                            </div>
                            <input type="text" x-model="searchQuery" placeholder="Cari obat atau no. batch..."
                                class="w-full pl-10 pr-4 py-2 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="mt-4 flow-root">
                        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-3">
                                                Nama Obat</th>
                                            <th scope="col"
                                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Batch</th>
                                            <th scope="col"
                                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Harga</th>
                                            <th scope="col"
                                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">ED</th>
                                            <th scope="col"
                                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Stok</th>
                                            <th scope="col"
                                                class="px-3 py-3.5 text-center text-sm font-semibold text-gray-900">Quantity
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white" id="obatTableBody">
                                        @foreach ($obats as $obat)
                                            <tr class="obat-row" data-nama="{{ strtolower($obat->nama_obat) }}"
                                                data-batch="{{ strtolower($obat->no_batch) }}">
                                                <td
                                                    class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-3">
                                                    {{ $obat->nama_obat }}</td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                    {{ $obat->no_batch }}</td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Rp
                                                    {{ number_format($obat->harga, 0, ',', '.') }}</td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                    {{ \Carbon\Carbon::parse($obat->ed)->isoFormat('MMM YYYY') }}</td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                    {{ $obat->quantity }} {{ $obat->kemasan }}</td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-center text-gray-500">
                                                    <input type="number" x-model.number="pesanan[{{ $obat->id }}]"
                                                        min="0" max="{{ $obat->quantity }}"
                                                        class="w-24 text-center rounded-md border-gray-300 shadow-md focus:border-blue-500 focus:ring-blue-500">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <template x-for="(quantity, id) in pesanan" :key="id">
                    <div x-show="quantity > 0">
                        <input type="hidden" :name="`pesanan[${id}][id]`" :value="id">
                        <input type="hidden" :name="`pesanan[${id}][quantity]`" :value="quantity">
                    </div>
                </template>

                <div class="border-t border-gray-200 pt-6 flex flex-col items-end gap-4">
                    <div class="text-right">
                        <span class="text-sm text-gray-500">Total Belanja</span>
                        <p class="text-2xl font-bold text-gray-900" x-text="formatCurrency(total)"></p>
                    </div>
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-blue-600 text-white font-semibold text-sm rounded-lg hover:bg-blue-700 transition-colors shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="icon-save"></i>
                        <span>Buat & Simpan Faktur</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function fakturForm() {
            return {
                searchQuery: '',
                pesanan: {},
                total: 0,
                obats: {!! $obats->mapWithKeys(fn($obat) => [$obat->id => ['harga' => $obat->harga]])->toJson() !!},

                calculateTotal() {
                    let newTotal = 0;
                    for (const id in this.pesanan) {
                        const quantity = parseInt(this.pesanan[id], 10) || 0;
                        if (quantity > 0 && this.obats[id]) {
                            newTotal += quantity * this.obats[id].harga;
                        }
                    }
                    this.total = newTotal;
                },

                formatCurrency(value) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    }).format(value);
                },

                filterTable() {
                    const query = this.searchQuery.toLowerCase();
                    document.querySelectorAll('.obat-row').forEach(row => {
                        const nama = row.dataset.nama;
                        const batch = row.dataset.batch;
                        if (nama.includes(query) || batch.includes(query)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                }
            }
        }
    </script>
@endsection
