@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Tambah Pengguna Baru</h1>
            <p class="mt-1 text-sm text-gray-600">Buat akun baru dan tentukan hak aksesnya dalam sistem.</p>
        </div>

        <form method="POST" action="{{ route('users.store') }}">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-md space-y-6">
                    <h3 class="text-lg font-semibold border-b border-gray-200 pb-4 text-gray-800">Informasi Akun</h3>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Username <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            maxlength="100"
                            class="mt-1 block w-full p-2 rounded-lg shadow-md {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }} focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Masukkan nama pengguna">
                        @error('name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email <span
                                    class="text-red-500">*</span></label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                class="mt-1 block w-full p-2 rounded-lg shadow-md {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }} focus:border-blue-500 focus:ring-blue-500"
                                placeholder="contoh@email.com">
                            @error('email')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Password <span
                                    class="text-red-500">*</span></label>
                            <input type="password" name="password" id="password" required
                                class="mt-1 block w-full p-2 rounded-lg shadow-md {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }} focus:border-blue-500 focus:ring-blue-500"
                                placeholder="••••••••">
                            @error('password')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat <span
                                class="text-gray-400">(Opsional)</span></label>
                        <textarea name="alamat" id="alamat" rows="3"
                            class="mt-1 block w-full p-2 rounded-lg shadow-md {{ $errors->has('alamat') ? 'border-red-500' : 'border-gray-300' }} focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">Role / Hak Akses <span
                                class="text-red-500">*</span></label>
                        <select id="role" name="role" required
                            class="mt-1 block w-full p-2 rounded-lg shadow-md {{ $errors->has('role') ? 'border-red-500' : 'border-gray-300' }} focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Pilih Role</option>
                            <option value="admin" @if (old('role') == 'admin') selected @endif>Admin</option>
                            <option value="fakturis" @if (old('role') == 'fakturis') selected @endif>Fakturis</option>
                            <option value="gudang" @if (old('role') == 'gudang') selected @endif>Gudang</option>
                            <option value="customer" @if (old('role') == 'customer') selected @endif>Customer</option>
                        </select>
                        @error('role')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-xl shadow-md space-y-4 sticky top-24">
                        <h3 class="text-lg font-semibold text-gray-800">Aksi</h3>
                        <p class="text-sm text-gray-500">Setelah semua data yang diperlukan terisi, klik tombol di bawah
                            untuk menyimpan pengguna baru.</p>
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="icon-user-plus"></i>
                            <span>Simpan Pengguna</span>
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>
@endsection
