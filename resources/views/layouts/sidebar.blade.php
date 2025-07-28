<aside class="bg-gray-800 text-gray-200 flex flex-col transition-all duration-300 ease-in-out z-30"
    :class="sidebarOpen ? 'w-64' : 'w-20'">

    <div class="flex items-center justify-between h-16 px-6 border-b border-gray-700">
        <a href="{{ url('/') }}" class="flex items-center gap-3" :class="!sidebarOpen && 'justify-center w-full'">
            <img src="{{ asset('assets/images/logo.jpeg') }}" alt="Icon" class="h-10 rounded w-auto">
            <span x-show="sidebarOpen" x-transition:enter="transition-opacity duration-300"
                x-transition:leave="transition-opacity duration-100" class="font-bold whitespace-nowrap">Distribusi
                Obat</span>
        </a>
        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-400 hover:text-white hidden lg:block">
            <i class="icon-menu-left text-xl"></i>
        </button>
    </div>

    <nav class="flex-1 px-3 py-4 space-y-4 overflow-y-auto">
        @php $role = Auth::user()->role; @endphp

        <div>
            <span x-show="sidebarOpen" class="px-3 text-xs font-semibold uppercase text-gray-500">Main Home</span>
            <ul class="mt-2 space-y-1">
                <li><a href="/{{ $role }}/dashboard"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-md hover:bg-gray-700 transition-colors">
                        <i class="icon-grid w-5 text-center text-lg"></i>
                        <span x-show="sidebarOpen" class="font-medium">Dashboard</span>
                    </a></li>
            </ul>
        </div>

        <div class="space-y-2">
            @if ($role === 'admin' || $role === 'gudang')
                <div>
                    <button @click="activeSubmenu = activeSubmenu === 'obat' ? '' : 'obat'"
                        class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-md hover:bg-gray-700 transition-colors">
                        <div class="flex items-center gap-3">
                            <i class="icon-aid-kit w-5 text-center text-lg"></i>
                            <span x-show="sidebarOpen" class="font-medium">Obat-Obatan</span>
                        </div>
                        <i x-show="sidebarOpen" class="icon-chevron-down text-xs transition-transform"
                            :class="activeSubmenu === 'obat' && 'rotate-180'"></i>
                    </button>
                    <ul x-show="activeSubmenu === 'obat'" x-cloak class="mt-1 space-y-1"
                        :class="sidebarOpen ? 'pl-8' : 'pl-0 bg-gray-900 rounded-md py-1 mt-2'">
                        @if ($role === 'gudang')
                            <li><a href="/gudangAdmin/add-obat"
                                    class="flex items-center gap-3 px-3 py-2 rounded-md text-sm hover:bg-gray-700"><span
                                        x-show="sidebarOpen">Tambah Obat</span><span x-show="!sidebarOpen"
                                        class="w-full text-center text-xs">TO</span></a></li>
                        @endif
                        <li><a href="/gudangAdmin/obat"
                                class="flex items-center gap-3 px-3 py-2 rounded-md text-sm hover:bg-gray-700"><span
                                    x-show="sidebarOpen">Data Obat</span><span x-show="!sidebarOpen"
                                    class="w-full text-center text-xs">DO</span></a></li>
                    </ul>
                </div>
            @endif

            @if ($role === 'admin')
                <ul class="space-y-1">
                    <li><a href="/{{ $role }}/faktur"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-md hover:bg-gray-700 transition-colors">
                            <i class="icon-file-text w-5 text-center text-lg"></i>
                            <span x-show="sidebarOpen" class="font-medium">Faktur</span>
                        </a></li>
                    <li><a href="/{{ $role }}/orders"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-md hover:bg-gray-700 transition-colors">
                            <i class="icon-shopping-cart w-5 text-center text-lg"></i>
                            <span x-show="sidebarOpen" class="font-medium">Orders</span>
                        </a></li>
                </ul>
                <div>
                    <button @click="activeSubmenu = activeSubmenu === 'users' ? '' : 'users'"
                        class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-md hover:bg-gray-700 transition-colors">
                        <div class="flex items-center gap-3">
                            <i class="icon-user w-5 text-center text-lg"></i>
                            <span x-show="sidebarOpen" class="font-medium">Data Users</span>
                        </div>
                        <i x-show="sidebarOpen" class="icon-chevron-down text-xs transition-transform"
                            :class="activeSubmenu === 'users' && 'rotate-180'"></i>
                    </button>
                    <ul x-show="activeSubmenu === 'users'" x-cloak class="mt-1 space-y-1"
                        :class="sidebarOpen ? 'pl-8' : 'pl-0 bg-gray-900 rounded-md py-1 mt-2'">
                        <li><a href="/{{ $role }}/add-user"
                                class="flex items-center gap-3 px-3 py-2 rounded-md text-sm hover:bg-gray-700"><span
                                    x-show="sidebarOpen">Tambah User</span><span x-show="!sidebarOpen"
                                    class="w-full text-center text-xs">TU</span></a></li>
                        <li><a href="/{{ $role }}/users"
                                class="flex items-center gap-3 px-3 py-2 rounded-md text-sm hover:bg-gray-700"><span
                                    x-show="sidebarOpen">Data Users</span><span x-show="!sidebarOpen"
                                    class="w-full text-center text-xs">DU</span></a></li>
                    </ul>
                </div>
            @endif
        </div>
    </nav>
</aside>
