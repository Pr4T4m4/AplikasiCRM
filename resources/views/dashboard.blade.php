<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - My Brand Loyalty</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100 p-8">
    <div class="container mx-auto">
        {{-- Mengganti $member menjadi $user --}}
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Selamat Datang, {{ $user->full_name }}!</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-blue-600 text-white p-4 rounded-lg shadow-md mb-6 text-center">
            <h2 class="text-2xl font-bold">Welcome to My Brand</h2>
            <p class="text-lg">Your Loyalty & Rewards Platform</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Status Loyalitas Anda</h2>
            <div class="flex items-center justify-between mb-2">
                {{-- Mengganti $member menjadi $user dan menggunakan $currentTier dari controller --}}
                <p class="text-lg">Level Kamu: <span class="font-bold text-blue-600">{{ $currentTier->name ?? 'Belum Ada Tier' }}</span></p>
                <p class="text-lg">Poin Hadiah: <span class="font-bold text-purple-600">{{ $user->current_points }}</span></p>
            </div>

            {{-- Logika untuk menampilkan progress bar dan pesan, disesuaikan dengan controller --}}
            <div class="mb-4">
                @if($isHighestTier)
                    <p class="text-sm text-gray-600 mb-1">
                        Anda sudah di level tertinggi ({{ $currentTier->name ?? 'Tidak Diketahui' }}). Selamat!
                    </p>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                        <div class="bg-green-500 h-2.5 rounded-full" style="width: 100%"></div>
                    </div>
                @else {{-- Ada nextTier --}}
                    <p class="text-sm text-gray-600 mb-1">
                        Progres menuju {{ $nextTier->name }}: {{ round($progressPercentage, 2) }}%
                    </p>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $progressPercentage }}%"></div>
                    </div>
                    <p class="text-sm text-gray-600 mt-2">
                        @if ($pointsToNextLevel > 0)
                            Perlu {{ $pointsToNextLevel }} poin lagi untuk mencapai level {{ $nextTier->name }} selanjutnya.
                        @else
                            Selamat! Anda telah mencapai syarat untuk level {{ $nextTier->name }}.
                            {{-- Anda bisa menambahkan pesan lain di sini jika tier belum otomatis diperbarui --}}
                        @endif
                    </p>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
            <a href="{{ route('member.rewards.index') }}" class="flex flex-col items-center justify-center bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition duration-200 text-center">
                <i class="fas fa-gift text-blue-500 text-3xl mb-2"></i> <p class="text-sm font-semibold text-gray-700">Tukar Point Redeem</p>
            </a>
            
            <a href="{{ route('member.product_ratings.index') }}" class="flex flex-col items-center justify-center bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition duration-200 text-center">
                <i class="fas fa-star text-yellow-500 text-3xl mb-2"></i> <p class="text-sm font-semibold text-gray-700">Rating Produk</p>
            </a>
            <a href="{{ route('member.history.index') }}" class="flex flex-col items-center justify-center bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition duration-200 text-center">
                <i class="fas fa-history text-purple-500 text-3xl mb-2"></i> <p class="text-sm font-semibold text-gray-700">Riwayat</p>
            </a>
            <a href="{{ route('support.index') }}" class="flex flex-col items-center justify-center bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition duration-200 text-center">
                <i class="fas fa-ellipsis-h text-gray-500 text-3xl mb-2"></i> <p class="text-sm font-semibold text-gray-700">Lain-Lain</p>
            </a>
        </div>

        <div class="mt-8 text-center">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Logout
                </button>
            </form>
        </div>
    </div>
</body>
</html>