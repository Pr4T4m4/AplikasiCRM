<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - My Brand Loyalty</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen py-12">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-lg">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Daftar Akun Loyalitas</h2>

        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="full_name" class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
                <input type="text" id="full_name" name="full_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('full_name') border-red-500 @enderror" value="{{ old('full_name') }}" required autofocus>
                @error('full_name') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" id="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror" value="{{ old('email') }}" required>
                @error('email') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
            </div>
            <div class="mb-4">
                <label for="phone_number" class="block text-gray-700 text-sm font-bold mb-2">Nomor Telepon</label>
                <input type="text" id="phone_number" name="phone_number" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('phone_number') border-red-500 @enderror" value="{{ old('phone_number') }}" required>
                @error('phone_number') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" id="password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror" required autocomplete="new-password">
                @error('password') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
            </div>
            <div class="mb-4">
                <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required autocomplete="new-password">
            </div>

            <div class="mb-4">
                <label for="gender" class="block text-gray-700 text-sm font-bold mb-2">Jenis Kelamin</label>
                <select id="gender" name="gender" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('gender') border-red-500 @enderror">
                    <option value="">Pilih</option>
                    <option value="Laki-laki" {{ old('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    <option value="Tidak_Disebutkan" {{ old('gender') == 'Tidak_Disebutkan' ? 'selected' : '' }}>Tidak Disebutkan</option>
                </select>
                @error('gender') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="date_of_birth" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Lahir</label>
                <input type="date" id="date_of_birth" name="date_of_birth" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('date_of_birth') border-red-500 @enderror" value="{{ old('date_of_birth') }}">
                @error('date_of_birth') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="city" class="block text-gray-700 text-sm font-bold mb-2">Kota</label>
                <input type="text" id="city" name="city" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('city') border-red-500 @enderror" value="{{ old('city') }}">
                @error('city') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @endError
            </div>

            <div class="mb-4">
                <label for="province" class="block text-gray-700 text-sm font-bold mb-2">Provinsi</label>
                <input type="text" id="province" name="province" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('province') border-red-500 @enderror" value="{{ old('province') }}">
                @error('province') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @endError
            </div>

            <div class="mb-6">
                <label for="address_line1" class="block text-gray-700 text-sm font-bold mb-2">Alamat (Baris 1)</label>
                <input type="text" id="address_line1" name="address_line1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('address_line1') border-red-500 @enderror" value="{{ old('address_line1') }}">
                @error('address_line1') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6 flex items-center">
                <input type="checkbox" id="terms" name="terms" class="mr-2 leading-tight" {{ old('terms') ? 'checked' : '' }} required>
                <label for="terms" class="text-gray-700 text-sm">Saya menyetujui <a href="{{ route('terms') }}" class="text-blue-500 hover:text-blue-800" target="_blank">Syarat dan Ketentuan</a>.</label>
                @error('terms') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Daftar
                </button>
                <a href="{{ route('login') }}" class="inline-block align-baseline font-bold text-sm text-gray-600 hover:text-gray-800">
                    Sudah punya akun? Login
                </a>
            </div>
        </form>
    </div>
</body>
</html>