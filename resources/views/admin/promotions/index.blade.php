@extends('layouts.app')

@section('title', 'Kelola Promosi')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Kelola Promosi</h1>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Sukses!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
            {{-- Tombol close untuk notifikasi success --}}
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.style.display='none';">
                <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l3.029-2.651-3.029-2.651a1.2 1.2 0 0 1 1.697-1.697l2.651 3.029 2.651-3.029a1.2 1.2 0 1 1 1.697 1.697l-3.029 2.651 3.029 2.651a1.2 1.2 0 0 1 0 1.697z"/></svg>
            </span>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Gagal!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
            {{-- Tombol close untuk notifikasi error --}}
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.style.display='none';">
                <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l3.029-2.651-3.029-2.651a1.2 1.2 0 0 1 1.697-1.697l2.651 3.029 2.651-3.029a1.2 1.2 0 1 1 1.697 1.697l-3.029 2.651 3.029 2.651a1.2 1.2 0 0 1 0 1.697z"/></svg>
            </span>
        </div>
    @endif

    {{-- FLEX CONTAINER UTAMA UNTUK SEARCH/FILTER DAN TOMBOL TAMBAH --}}
    <div class="flex flex-col md:flex-row md:justify-between items-stretch md:items-center mb-6 space-y-4 md:space-y-0 md:space-x-4">
        {{-- FORM PENCARIAN & FILTER --}}
        <form action="{{ route('admin.promotions.index') }}" method="GET" class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2 w-full md:w-auto flex-grow">
            <input type="text" name="search" placeholder="Cari promosi..."
                   class="border border-gray-300 p-2 rounded-md focus:ring-blue-500 focus:border-blue-500 flex-grow"
                   value="{{ request('search') }}">
            <select name="status" class="border border-gray-300 p-2 rounded-md focus:ring-blue-500 focus:border-blue-500 w-full sm:w-auto">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draf</option>
            </select>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 w-full sm:w-auto">Cari & Filter</button>
            @if(request('search') || request('status'))
                <a href="{{ route('admin.promotions.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline w-full sm:w-auto text-center">Reset</a>
            @endif
        </form>
        
        {{-- TOMBOL TAMBAH PROMOSI BARU --}}
        <a href="{{ route('admin.promotions.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 text-center w-full md:w-auto flex-shrink-0">
            <i class="fas fa-plus mr-2"></i> Tambah Promosi Baru
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse ($promotions as $promotion)
            <div class="bg-white shadow-md rounded-lg overflow-hidden flex flex-col">
                {{-- Bagian Ikon/Placeholder Dinamis --}}
                <div class="w-full h-32 flex items-center justify-center text-white text-5xl
                    @switch($promotion->type)
                        @case('diskon') bg-blue-500 @break
                        @case('gratis_ongkir') bg-green-500 @break
                        @case('cashback') bg-purple-500 @break
                        @case('hadiah') bg-yellow-500 @break
                        @case('birthday') bg-pink-500 @break
                        @case('tanggal_kembar') bg-teal-500 @break
                        @default bg-gray-500
                    @endswitch
                ">
                    @switch($promotion->type)
                        @case('diskon')
                            <i class="fas fa-tags" title="Promo Diskon"></i>
                            @break
                        @case('gratis_ongkir')
                            <i class="fas fa-truck" title="Gratis Ongkir"></i>
                            @break
                        @case('cashback')
                            <i class="fas fa-money-bill-wave" title="Cashback"></i>
                            @break
                        @case('hadiah')
                            <i class="fas fa-gift" title="Hadiah"></i>
                            @break
                        @case('birthday')
                            <i class="fas fa-birthday-cake" title="Promo Ulang Tahun"></i>
                            @break
                        @case('tanggal_kembar')
                            <i class="fas fa-calendar-alt" title="Promo Tanggal Kembar"></i>
                            @break
                        @default
                            <i class="fas fa-bullhorn" title="Promo Umum"></i>
                    @endswitch
                </div>
                
                {{-- Bagian Detail Promosi --}}
                <div class="p-4 flex-grow flex flex-col justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800 mb-2 truncate">{{ $promotion->name }}</h2>
                        <p class="text-gray-600 text-sm mb-1">Tipe: <span class="capitalize">{{ str_replace('_', ' ', $promotion->type) }}</span></p>
                        <p class="text-gray-600 text-sm mb-1">Periode: {{ $promotion->start_date ? $promotion->start_date->format('d M Y') : 'N/A' }} - {{ $promotion->end_date ? $promotion->end_date->format('d M Y') : 'N/A' }}</p>
                        <p class="text-gray-600 text-sm mb-2">Target: <span class="capitalize">{{ str_replace('_', ' ', $promotion->target_audience) }}</span></p>
                        <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full
                            @if($promotion->status == 'active') bg-green-100 text-green-800
                            @elseif($promotion->status == 'inactive') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($promotion->status) }}
                        </span>
                    </div>
                    
                    {{-- Bagian Aksi --}}
                    <div class="mt-4 flex space-x-2">
                        <a href="{{ route('admin.promotions.show', $promotion->id) }}" class="text-blue-600 hover:text-blue-900" title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.promotions.edit', $promotion->id) }}" class="text-indigo-600 hover:text-indigo-900" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.promotions.destroy', $promotion->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus promosi ini?');">
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
                Tidak ada promosi yang tersedia.
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $promotions->links() }}
    </div>
</div>
@endsection
