<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Distribusi Obat | Dashboard</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="icon" href="{{ asset('assets/images/logo.jpeg') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/icon/style.css') }}">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }

        .swal2-toast {
            box-shadow: 0 0 1rem rgba(0, 0, 0, .1) !important;
            border-radius: 0.5rem !important;
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-800">

    <div x-data="{ sidebarOpen: window.innerWidth > 1024, activeSubmenu: '', userDropdownOpen: false, modalOpen: false, modalData: {} }" class="flex min-h-screen">

        @include('layouts.sidebar')

        <div class="flex-1 flex flex-col transition-all duration-300">
            <header class="bg-white shadow-sm sticky top-0 z-20">
                <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-700 lg:hidden">
                        <i class="icon-menu-left text-2xl"></i>
                    </button>
                    <div class="flex-1"></div>
                    <div class="relative">
                        <button @click="userDropdownOpen = !userDropdownOpen" @click.away="userDropdownOpen = false"
                            class="flex items-center gap-3 text-left">
                            <div class="hidden sm:flex flex-col items-end">
                                <span class="font-semibold text-sm text-gray-700">{{ Auth::user()->name }}</span>
                                <span class="text-xs text-gray-500 capitalize">{{ Auth::user()->role }}</span>
                            </div>
                            <img class="h-9 w-9 rounded-full object-cover"
                                src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0D8ABC&color=fff"
                                alt="User avatar">
                        </button>
                        <div x-show="userDropdownOpen" x-cloak x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 transform scale-100"
                            x-transition:leave-end="opacity-0 transform scale-95"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-30">
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <a href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="icon-log-out text-gray-500"></i>
                                <span>Log Out</span>
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')

    <script>
        @if (session('success'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        @endif

        @if (session('error'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: '{{ session('error') }}',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true
            });
        @endif
    </script>
</body>

</html>
