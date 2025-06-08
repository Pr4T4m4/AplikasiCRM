<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Welcome to My Brand Loyalty</title>

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
        .gradient-background {
            background: linear-gradient(to right, #6EE7B7, #3B82F6);
        }
    </style>
</head>
<body class="antialiased">
    <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
        @if (Route::has('login'))
            <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Log in</a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">Register</a>
                    @endif
                @endauth
            </div>
        @endif

        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-center pt-8 sm:pt-0">
                <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white">
                    My Brand Loyalty Program
                </h1>
            </div>

            <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-2">
                    <div class="p-6 border-t border-gray-200 dark:border-gray-700 md:border-t-0 md:border-l">
                        <div class="flex items-center">
                            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-500"><path d="M12 6.253v13.5m0-13.5c-4.142 0-7.5 3.103-7.5 6.931 0 2.678 2.6 5.204 7.5 7.072m0-13.5c4.142 0 7.5 3.103 7.5 6.931 0 2.678-2.6 5.204-7.5 7.072L12 6.253z"></path></svg>
                            <div class="ml-4 text-lg leading-7 font-semibold"><a href="{{ route('register') }}" class="underline text-gray-900 dark:text-white">Daftar Sekarang!</a></div>
                        </div>

                        <div class="ml-12">
                            <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                Bergabunglah dengan program loyalitas kami dan mulai kumpulkan poin untuk setiap pembelian Anda. Dapatkan akses ke penawaran eksklusif, diskon, dan hadiah menarik! Proses pendaftaran cepat dan mudah.
                            </div>
                        </div>
                    </div>

                    <div class="p-6 border-t border-gray-200 dark:border-gray-700 md:border-l">
                        <div class="flex items-center">
                            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-500"><path d="M3 9v6m0 0h6m-6 0h6m0 0v-6m0 6l3 3m-3-3l3-3m-3 3h12a2 2 0 002-2V7a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                            <div class="ml-4 text-lg leading-7 font-semibold"><a href="{{ route('login') }}" class="underline text-gray-900 dark:text-white">Sudah Punya Akun? Login</a></div>
                        </div>

                        <div class="ml-12">
                            <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                Masuk ke akun Anda untuk melihat poin hadiah terkini, melacak riwayat pembelian, dan menukarkan poin dengan hadiah favorit Anda. Nikmati pengalaman loyalitas yang mulus.
                            </div>
                        </div>
                    </div>

                    <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center">
                            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-500"><path d="M7 8h10M7 12h10M7 16h10M4 21h16a2 2 0 002-2V5a2 2 0 00-2-2H4a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            <div class="ml-4 text-lg leading-7 font-semibold"><a href="{{ route('terms') }}" class="underline text-gray-900 dark:text-white">Syarat & Ketentuan</a></div>
                        </div>

                        <div class="ml-12">
                            <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                Pahami kebijakan dan aturan program loyalitas kami. Kami berkomitmen untuk transparansi dan memberikan pengalaman terbaik untuk anggota kami.
                            </div>
                        </div>
                    </div>

                    <div class="p-6 border-t border-gray-200 dark:border-gray-700 md:border-l">
                        <div class="flex items-center">
                            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-500"><path d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            <div class="ml-4 text-lg leading-7 font-semibold"><a href="{{ route('support.index') }}" class="underline text-gray-900 dark:text-white">Butuh Bantuan?</a></div>
                        </div>

                        <div class="ml-12">
                            <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                Tim dukungan kami siap membantu Anda dengan pertanyaan atau masalah apa pun terkait akun atau program loyalitas Anda.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-center mt-4 sm:items-center sm:justify-between">
                <div class="text-center text-sm text-gray-500 sm:text-left">
                    <div class="flex items-center">
                        Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>