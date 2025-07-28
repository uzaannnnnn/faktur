@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Tambah Data Obat Baru</h1>
            <p class="mt-1 text-sm text-gray-600">Pastikan semua data diisi dengan benar sebelum disimpan.</p>
        </div>

        <form method="POST" action="{{ route('obats.store') }}">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-md space-y-6">
                    <h3 class="text-lg font-semibold border-b border-gray-200 pb-4 text-gray-800">Detail Obat</h3>

                    <div>
                        <label for="nama_obat" class="block text-sm font-medium text-gray-700">Nama Obat <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="nama_obat" id="nama_obat" value="{{ old('nama_obat') }}" required
                            maxlength="100"
                            class="mt-1 block w-full p-2 rounded-lg shadow-md {{ $errors->has('nama_obat') ? 'border-red-500' : 'border-gray-300' }} focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Contoh: Paracetamol 500mg">
                        @error('nama_obat')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="no_batch" class="block text-sm font-medium text-gray-700">No. Batch <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="no_batch" id="no_batch" value="{{ old('no_batch') }}" required
                                maxlength="50"
                                class="mt-1 block w-full p-2 rounded-lg shadow-md {{ $errors->has('no_batch') ? 'border-red-500' : 'border-gray-300' }} focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Masukkan Nomor Batch">
                            @error('no_batch')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="harga" class="block text-sm font-medium text-gray-700">Harga (Rp) <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="harga" id="harga" value="{{ old('harga') }}" required
                                min="0" step="1"
                                class="mt-1 block w-full p-2 rounded-lg shadow-md {{ $errors->has('harga') ? 'border-red-500' : 'border-gray-300' }} focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Contoh: 15000">
                            @error('harga')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="kemasan" class="block text-sm font-medium text-gray-700">Kemasan <span
                                    class="text-red-500">*</span></label>
                            <select id="kemasan" name="kemasan" required
                                class="mt-1 block w-full p-2 rounded-lg shadow-md {{ $errors->has('kemasan') ? 'border-red-500' : 'border-gray-300' }} focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Pilih Kemasan</option>
                                <option value="fls" @if (old('kemasan') == 'fls') selected @endif>Fls</option>
                                <option value="btl" @if (old('kemasan') == 'btl') selected @endif>Btl</option>
                                <option value="box" @if (old('kemasan') == 'box') selected @endif>Box</option>
                                <option value="tube" @if (old('kemasan') == 'tube') selected @endif>Tube</option>
                                <option value="strip" @if (old('kemasan') == 'strip') selected @endif>Strip</option>
                            </select>
                            @error('kemasan')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="quantity" id="quantity" value="{{ old('quantity') }}" required
                                class="mt-1 block w-full p-2 rounded-lg shadow-md {{ $errors->has('quantity') ? 'border-red-500' : 'border-gray-300' }} focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Jumlah stok masuk">
                            @error('quantity')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold border-b border-gray-200 pb-4 text-gray-800 pt-4">Informasi Tambahan
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="distributor" class="block text-sm font-medium text-gray-700">Distributor <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="distributor" id="distributor" value="{{ old('distributor') }}"
                                required
                                class="mt-1 block w-full p-2 rounded-lg shadow-md {{ $errors->has('distributor') ? 'border-red-500' : 'border-gray-300' }} focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Nama Distributor">
                            @error('distributor')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="pabrik" class="block text-sm font-medium text-gray-700">Pabrik <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="pabrik" id="pabrik" value="{{ old('pabrik') }}" required
                                class="mt-1 block w-full p-2 rounded-lg shadow-md {{ $errors->has('pabrik') ? 'border-red-500' : 'border-gray-300' }} focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Nama Pabrik">
                            @error('pabrik')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="tanggal_masuk" class="block text-sm font-medium text-gray-700">Tanggal Masuk <span
                                    class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_masuk" id="tanggal_masuk"
                                value="{{ old('tanggal_masuk') }}" required
                                class="mt-1 block w-full p-2 rounded-lg shadow-md {{ $errors->has('tanggal_masuk') ? 'border-red-500' : 'border-gray-300' }} focus:border-blue-500 focus:ring-blue-500">
                            @error('tanggal_masuk')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="ed" class="block text-sm font-medium text-gray-700">Expired Date <span
                                    class="text-red-500">*</span></label>
                            <input type="date" name="ed" id="ed" value="{{ old('ed') }}" required
                                class="mt-1 block w-full p-2 rounded-lg shadow-md {{ $errors->has('ed') ? 'border-red-500' : 'border-gray-300' }} focus:border-blue-500 focus:ring-blue-500">
                            @error('ed')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-xl shadow-md space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800">Aksi</h3>
                        <p class="text-sm text-gray-500">Setelah semua data terisi, klik tombol di bawah untuk menyimpan
                            data obat ke dalam sistem.</p>
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="icon-save"></i>
                            <span>Simpan Data Obat</span>
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>
@endsection
