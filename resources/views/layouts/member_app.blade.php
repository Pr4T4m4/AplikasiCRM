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
    </style>
</head>
<body class="antialiased">
    <div id="app">
        {{-- Header/Navbar untuk Member --}}
        <nav class="bg-white shadow-md p-4">
            <div class="container mx-auto flex justify-between items-center">
                <a href="{{ route('member.dashboard') }}" class="text-2xl font-bold text-indigo-600">
                    My Loyalty
                </a>
                <div class="flex items-center space-x-4">
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

        {{-- Konten Halaman --}}
        <main class="py-4">
            @yield('content')
        </main>
    </div>

    {{-- Stack untuk script JavaScript tambahan --}}
    @stack('scripts')
</body>
</html>
