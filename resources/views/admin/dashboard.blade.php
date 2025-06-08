@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Admin Dashboard Overview</h1>

        {{-- Grid untuk Statistik/Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">Total Anggota Aktif</h3>
                    <p class="text-3xl font-bold text-indigo-600 mt-1">{{ number_format($totalActiveMembers) }}</p>
                </div>
                <i class="fas fa-users text-5xl text-indigo-400 opacity-50"></i>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">Total Poin Dikeluarkan</h3>
                    <p class="text-3xl font-bold text-green-600 mt-1">{{ number_format($totalPointsSpent) }}</p>
                </div>
                <i class="fas fa-coins text-5xl text-green-400 opacity-50"></i>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">Total Hadiah Ditukarkan</h3>
                    <p class="text-3xl font-bold text-yellow-600 mt-1">{{ number_format($totalRewardsRedeemed) }}</p>
                </div>
                <i class="fas fa-gift text-5xl text-yellow-400 opacity-50"></i>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Diagram Produk Terlaris</h3>
                <div class="h-64 bg-gray-200 flex items-center justify-center text-gray-500">
                    Grafik Produk Terlaris Disini
                </div>
            </div>

            {{-- Diagram Jumlah Gender Pembeli --}}
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Diagram Jumlah Gender Pembeli</h3>
                <div class="h-64 flex flex-col items-center justify-center">
                    {{-- Elemen canvas untuk Chart.js --}}
                    <canvas id="genderChart"></canvas>
                </div>
                <p class="text-sm text-gray-500 mt-2">
                    (Berdasarkan user aktif yang melakukan transaksi)
                </p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Diagram Hadiah Paling Banyak Ditukarkan</h3>
                <div class="h-64 bg-gray-200 flex items-center justify-center text-gray-500">
                    Grafik Hadiah Ditukarkan Disini
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Laporan Rating Produk (Terendah)</h3>
                <div class="h-64 bg-gray-200 flex items-center justify-center text-gray-500">
                    Daftar Produk Rating Terendah Disini
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT UNTUK CHART.JS --}}
    {{-- PENTING: Pastikan ini dimuat SETELAH elemen <canvas> ada di DOM --}}
    {{-- Anda bisa memindahkannya ke dalam @push('scripts') di layouts/app.blade.php jika Anda punya section scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Pastikan DOM sudah siap sebelum menjalankan script Chart.js
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('genderChart'); // Dapatkan elemen canvas

            // Hanya inisialisasi chart jika elemen ctx ditemukan
            if (ctx) {
                new Chart(ctx, {
                    type: 'pie', // Tipe grafik pie
                    data: {
                        labels: ['Laki-laki', 'Perempuan', 'Lain-lain'],
                        datasets: [{
                            data: [
                                {{ $genderCounts['Laki-laki'] ?? 0 }}, // Gunakan ?? 0 untuk keamanan
                                {{ $genderCounts['Perempuan'] ?? 0 }},
                                {{ $genderCounts['Lain-lain'] ?? 0 }}
                            ],
                            backgroundColor: [
                                '#4299e1', // Biru (Tailwind blue-500/600)
                                '#ed64a6', // Pink (Tailwind pink-400/500)
                                '#a0aec0'  // Abu-abu (Tailwind gray-500)
                            ],
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false, // Penting agar grafik mengisi parent div
                        plugins: {
                            legend: {
                                position: 'bottom', // Posisi legenda di bawah grafik
                            },
                            title: {
                                display: false, // Judul sudah ada di h3
                                text: 'Jumlah Gender Pembeli'
                            }
                        }
                    }
                });
            } else {
                console.error("Canvas element with ID 'genderChart' not found.");
            }
        });
    </script>
@endsection
