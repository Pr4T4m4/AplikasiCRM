<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahkan ini
use App\Models\User;
use App\Models\Transaction;
use App\Models\RewardRedemption; // Tambahkan ini
use Illuminate\Contracts\View\View; // Tambahkan untuk deklarasi tipe return
use Illuminate\Contracts\View\Factory; // Tambahkan untuk deklarasi tipe return


class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     * Menampilkan dashboard admin.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(): Factory|View // Deklarasi tipe return
    {
        // Menggunakan Auth::user() untuk mendapatkan user yang sedang login.
        // Asumsi: Admin juga adalah instance dari model User.
        // Jika Anda memiliki guard 'admin' terpisah dan ingin menggunakannya,
        // pastikan guard tersebut terkonfigurasi dengan benar di config/auth.php
        $admin = Auth::user(); // Lebih umum digunakan jika admin adalah user biasa dengan flag is_admin

        // 1. Total Anggota Aktif
        // Memastikan hanya user non-admin yang berstatus 'active'
        $totalActiveMembers = User::where('is_admin', false)
                                  ->where('status', 'active')
                                  ->count();

        // 2. Total Poin Dikeluarkan (Total Poin yang Diperoleh dari Semua Transaksi)
        $totalPointsSpent = Transaction::sum('points_earned');

        // 3. Total Hadiah Ditukarkan
        try {
            // Memastikan model RewardRedemption ada dan terhubung ke tabel yang benar
            $totalRewardsRedeemed = RewardRedemption::count();
        } catch (\Exception $e) {
            $totalRewardsRedeemed = 0;
            // Anda bisa mengaktifkan Log::error jika ingin mencatat error ini
            // \Log::error("Error counting rewards: " . $e->getMessage());
        }

        // 4. Data untuk Diagram Jumlah Gender Pembeli
        // Dapatkan user_id unik yang memiliki transaksi
        $usersWithTransactionsIds = Transaction::distinct('user_id')->pluck('user_id');

        // Filter user aktif (bukan admin) yang ada di daftar transaksi unik
        $activeTransactionalUsers = User::whereIn('id', $usersWithTransactionsIds)
                                         ->where('is_admin', false) // Filter non-admin
                                         ->where('status', 'active') // Filter user aktif
                                         ->get();

        // Hitung jumlah gender dari user yang difilter
        $genderCounts = [
            'Laki-laki' => $activeTransactionalUsers->where('gender', 'Laki-laki')->count(),
            'Perempuan' => $activeTransactionalUsers->where('gender', 'Perempuan')->count(),
            // 'Lain-lain' untuk gender selain Laki-laki/Perempuan, jika ada
            'Lain-lain' => $activeTransactionalUsers->whereNotIn('gender', ['Laki-laki', 'Perempuan'])->count(),
        ];

        // Memastikan data gender selalu ada, bahkan jika 0 (sudah ada di kode Anda, ini bagus)
        if (!isset($genderCounts['Laki-laki'])) $genderCounts['Laki-laki'] = 0;
        if (!isset($genderCounts['Perempuan'])) $genderCounts['Perempuan'] = 0;
        if (!isset($genderCounts['Lain-lain'])) $genderCounts['Lain-lain'] = 0;


        return view('admin.dashboard', compact(
            'admin',
            'totalActiveMembers',
            'totalPointsSpent',
            'totalRewardsRedeemed',
            'genderCounts' // Kirim data gender ke view
        ));
    }
}
