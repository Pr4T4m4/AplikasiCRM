@extends('layouts.app')

@section('title', 'Detail Hadiah')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Detail Hadiah</h1>
        <a href="{{ route('admin.rewards.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Kembali ke Daftar Hadiah</a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6 flex flex-col md:flex-row gap-6">
        <div class="md:w-1/2 flex-shrink-0">
            @if ($reward->image_path)
                <img src="{{ asset('storage/' . $reward->image_path) }}" alt="{{ $reward->name }}" class="w-full h-auto object-cover rounded-lg shadow-lg">
            @else
                <div class="w-full h-80 bg-gray-200 flex items-center justify-center text-gray-500 rounded-lg shadow-lg">
                    <i class="fas fa-gift text-8xl"></i>
                </div>
            @endif
        </div>
        <div class="md:w-1/2 flex-grow">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">{{ $reward->name }}</h2>
            <div class="mb-4 text-lg">
                <p class="text-gray-700 mb-2"><strong class="text-gray-800">Poin Dibutuhkan:</strong> <span class="font-semibold text-blue-600">{{ number_format($reward->points_required) }}</span></p>
                <p class="text-gray-700 mb-2"><strong class="text-gray-800">Stok Tersedia:</strong> <span class="font-semibold text-green-600">{{ number_format($reward->stock) }}</span></p>
                <p class="text-gray-700 mb-2"><strong class="text-gray-800">Status:</strong>
                    @if ($reward->is_active)
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                    @else
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">Tidak Aktif</span>
                    @endif
                </p>
            </div>

            <div class="mb-6">
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Deskripsi Hadiah:</h3>
                <p class="text-gray-700 leading-relaxed">
                    {{ $reward->description ?: 'Tidak ada deskripsi untuk hadiah ini.' }}
                </p>
            </div>

            <div class="mt-6 flex flex-wrap gap-4">
                <a href="{{ route('admin.rewards.edit', $reward->id) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded focus:outline-none focus:shadow-outline flex items-center justify-center">
                    <i class="fas fa-edit mr-2"></i> Edit Hadiah
                </a>
                <form action="{{ route('admin.rewards.destroy', $reward->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus hadiah ini? Tindakan ini tidak bisa dibatalkan.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded focus:outline-none focus:shadow-outline flex items-center justify-center">
                        <i class="fas fa-trash-alt mr-2"></i> Hapus Hadiah
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
