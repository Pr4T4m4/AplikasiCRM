@extends('layouts.app')

@section('title', 'Kelola Hadiah') {{-- Judul halaman tetap 'Kelola Hadiah' --}}

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Kelola Hadiah</h1>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Sukses!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
            {{-- Tombol close untuk notifikasi --}}
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.style.display='none';">
            </span>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Gagal!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
            {{-- Tombol close untuk notifikasi --}}
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.style.display='none';">
            </span>
        </div>
    @endif

    {{-- FLEX CONTAINER UTAMA UNTUK SEARCH/FILTER DAN TOMBOL TAMBAH --}}
    <div class="flex flex-col md:flex-row md:justify-between items-stretch md:items-center mb-6 space-y-4 md:space-y-0 md:space-x-4">
        {{-- FORM PENCARIAN & FILTER --}}
        <form action="{{ route('admin.rewards.index') }}" method="GET" class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2 w-full md:w-auto flex-grow">
            <input type="text" name="search" placeholder="Cari hadiah..."
                   class="border border-gray-300 p-2 rounded-md focus:ring-blue-500 focus:border-blue-500 flex-grow"
                   value="{{ request('search') }}">
            <select name="status" class="border border-gray-300 p-2 rounded-md focus:ring-blue-500 focus:border-blue-500 w-full sm:w-auto">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
            </select>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 w-full sm:w-auto">Cari & Filter</button>
            @if(request('search') || request('status'))
                <a href="{{ route('admin.rewards.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline w-full sm:w-auto text-center">Reset</a>
            @endif
        </form>
        
        {{-- TOMBOL TAMBAH HADIAH BARU --}}
        <a href="{{ route('admin.rewards.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 text-center w-full md:w-auto flex-shrink-0">
            <i class="fas fa-plus mr-2"></i> Tambah Hadiah Baru
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse ($rewards as $reward)
            <div class="bg-white shadow-md rounded-lg overflow-hidden flex flex-col">
                {{-- Bagian Gambar Hadiah --}}
                <div class="w-full h-48 bg-gray-200 flex items-center justify-center overflow-hidden">
                    {{-- PENTING: Pastikan $reward->image_path berisi PATH YANG BENAR dari folder 'storage' Anda --}}
                    <img src="{{ asset('storage/' . $reward->image_path) }}"
                         alt="{{ $reward->name }}"
                         class="w-full h-full object-cover"
                         onerror="this.onerror=null; this.src='https://placehold.co/400x300/E0E0E0/6C6C6C?text=No+Image';">
                </div>
                
                {{-- Bagian Detail Hadiah --}}
                <div class="p-4 flex-grow flex flex-col justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800 mb-2 truncate">{{ $reward->name }}</h2>
                        <p class="text-gray-600 text-sm mb-1">Poin: <span class="font-bold">{{ number_format($reward->points_required) }}</span></p>
                        <p class="text-gray-600 text-sm mb-2">Stok: <span class="font-bold">{{ number_format($reward->stock) }}</span></p>
                        <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full {{ $reward->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $reward->is_active ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </div>
                    
                    {{-- Bagian Aksi --}}
                    <div class="mt-4 flex space-x-2">
                        <a href="{{ route('admin.rewards.show', $reward->id) }}" class="text-blue-600 hover:text-blue-900" title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.rewards.edit', $reward->id) }}" class="text-indigo-600 hover:text-indigo-900" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.rewards.destroy', $reward->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus hadiah ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-8 text-gray-600">
                Tidak ada hadiah yang tersedia.
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $rewards->links() }}
    </div>
</div>
@endsection
