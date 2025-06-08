@extends('layouts.app')

@section('title', 'Manajemen Anggota') {{-- Tambahkan judul halaman --}}

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Manajemen Anggota</h1>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Sukses!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
            {{-- Tombol close untuk notifikasi --}}
            </span>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Gagal!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
            {{-- Tombol close untuk notifikasi --}}
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.style.display='none';">
        </div>
    @endif

    {{-- CONTAINER UTAMA UNTUK SEARCH DAN TOMBOL (jika ada) --}}
    {{-- Konsisten dengan layout promo dan reward --}}
    <div class="flex flex-col md:flex-row md:justify-between items-stretch md:items-center mb-6 space-y-4 md:space-y-0 md:space-x-4">
        {{-- FORM PENCARIAN --}}
        {{-- Hapus md:w-auto dari form agar flex-grow bekerja lebih baik pada layar medium ke atas --}}
        <form action="{{ route('admin.members.index') }}" method="GET" class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2 w-full flex-grow">
            {{-- Tambahkan w-full pada input untuk memastikan lebar penuh pada mobile --}}
            <input type="text" name="search" placeholder="Cari anggota..."
                   class="w-full border border-gray-300 p-2 rounded-md focus:ring-blue-500 focus:border-blue-500 flex-grow"
                   value="{{ request('search') }}">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 w-full sm:w-auto">Cari</button>
            @if(request('search')) {{-- Tambahkan tombol Reset jika ada pencarian aktif --}}
                <a href="{{ route('admin.members.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline w-full sm:w-auto text-center">Reset</a>
            @endif
        </form>
        
        {{-- Tombol Tambah Member Dihilangkan (tetap di-komen sesuai permintaan) --}}
        {{-- Jika ingin menambahkan lagi, uncomment baris di bawah dan sesuaikan stylingnya --}}
        {{-- <a href="{{ route('admin.members.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 text-center w-full md:w-auto flex-shrink-0">
            <i class="fas fa-user-plus mr-2"></i> Tambah Anggota Baru
        </a> --}}
    </div>

    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Nama Lengkap
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Email
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Poin Saat Ini
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Tier
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($members as $member)
                <tr class="hover:bg-gray-50"> {{-- Tambahkan hover effect --}}
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        {{-- Tambahkan avatar/gambar profil jika ada, seperti di rewards --}}
                        {{-- Contoh: <div class="flex items-center"> <img class="w-10 h-10 rounded-full object-cover mr-3" src="{{ asset('storage/' . $member->profile_picture) }}" alt="Avatar"> ... </div> --}}
                        <p class="text-gray-900 whitespace-no-wrap">{{ $member->full_name }}</p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap">{{ $member->email }}</p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap">{{ number_format($member->current_points) }}</p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <span class="relative inline-block px-3 py-1 font-semibold leading-tight">
                            <span aria-hidden="true" class="absolute inset-0 {{ $member->status == 'active' ? 'bg-green-200' : ($member->status == 'inactive' ? 'bg-red-200' : 'bg-yellow-200') }} opacity-50 rounded-full"></span>
                            <span class="relative text-{{ $member->status == 'active' ? 'green' : ($member->status == 'inactive' ? 'red' : 'yellow') }}-900">
                                {{ ucfirst($member->status) }}
                            </span>
                        </span>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap">{{ $member->tier->name ?? '-' }}</p> {{-- Pastikan tier ada, tambahkan fallback jika null --}}
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <div class="flex space-x-2 justify-start"> {{-- Konsisten dengan justify-start --}}
                            <a href="{{ route('admin.members.show', $member->id) }}" class="text-blue-600 hover:text-blue-900" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.members.edit_status', $member->id) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit Status">
                                <i class="fas fa-toggle-on"></i>
                            </a>
                            <form action="{{ route('admin.members.destroy', $member->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggota ini? Ini akan menghapus semua data transaksi yang terkait!');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                    <i class="fas fa-trash-alt"></i> {{-- Menggunakan fas fa-trash-alt untuk konsistensi --}}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center text-gray-600"> {{-- Tambahkan text-gray-600 --}}
                        Tidak ada data anggota.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6"> {{-- Konsisten dengan mt-6 untuk paginasi --}}
        {{ $members->links() }}
    </div>
</div>
@endsection
