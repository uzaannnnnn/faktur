@extends('layouts.app')

@section('content')
    <div x-data="usersPage()">
        <div class="space-y-6">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <h1 class="text-2xl font-semibold text-gray-900">Manajemen Pengguna</h1>
                <a href="add-user"
                    class="inline-flex items-center justify-center gap-2 w-full sm:w-auto px-4 py-2 bg-blue-600 text-white font-semibold text-sm rounded-lg hover:bg-blue-700 transition-colors whitespace-nowrap">
                    <i class="icon-plus"></i>
                    <span>Tambah User</span>
                </a>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-md">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">#</th>
                                <th scope="col" class="px-6 py-3">Nama & Status</th>
                                <th scope="col" class="px-6 py-3">Email</th>
                                <th scope="col" class="px-6 py-3">Role</th>
                                <th scope="col" class="px-6 py-3 text-right">Tagihan</th>
                                <th scope="col" class="px-6 py-3">Sales</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                            @if ($user->status === 'aktif')
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                            @elseif ($user->status === 'block')
                                                <button
                                                    @click="openModal('confirm', {
                                                    action: '{{ route('users.aktifkan', $user->id) }}',
                                                    method: 'PUT',
                                                    title: 'Aktifkan Pengguna',
                                                    message: 'Anda yakin ingin mengaktifkan kembali pengguna \'{{ $user->name }}\'?',
                                                    buttonText: 'Ya, Aktifkan',
                                                    buttonClass: 'bg-green-600 hover:bg-green-700'
                                                })"
                                                    type="button"
                                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 hover:bg-red-200 transition-colors">
                                                    Block
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">{{ $user->email }}</td>
                                    <td class="px-6 py-4 capitalize">{{ $user->role }}</td>
                                    <td class="px-6 py-4 text-right">
                                        @if ($user->tagihan > 0)
                                            <a href="{{ route('orders', $user->id) }}" target="_blank"
                                                class="font-medium text-blue-600 hover:underline">Rp{{ number_format($user->tagihan, 0, ',', '.') }}</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($user->status === 'block')
                                            @if ($user->sales)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">{{ $user->sales }}</span>
                                            @else
                                                <button
                                                    @click="openModal('input', {
                                                    action: '{{ route('users.tambahSales') }}',
                                                    method: 'POST',
                                                    userId: {{ $user->id }},
                                                    title: 'Tambah Sales untuk {{ $user->name }}',
                                                    label: 'Nama Sales',
                                                    inputName: 'sales',
                                                    buttonText: 'Simpan Sales',
                                                    buttonClass: 'bg-blue-600 hover:bg-blue-700'
                                                })"
                                                    type="button"
                                                    class="text-amber-600 hover:text-amber-900 text-xs font-semibold">
                                                    Tambah Sales
                                                </button>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-10 text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <i class="icon-users text-4xl mb-2"></i>
                                            <span>Belum ada data pengguna yang terdaftar.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($users->hasPages())
                    <div class="mt-6">
                        {{ $users->links() }}
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
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form :action="modalData.action" method="POST">
                        @csrf
                        <template x-if="modalData.method === 'PUT'">
                            @method('PUT')
                        </template>
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" x-text="modalData.title"></h3>
                            <div class="mt-4">
                                <div x-show="modalType === 'confirm'">
                                    <p class="text-sm text-gray-500" x-text="modalData.message"></p>
                                </div>
                                <div x-show="modalType === 'input'">
                                    <input type="hidden" name="user_id" :value="modalData.userId">
                                    <label :for="modalData.inputName" class="block text-sm font-medium text-gray-700"
                                        x-text="modalData.label"></label>
                                    <input type="text" :name="modalData.inputName" :id="modalData.inputName"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        required>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white sm:ml-3 sm:w-auto sm:text-sm"
                                :class="modalData.buttonClass" x-text="modalData.buttonText"></button>
                            <button @click="modalOpen = false" type="button"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function usersPage() {
            return {
                modalOpen: false,
                modalType: '', // 'confirm' or 'input'
                modalData: {},

                openModal(type, data) {
                    this.modalType = type;
                    this.modalData = data;
                    this.modalOpen = true;
                }
            }
        }
    </script>
@endsection
