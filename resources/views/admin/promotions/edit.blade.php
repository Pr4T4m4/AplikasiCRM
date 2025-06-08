@extends('layouts.app')

@section('title', 'Tambah Promosi Baru')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Tambah Promosi Baru</h1>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Oops!</strong>
            <span class="block sm:inline">Ada beberapa masalah dengan input Anda:</span>
            <ul class="mt-3 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white shadow-lg rounded-lg p-8">
        <form action="{{ route('admin.promotions.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nama Promosi:</label>
                    <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" value="{{ old('name') }}" required>
                    @error('name')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="type" class="block text-gray-700 text-sm font-bold mb-2">Tipe Promosi:</label>
                    <select name="type" id="type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('type') border-red-500 @enderror" required>
                        <option value="">Pilih Tipe</option>
                        {{-- Opsi tipe promosi yang diperbarui --}}
                        <option value="diskon" {{ old('type') == 'diskon' ? 'selected' : '' }}>Diskon</option>
                        <option value="gratis_ongkir" {{ old('type') == 'gratis_ongkir' ? 'selected' : '' }}>Gratis Ongkir</option>
                        <option value="cashback" {{ old('type') == 'cashback' ? 'selected' : '' }}>Cashback</option>
                        <option value="hadiah" {{ old('type') == 'hadiah' ? 'selected' : '' }}>Hadiah</option>
                        <option value="birthday" {{ old('type') == 'birthday' ? 'selected' : '' }}>Ulang Tahun</option>
                        <option value="tanggal_kembar" {{ old('type') == 'tanggal_kembar' ? 'selected' : '' }}>Tanggal Kembar</option>
                        <option value="umum" {{ old('type') == 'umum' ? 'selected' : '' }}>Umum</option>
                    </select>
                    @error('type')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Deskripsi:</label>
                <textarea name="description" id="description" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="start_date" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Mulai:</label>
                    <input type="date" name="start_date" id="start_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('start_date') border-red-500 @enderror" value="{{ old('start_date') }}">
                    @error('start_date')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="end_date" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Berakhir:</label>
                    <input type="date" name="end_date" id="end_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('end_date') border-red-500 @enderror" value="{{ old('end_date') }}">
                    @error('end_date')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="target_audience" class="block text-gray-700 text-sm font-bold mb-2">Target Audiens:</label>
                    <select name="target_audience" id="target_audience" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('target_audience') border-red-500 @enderror" required>
                        <option value="">Pilih Target</option>
                        <option value="all" {{ old('target_audience') == 'all' ? 'selected' : '' }}>Semua Anggota</option>
                        <option value="specific_tiers" {{ old('target_audience') == 'specific_tiers' ? 'selected' : '' }}>Tingkatan Spesifik</option>
                        <option value="new_members" {{ old('target_audience') == 'new_members' ? 'selected' : '' }}>Anggota Baru</option>
                        <option value="returning_members" {{ old('target_audience') == 'returning_members' ? 'selected' : '' }}>Anggota Kembali</option>
                    </select>
                    @error('target_audience')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="promo_code" class="block text-gray-700 text-sm font-bold mb-2">Kode Promosi (Opsional):</label>
                    <input type="text" name="promo_code" id="promo_code" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('promo_code') border-red-500 @enderror" value="{{ old('promo_code') }}">
                    @error('promo_code')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="terms_and_conditions" class="block text-gray-700 text-sm font-bold mb-2">Syarat & Ketentuan:</label>
                <textarea name="terms_and_conditions" id="terms_and_conditions" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('terms_and_conditions') border-red-500 @enderror">{{ old('terms_and_conditions') }}</textarea>
                @error('terms_and_conditions')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status:</label>
                <select name="status" id="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('status') border-red-500 @enderror" required>
                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draf</option>
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-300">
                    Simpan Promosi
                </button>
                <a href="{{ route('admin.promotions.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-300">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
