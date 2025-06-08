@extends('layouts.app')

@section('title', 'Detail Promosi: ' . $promotion->name)

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Detail Promosi: {{ $promotion->name }}</h1>

    <div class="bg-white shadow-lg rounded-lg p-8 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="mb-4">
                <p class="text-gray-600"><strong>Nama Promosi:</strong></p>
                <p class="text-gray-900 text-lg">{{ $promotion->name }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600"><strong>Tipe Promosi:</strong></p>
                <p class="text-gray-900 text-lg capitalize">{{ str_replace('_', ' ', $promotion->type) }}</p>
            </div>
            <div class="mb-4 md:col-span-2">
                <p class="text-gray-600"><strong>Deskripsi:</strong></p>
                <p class="text-gray-900 text-lg">{{ $promotion->description ?? '-' }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600"><strong>Tanggal Mulai:</strong></p>
                <p class="text-gray-900 text-lg">{{ $promotion->start_date ? $promotion->start_date->format('d M Y') : 'N/A' }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600"><strong>Tanggal Berakhir:</strong></p>
                <p class="text-gray-900 text-lg">{{ $promotion->end_date ? $promotion->end_date->format('d M Y') : 'N/A' }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600"><strong>Target Audiens:</strong></p>
                <p class="text-gray-900 text-lg capitalize">{{ str_replace('_', ' ', $promotion->target_audience) }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600"><strong>Kode Promosi:</strong></p>
                <p class="text-gray-900 text-lg">{{ $promotion->promo_code ?? '-' }}</p>
            </div>
            <div class="mb-4 md:col-span-2">
                <p class="text-gray-600"><strong>Syarat & Ketentuan:</strong></p>
                <p class="text-gray-900 text-lg">{{ $promotion->terms_and_conditions ?? '-' }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600"><strong>Status:</strong></p>
                <p class="text-gray-900 text-lg">
                    <span class="text-sm font-semibold px-2.5 py-0.5 rounded-full
                        @if($promotion->status == 'active') bg-green-100 text-green-800
                        @elseif($promotion->status == 'inactive') bg-red-100 text-red-800
                        @else bg-yellow-100 text-yellow-800 @endif">
                        {{ ucfirst($promotion->status) }}
                    </span>
                </p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600"><strong>Dibuat Pada:</strong></p>
                <p class="text-gray-900 text-lg">{{ $promotion->created_at->format('d M Y H:i:s') }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600"><strong>Terakhir Diperbarui:</strong></p>
                <p class="text-gray-900 text-lg">{{ $promotion->updated_at->format('d M Y H:i:s') }}</p>
            </div>
        </div>
    </div>

    <div class="flex space-x-4">
        <a href="{{ route('admin.promotions.edit', $promotion->id) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-300">
            Edit Promosi
        </a>
        <a href="{{ route('admin.promotions.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-300">
            Kembali ke Daftar
        </a>
        <form action="{{ route('admin.promotions.destroy', $promotion->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus promosi ini?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-300">
                Hapus Promosi
            </button>
        </form>
    </div>
</div>
@endsection
