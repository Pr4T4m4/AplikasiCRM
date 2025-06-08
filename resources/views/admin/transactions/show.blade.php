@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Detail Transaksi: {{ $transaction->invoice_id }}</h1>

    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-gray-600"><strong>ID Invoice:</strong> {{ $transaction->invoice_id }}</p>
                <p class="text-gray-600"><strong>Nama Member:</strong> {{ $transaction->member_name }}</p>
                <p class="text-gray-600"><strong>Email Member:</strong> {{ $transaction->user->email ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-600"><strong>Jumlah Total:</strong> Rp {{ number_format($transaction->total_amount, 2, ',', '.') }}</p>
                <p class="text-gray-600"><strong>Poin Didapat:</strong> {{ number_format($transaction->points_earned) }}</p>
                <p class="text-gray-600"><strong>Tanggal Transaksi:</strong> {{ $transaction->created_at->format('d M Y H:i') }}</p>
            </div>
        </div>
    </div>

    <div class="flex space-x-2">
        <a href="{{ route('admin.transactions.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
            Kembali
        </a>
    </div>
</div>
@endsection