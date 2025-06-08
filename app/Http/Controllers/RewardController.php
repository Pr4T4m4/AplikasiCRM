<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use App\Models\RewardRedemption;
use App\Models\User; // Pastikan model User di-import
use App\Models\PointTransaction; // Pastikan model PointTransaction di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log; // Import Log untuk mencatat error

class RewardController extends Controller
{
    /**
     * Display a listing of the rewards for members.
     * Menampilkan daftar hadiah untuk anggota.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request): Factory|View
    {
        $search = $request->input('search');
        $category = $request->input('category'); // Ambil parameter kategori

        $rewards = Reward::query()
                         ->where('is_active', true) // Hanya tampilkan hadiah yang aktif
                         ->where('stock', '>', 0); // Hanya tampilkan hadiah yang stoknya lebih dari 0

        // Terapkan filter pencarian jika ada
        if ($search) {
            $rewards->where(function($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Terapkan filter kategori jika ada
        if ($category) {
            $rewards->where('category', $category); // Asumsi ada kolom 'category' di tabel rewards
        }

        $rewards = $rewards->orderBy('name', 'asc')
                           ->paginate(10); // Paginate hasilnya

        return view('rewards.index', compact('rewards'));
    }

    /**
     * Display the specified reward.
     * Menampilkan detail hadiah tertentu.
     *
     * @param  \App\Models\Reward  $reward
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(Reward $reward): Factory|View
    {
        return view('rewards.show', compact('reward'));
    }

    /**
     * Handle the reward redemption process.
     * Menangani proses penukaran hadiah.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reward  $reward
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redeem(Request $request, Reward $reward): RedirectResponse
    {
        $user = Auth::user();

        // Validasi awal
        if (!$reward->is_active) {
            return back()->with('error', 'Hadiah ini tidak aktif dan tidak bisa ditukarkan.');
        }

        if ($reward->stock <= 0) {
            return back()->with('error', 'Maaf, stok hadiah ini sudah habis.');
        }

        // Menggunakan null coalescing operator untuk memastikan current_points ada
        if (($user->current_points ?? 0) < $reward->points_required) {
            return back()->with('error', 'Poin Anda tidak cukup untuk menukarkan hadiah ini.');
        }

        // Memulai transaksi database untuk memastikan atomisitas operasi
        DB::beginTransaction();
        try {
            // 1. Mengurangi poin pengguna
            $user->current_points -= $reward->points_required;
            $user->save();

            // 2. Mengurangi stok hadiah
            $reward->stock -= 1;
            $reward->save();

            // 3. Membuat catatan penukaran hadiah
            RewardRedemption::create([
                'user_id' => $user->id,
                'reward_id' => $reward->id,
                'points_redeemed' => $reward->points_required,
                'status' => 'pending', // Atau status awal lain seperti 'menunggu_verifikasi'
                'redeemed_at' => now(), // Tambahkan timestamp penukaran
            ]);

            // 4. Membuat catatan transaksi poin (POIN DIGUNAKAN)
            PointTransaction::create([
                'user_id' => $user->id,
                'points' => $reward->points_required, // Jumlah poin yang digunakan
                'type' => 'spent', // Tipe transaksi: 'spent' (digunakan)
                'description' => 'Poin digunakan untuk menukar hadiah: ' . $reward->name, // Deskripsi
            ]);

            // Commit transaksi jika semua operasi berhasil
            DB::commit();

            return redirect()->route('member.history.index')->with('success', 'Hadiah berhasil ditukarkan! Poin Anda telah berkurang. Silakan cek riwayat Anda.');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();
            Log::error("Reward redemption failed for User ID: {$user->id}, Reward ID: {$reward->id}. Error: " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menukarkan hadiah. Silakan coba lagi. ' . (config('app.debug') ? $e->getMessage() : '')); // Tampilkan error detail jika debug mode aktif
        }
    }
}
