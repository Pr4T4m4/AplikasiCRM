<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | @yield('title', 'CRM')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @stack('styles')
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <nav class="bg-indigo-700 p-4 text-white shadow-md flex justify-between items-center">
        <div class="flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold">Admin Panel</a>
        </div>
        <div class="flex items-center">
            {{-- Menggunakan full_name dari user admin yang sedang login --}}
            <span class="mr-4 text-sm">Welcome, {{ Auth::guard('admin')->user()->full_name ?? 'Admin' }}</span>
            <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-indigo-800 hover:bg-indigo-900 text-white text-sm font-bold py-2 px-3 rounded focus:outline-none focus:shadow-outline transition duration-300">
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <div class="flex flex-col md:flex-row min-h-screen">
        <aside class="w-full md:w-64 bg-gray-800 text-gray-100 shadow-lg">
            <div class="p-4">
                <h3 class="font-semibold text-lg mb-4">Navigation</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 rounded-md hover:bg-gray-700 transition duration-200">
                            <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="border-t border-gray-700 my-2 pt-2"></li>
                    <li>
                        <a href="{{ route('admin.members.index') }}" class="flex items-center px-4 py-2 rounded-md hover:bg-gray-700 transition duration-200">
                            <i class="fas fa-users mr-2"></i> Kelola Anggota
                        </a>
                    </li>
                    {{-- Mengganti "Sesuaikan Poin" dengan "Transaksi" --}}
                    <li>
                        <a href="{{ route('admin.transactions.index') }}" class="flex items-center px-4 py-2 rounded-md hover:bg-gray-700 transition duration-200">
                            <i class="fas fa-exchange-alt mr-2"></i> Transaksi
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.tiers.index') }}" class="flex items-center px-4 py-2 rounded-md hover:bg-gray-700 transition duration-200">
                            <i class="fas fa-trophy mr-2"></i> Kelola Tingkatan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.rewards.index') }}" class="flex items-center px-4 py-2 rounded-md hover:bg-gray-700 transition duration-200">
                            <i class="fas fa-gift mr-2"></i> Kelola Hadiah
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.promotions.index') }}" class="flex items-center px-4 py-2 rounded-md hover:bg-gray-700 transition duration-200">
                            <i class="fas fa-bullhorn mr-2"></i> Kelola Promosi
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.point-rules.index') }}" class="flex items-center px-4 py-2 rounded-md hover:bg-gray-700 transition duration-200">
                            <i class="fas fa-cogs mr-2"></i> Aturan Poin
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.notifications.create') }}" class="flex items-center px-4 py-2 rounded-md hover:bg-gray-700 transition duration-200">
                            <i class="fas fa-bell mr-2"></i> Kirim Notifikasi
                        </a>
                    </li>
                    <li class="border-t border-gray-700 my-2 pt-2"></li>
                    <li>
                        <a href="{{ route('admin.reports.index') }}" class="flex items-center px-4 py-2 rounded-md hover:bg-gray-700 transition duration-200">
                            <i class="fas fa-chart-bar mr-2"></i> Laporan & Analisis
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <main class="flex-grow p-6">
            @yield('content')
        </main>
    </div>

    <footer class="bg-gray-800 text-white p-4 text-center text-sm">
        &copy; {{ date('Y') }} Aplikasi CRM Admin. All rights reserved.
    </footer>

    @stack('scripts')
</body>
</html>
