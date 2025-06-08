@extends('layouts.member_app') {{-- Mengacu ke layout khusus member --}}

@section('title', 'Katalog Hadiah')

@section('content')
<div class="container mx-auto px-4 py-8"> {{-- Konsisten dengan padding dashboard --}}
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Katalog Hadiah</h1>
    <p class="text-gray-600 mb-8">Daftar hadiah yang bisa Anda tukarkan dengan poin Anda.</p>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Sukses!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Gagal!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white p-6 rounded-lg shadow-md mb-8 flex items-center justify-between flex-wrap gap-4"> {{-- Card untuk Poin Anda Saat Ini dan Filter --}}
        <p class="text-lg font-semibold text-gray-700">Poin Anda Saat Ini: <span class="text-indigo-600">{{ number_format(Auth::user()->current_points ?? 0) }}</span></p>
        
        {{-- Filter Section (Responsive) --}}
        <div class="w-full md:w-auto flex flex-col md:flex-row items-stretch md:items-center space-y-2 md:space-y-0 md:space-x-4">
            {{-- Desktop Filter (Always visible on md and up) --}}
            <form action="{{ route('member.rewards.index') }}" method="GET" class="hidden md:flex items-center space-x-2 w-full">
                <input type="text" name="search" placeholder="Cari hadiah..." class="border border-gray-300 p-2 rounded-md focus:ring-blue-500 focus:border-blue-500 flex-grow" value="{{ request('search') }}">
                <select name="category" class="border border-gray-300 p-2 rounded-md focus:ring-blue-500 focus:border-blue-500 w-full md:w-auto">
                    <option value="">Semua Kategori</option>
                    <option value="elektronik" {{ request('category') == 'elektronik' ? 'selected' : '' }}>Elektronik</option>
                    <option value="voucher" {{ request('category') == 'voucher' ? 'selected' : '' }}>Voucher</option>
                    {{-- Add more categories as needed --}}
                </select>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200">
                    Filter
                </button>
            </form>

            {{-- Mobile Filter Button (Visible on small screens) --}}
            <div class="md:hidden w-full">
                <button id="openFilterModal" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition duration-200 w-full flex items-center justify-center">
                    <i class="fas fa-filter mr-2"></i> Cari & Filter
                </button>
            </div>
        </div>
        {{-- End Filter Section --}}
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse ($rewards as $reward)
            <div class="bg-white shadow-md rounded-lg overflow-hidden flex flex-col">
                {{-- Bagian Gambar Hadiah --}}
                <div class="w-full h-48 bg-gray-200 flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('storage/' . $reward->image_path) }}"
                         alt="{{ $reward->name }}"
                         class="w-full h-full object-cover"
                         onerror="this.onerror=null; this.src='https://placehold.co/400x300/E0E0E0/6C6C6C?text=No+Image';">
                </div>
                
                {{-- Bagian Detail Hadiah --}}
                <div class="p-4 flex-grow flex flex-col justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $reward->name }}</h2>
                        <p class="text-gray-600 text-sm mb-1">Poin Dibutuhkan: <span class="font-bold text-indigo-700">{{ number_format($reward->points_required) }}</span></p>
                        <p class="text-gray-600 text-sm mb-2">Stok Tersedia: <span class="font-bold {{ $reward->stock > 0 ? 'text-green-600' : 'text-red-600' }}">{{ number_format($reward->stock) }}</span></p>
                    </div>
                    
                    {{-- Tombol Redeem --}}
                    <div class="mt-4">
                        <form action="{{ route('member.rewards.redeem', $reward->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full py-2 px-4 rounded-md text-white font-semibold transition duration-200
                                       {{ (Auth::user()->current_points ?? 0) >= $reward->points_required && $reward->stock > 0 ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-400 cursor-not-allowed' }}"
                                {{ (Auth::user()->current_points ?? 0) < $reward->points_required || $reward->stock <= 0 ? 'disabled' : '' }}>
                                Tukarkan Hadiah
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-8 text-gray-600">
                Tidak ada hadiah yang tersedia saat ini.
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $rewards->links() }}
    </div>
</div>

{{-- Mobile Filter Modal (Hidden by default) --}}
<div id="filterModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-xl w-11/12 md:w-1/3">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-800">Filter Hadiah</h3>
            <button id="closeFilterModal" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>
        {{-- Form untuk filter mobile --}}
        <form action="{{ route('member.rewards.index') }}" method="GET" class="space-y-4">
            <div>
                <label for="mobile-search" class="block text-sm font-medium text-gray-700">Cari Hadiah</label>
                <input type="text" id="mobile-search" name="search" placeholder="Cari hadiah..." class="form-input rounded-md shadow-sm mt-1 block w-full" value="{{ request('search') }}">
            </div>
            <div>
                <label for="mobile-category" class="block text-sm font-medium text-gray-700">Kategori</label>
                <select id="mobile-category" name="category" class="form-select rounded-md shadow-sm mt-1 block w-full">
                    <option value="">Semua Kategori</option>
                    <option value="elektronik" {{ request('category') == 'elektronik' ? 'selected' : '' }}>Elektronik</option>
                    <option value="voucher" {{ request('category') == 'voucher' ? 'selected' : '' }}>Voucher</option>
                    {{-- Tambahkan opsi kategori lain sesuai kebutuhan --}}
                </select>
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition duration-200 w-full">
                Terapkan Filter
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const openModalBtn = document.getElementById('openFilterModal');
        const closeModalBtn = document.getElementById('closeFilterModal');
        const filterModal = document.getElementById('filterModal');

        if (openModalBtn) {
            openModalBtn.addEventListener('click', function() {
                filterModal.classList.remove('hidden');
                filterModal.classList.add('flex'); // Menggunakan flex untuk centering
            });
        }

        if (closeModalBtn) {
            closeModalBtn.addEventListener('click', function() {
                filterModal.classList.add('hidden');
                filterModal.classList.remove('flex');
            });
        }

        // Opsional: Tutup modal ketika mengklik di luar konten modal
        if (filterModal) {
            filterModal.addEventListener('click', function(event) {
                if (event.target === filterModal) {
                    filterModal.classList.add('hidden');
                    filterModal.classList.remove('flex');
                }
            });
        }
    });
</script>
@endpush

@endsection
