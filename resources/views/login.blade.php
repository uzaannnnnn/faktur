<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login | Aplikasi Modern</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Google Fonts (Opsional, untuk font yang lebih bagus) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* Menggunakan font Inter sebagai default jika di-load */
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Style tambahan untuk toast agar terlihat lebih baik dengan Tailwind */
        .swal2-toast {
            box-shadow: 0 0 1rem rgba(0, 0, 0, .15) !important;
            border-radius: 0.5rem !important;
        }
    </style>
</head>

<body class="bg-gray-100">

    <!-- Container Utama: Menggunakan flexbox untuk memusatkan form di tengah layar -->
    <div class="min-h-screen flex items-center justify-center p-4">

        <!-- Kartu Login -->
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8 space-y-6">

            <!-- Header -->
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-800">Selamat Datang</h1>
                <p class="text-gray-500 mt-2">Login untuk Mengelola Distribusi Obat</p>
            </div>

            <!-- Form Login -->
            <form method="POST" action="{{ url('/login') }}" class="space-y-6">
                @csrf

                <!-- Input Email -->
                <div>
                    <label for="email" class="text-sm font-medium text-gray-700 block mb-2">Alamat Email</label>
                    <input type="email"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition duration-200"
                        id="email" name="email" value="{{ old('email') }}" placeholder="contoh@email.com"
                        required />
                </div>

                <!-- Input Password -->
                <div>
                    <label for="password" class="text-sm font-medium text-gray-700 block mb-2">Password</label>
                    <input type="password"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition duration-200"
                        id="password" name="password" placeholder="••••••••" required />
                </div>

                <!-- Tombol Submit -->
                <div>
                    <button type="submit"
                        class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 transition-all duration-300">
                        Login
                    </button>
                </div>
            </form>

        </div>
    </div>

    <!-- ========================================================== -->
    <!-- PERUBAHAN UTAMA ADA DI SINI -->
    <!-- ========================================================== -->
    <script>
        // Cek jika ada error dari validasi Laravel
        @if ($errors->any())
            Swal.fire({
                // Konfigurasi untuk Toast
                toast: true,
                position: 'top-end', // Posisi di pojok kanan atas
                icon: 'error',
                title: '{{ $errors->first() }}', // Pesan error sebagai judul

                // Opsi tambahan untuk UX yang lebih baik
                showConfirmButton: false, // Sembunyikan tombol "OK"
                timer: 4000, // Toast akan hilang dalam 4 detik
                timerProgressBar: true, // Tampilkan progress bar waktu

                // Event ini membuat timer berhenti jika mouse diarahkan ke toast
                // dan lanjut lagi jika mouse digeser pergi. Berguna jika pesan error panjang.
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
        @endif
    </script>
    <!-- ========================================================== -->

</body>

</html>
