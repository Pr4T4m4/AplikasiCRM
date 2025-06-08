@extends('layouts.app') {{-- Pastikan app.blade.php Anda ada di resources/views/layouts/app.blade.php --}}

@section('title', 'Detail Anggota')

@section('content')
<div class="container mx-auto p-6 bg-gray-100 min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg">
        <div class="flex justify-between items-center mb-6">
            {{-- Diperbarui: Menggunakan $user->full_name --}}
            <h1 class="text-3xl font-bold text-gray-800">Detail Anggota: {{ $user->full_name }}</h1>
            <a href="{{ route('admin.members.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Informasi Pribadi</h2>
                <div class="mb-3">
                    <p class="text-gray-600 font-semibold">Nama Lengkap:</p>
                    <p class="text-gray-900 text-lg">{{ $user->full_name }}</p>
                </div>
                <div class="mb-3">
                    <p class="text-gray-600 font-semibold">Email:</p>
                    <p class="text-gray-900 text-lg">{{ $user->email }}</p>
                </div>
                <div class="mb-3">
                    <p class="text-gray-600 font-semibold">Telepon:</p>
                    <p class="text-gray-900 text-lg">{{ $user->phone_number ?? '-' }}</p>
                </div>
                <div class="mb-3">
                    <p class="text-gray-600 font-semibold">Gender:</p>
                    <p class="text-gray-900 text-lg">{{ $user->gender ?? '-' }}</p>
                </div>
                <div class="mb-3">
                    <p class="text-gray-600 font-semibold">Tanggal Lahir:</p>
                    {{-- PERBAIKAN DI SINI: Gunakan $user->date_of_birth dan pastikan ada sebelum memformat --}}
                    <p class="text-gray-900 text-lg">
                        {{ $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('d-m-Y') : '-' }}
                    </p>
                </div>
            </div>

            <div>
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Informasi Alamat & Loyalitas</h2>
                <div class="mb-3">
                    <p class="text-gray-600 font-semibold">Alamat:</p>
                    {{-- PASTIKAN KOLOM address_line1 ADA DI DB MODEL USER ANDA --}}
                    <p class="text-gray-900 text-lg">{{ $user->address_line1}}</p>
                </div>
                <div class="mb-3">
                    <p class="text-gray-600 font-semibold">Kota:</p>
                    {{-- PASTIKAN KOLOM city ADA DI DB MODEL USER ANDA --}}
                    <p class="text-gray-900 text-lg">{{ $user->city ?? '-' }}</p>
                </div>
                <div class="mb-3">
                    <p class="text-gray-600 font-semibold">Provinsi:</p>
                    {{-- PASTIKAN KOLOM province ADA DI DB MODEL USER ANDA --}}
                    <p class="text-gray-900 text-lg">{{ $user->province ?? '-' }}</p>
                </div>
                <div class="mb-3">
                    <p class="text-gray-600 font-semibold">Status Akun:</p>
                    {{-- Diperbarui: Menggunakan $user->status --}}
                    <span class="relative inline-block px-3 py-1 font-semibold leading-tight {{ $user->status == 'active' ? 'text-green-900' : ($user->status == 'inactive' ? 'text-red-900' : 'text-yellow-900') }}">
                        <span aria-hidden="true" class="absolute inset-0 opacity-50 rounded-full {{ $user->status == 'active' ? 'bg-green-200' : ($user->status == 'inactive' ? 'bg-red-200' : 'bg-yellow-200') }}"></span>
                        <span class="relative">{{ ucfirst($user->status) }}</span>
                    </span>
                </div>
                <div class="mb-3">
                    <p class="text-gray-600 font-semibold">Level Loyalitas:</p>
                    <p class="text-gray-900 text-lg">{{ $user->tier->name ?? 'N/A' }}</p>
                </div>
                <div class="mb-3">
                    <p class="text-gray-600 font-semibold">Poin Saat Ini:</p>
                    <p class="text-gray-900 text-lg">{{ number_format($user->current_points ?? 0) }}</p>
                </div>
                <div class="mb-3">
                    <p class="text-gray-600 font-semibold">Total Poin Didapat:</p>
                    <p class="text-gray-900 text-lg">{{ number_format($user->total_points_earned ?? 0) }}</p>
                </div>
                <div class="mb-3">
                    <p class="text-gray-600 font-semibold">Total Poin Ditukar:</p>
                    <p class="text-gray-900 text-lg">{{ number_format($user->total_points_redeemed ?? 0) }}</p>
                </div>
                <div class="mb-3">
                    <p class="text-gray-600 font-semibold">Tanggal Daftar:</p>
                    <p class="text-gray-900 text-lg">{{ $user->created_at->format('d-m-Y H:i') }}</p>
                </div>
            </div>
        </div>

        {{-- Anda bisa menambahkan bagian lain seperti riwayat poin, riwayat redeem, dll. --}}
    </div>
</div>
@endsection