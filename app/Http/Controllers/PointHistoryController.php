<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PointTransaction; // Pastikan model PointTransaction di-import
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;

class PointHistoryController extends Controller
{
    /**
     * Display a listing of the point history for the authenticated user.
     * Menampilkan daftar riwayat poin untuk pengguna yang terautentikasi.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request): Factory|View
    {
        $user = Auth::user();

        // Ambil riwayat poin untuk pengguna yang sedang login
        // Diurutkan berdasarkan tanggal pembuatan terbaru
        // dan dipaginasi untuk tampilan yang lebih baik
        $pointHistory = PointTransaction::where('user_id', $user->id)
                                        ->orderBy('created_at', 'desc')
                                        ->paginate(10); // Sesuaikan jumlah item per halaman jika perlu

        // Kirim data riwayat poin ke view 'history.index'
        return view('history.index', compact('pointHistory'));
    }
}
