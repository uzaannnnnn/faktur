@extends('layouts.app')

@section('content')
    <div class="space-y-8">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Halo Admin, Selamat Datang! 👋</h1>
            <p class="mt-1 text-sm text-gray-600">Berikut adalah ringkasan aktivitas dan data dari sistem distribusi obat.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            <a href="{{ route('obats') }}"
                class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow flex items-start gap-4">
                <div class="flex-shrink-0 h-12 w-12 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="icon-aid-kit text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Obat Aktif</p>
                    {{-- Menggunakan variabel dari controller --}}
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($totalObat) }}</p>
                </div>
            </a>

            <a href="/admin/faktur"
                class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow flex items-start gap-4">
                <div
                    class="flex-shrink-0 h-12 w-12 rounded-lg bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="icon-file-text text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Faktur</p>
                    {{-- Menggunakan variabel dari controller --}}
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($totalFaktur) }}</p>
                </div>
            </a>

            <a href="/admin/orders"
                class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow flex items-start gap-4">
                <div
                    class="flex-shrink-0 h-12 w-12 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center">
                    <i class="icon-shopping-cart text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Pesanan Bulan Ini</p>
                    {{-- Menggunakan variabel dari controller --}}
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($totalOrdersBulanIni) }}</p>
                </div>
            </a>

            <a href="/admin/users"
                class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow flex items-start gap-4">
                <div
                    class="flex-shrink-0 h-12 w-12 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center">
                    <i class="icon-users text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Jumlah Pengguna</p>
                    {{-- Menggunakan variabel dari controller --}}
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($totalUsers) }}</p>
                </div>
            </a>

        </div>

        <div class="bg-white p-6 rounded-xl shadow-md">
            <h3 class="text-lg font-semibold text-gray-800">Selamat Bekerja!</h3>
            <p class="mt-2 text-sm text-gray-600">Gunakan menu di samping untuk mengelola data obat, faktur, pesanan, dan
                pengguna. Jika ada notifikasi penting mengenai stok atau tanggal kadaluarsa, sistem akan memberitahu Anda
                secara otomatis saat Anda membuka halaman ini.</p>
        </div>
    </div>

    {{-- Bagian Script SweetAlert tetap sama, tidak perlu diubah --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if ($stokMinimum->isNotEmpty() || $hampirExpired->isNotEmpty())
                let alertHtml = '<div class="space-y-6 text-left">';

                @if ($stokMinimum->isNotEmpty())
                    alertHtml += `
                <div>
                    <h4 class="font-semibold text-lg text-yellow-600 mb-2">⚠️ Stok Minimum Ditemukan</h4>
                    <ul class="list-disc list-inside space-y-1 text-gray-700 max-h-40 overflow-y-auto pr-2">
                        @foreach ($stokMinimum as $obat)
                            <li class="text-sm">
                                <span>{{ $obat->nama_obat }}</span> — Sisa <span class="font-bold text-yellow-700">{{ $obat->quantity }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            `;
                @endif

                @if ($hampirExpired->isNotEmpty())
                    alertHtml += `
                <div>
                    <h4 class="font-semibold text-lg text-red-600 mb-2">⚠️ Obat Akan Segera Kadaluarsa</h4>
                     <ul class="list-disc list-inside space-y-1 text-gray-700 max-h-40 overflow-y-auto pr-2">
                        @foreach ($hampirExpired as $obat)
                             <li class="text-sm">
                                <span>{{ $obat->nama_obat }}</span> — Exp. <span class="font-bold text-red-700">{{ \Carbon\Carbon::parse($obat->ed)->isoFormat('DD MMM YYYY') }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            `;
                @endif

                alertHtml += '</div>';

                Swal.fire({
                    title: 'Pemberitahuan Penting',
                    html: alertHtml,
                    icon: 'warning',
                    width: '36rem',
                    confirmButtonText: 'Oke',
                    confirmButtonColor: '#3B82F6'
                });
            @endif
        });
    </script>
@endsection
