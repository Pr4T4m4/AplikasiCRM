<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Aplikasi Loyalitas')</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://kit.fontawesome.com/4a2b166070.js" crossorigin="anonymous"></script> {{-- Ganti dengan kode Font Awesome Anda jika berbeda --}}

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6; /* Tailwind gray-100 */
        }
        /* Tambahkan gaya kustom lainnya di sini jika diperlukan */
        /* Pastikan konten tidak tertutup oleh navbar fixed */
        .content-padding {
            padding-top: 64px; /* Sesuaikan dengan tinggi navbar Anda, misalnya 16 (4rem) * 4px = 64px */
        }
    </style>
</head>
<body class="antialiased">
    <div id="app">
        {{-- Header/Navbar untuk Member (Fixed & Floating) --}}
        <nav class="bg-white shadow-lg fixed top-0 left-0 right-0 z-50 p-4">
            <div class="container mx-auto flex flex-wrap justify-between items-center">
                {{-- Logo dan Tombol Toggle (untuk mobile) --}}
                <div class="flex items-center justify-between w-full md:w-auto">
                    <a href="{{ route('member.dashboard') }}" class="text-2xl font-bold text-indigo-600">
                        My Loyalty
                    </a>
                    {{-- Mobile Menu Button (Hamburger/3-dot icon) --}}
                    <button id="mobile-menu-toggle" class="md:hidden text-gray-700 hover:text-indigo-600 focus:outline-none focus:text-indigo-600">
                        <i class="fas fa-bars text-xl"></i> {{-- Ikon hamburger --}}
                    </button>
                </div>

                {{-- Desktop Menu Links dan Logout --}}
                <div class="hidden md:flex items-center space-x-4 w-auto">
                    <a href="{{ route('member.dashboard') }}" class="text-gray-700 hover:text-indigo-600 font-medium">Dashboard</a>
                    <a href="{{ route('member.rewards.index') }}" class="text-gray-700 hover:text-indigo-600 font-medium">Hadiah</a>
                    <a href="{{ route('member.history.index') }}" class="text-gray-700 hover:text-indigo-600 font-medium">Riwayat Poin</a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm font-medium transition duration-200">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        {{-- Mobile Menu (Hidden by default, toggled by JS) --}}
        {{-- Posisi di bawah navbar utama, mengambil lebar penuh --}}
        <div id="mobile-menu" class="hidden md:hidden bg-white shadow-md absolute top-16 left-0 right-0 z-40 py-4">
            <div class="container mx-auto flex flex-col space-y-2 px-4">
                <a href="{{ route('member.dashboard') }}" class="block text-gray-700 hover:text-indigo-600 font-medium py-2">Dashboard</a>
                <a href="{{ route('member.rewards.index') }}" class="block text-gray-700 hover:text-indigo-600 font-medium py-2">Hadiah</a>
                <a href="{{ route('member.history.index') }}" class="block text-gray-700 hover:text-indigo-600 font-medium py-2">Riwayat Poin</a>
                <form action="{{ route('logout') }}" method="POST" class="block">
                    @csrf
                    <button type="submit" class="w-full text-left bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm font-medium transition duration-200">
                        Logout
                    </button>
                </form>
            </div>
        </div>

        {{-- Konten Halaman (dengan padding untuk navbar fixed) --}}
        <main class="content-padding py-4">
            @yield('content')
        </main>
    </div>

    {{-- Stack untuk script JavaScript tambahan --}}
    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
            const mobileMenu = document.getElementById('mobile-menu');

            if (mobileMenuToggle && mobileMenu) {
                mobileMenuToggle.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });

                // Optional: Close mobile menu when a link is clicked
                mobileMenu.querySelectorAll('a').forEach(link => {
                    link.addEventListener('click', () => {
                        mobileMenu.classList.add('hidden');
                    });
                });
            }
        });
    </script>
</body>
</html>
