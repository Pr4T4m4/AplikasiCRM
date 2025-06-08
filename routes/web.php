<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Import Controllers untuk Pengguna Biasa (Member)
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController; // Untuk dashboard member
use App\Http\Controllers\RewardController; // Untuk katalog hadiah member dan redeem
use App\Http\Controllers\ProductRatingController;
use App\Http\Controllers\PointHistoryController;
use App\Http\Controllers\SupportController;

// Import Controllers untuk Admin (menggunakan alias jika nama kelas sama atau untuk kejelasan)
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\TierController;
use App\Http\Controllers\Admin\RewardController as AdminRewardController; // Untuk pengelolaan hadiah oleh admin
use App\Http\Controllers\Admin\AdminRewardRedemptionController;
use App\Http\Controllers\Admin\PromotionController; // <-- Pastikan ini diimport
use App\Http\Controllers\Admin\PointRuleController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ReportController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Halaman Utama: Redirect ke dashboard jika sudah login, atau tampilkan welcome page
Route::get('/', function () {
    if (Auth::check()) {
        // Cek apakah user yang login adalah admin (menggunakan guard 'admin')
        // Ini mengasumsikan Anda memiliki guard 'admin' yang terkonfigurasi di config/auth.php
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        // Jika bukan admin (berarti member), redirect ke dashboard member
        return redirect()->route('member.dashboard');
    }
    return view('welcome'); // Tampilkan halaman welcome jika belum login
})->name('home');

// --- Rute Otentikasi Umum (Tidak Membutuhkan Login) ---
// Rute untuk user biasa (member)
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Rute untuk halaman statis atau umum lainnya
Route::get('terms', function () { return view('terms'); })->name('terms');
Route::get('/support', [SupportController::class, 'index'])->name('support.index');


// --- Rute Anggota/Pelanggan (Membutuhkan Login Member) ---
Route::middleware(['auth'])->group(function () {
    // Rute Logout untuk member
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    // Rute Dashboard Member
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('member.dashboard');

    // Rute untuk Rewards (Katalog Hadiah dan Penukaran)
    Route::prefix('rewards')->name('member.rewards.')->group(function () {
        Route::get('/', [RewardController::class, 'index'])->name('index'); // Menampilkan daftar hadiah
        Route::get('/{reward}', [RewardController::class, 'show'])->name('show'); // Menampilkan detail hadiah
        // Rute untuk redeem hadiah, dilindungi oleh middleware status member 'active'
        Route::post('/{reward}/redeem', [RewardController::class, 'redeem'])
             ->middleware('check.member.status:active') // Pastikan middleware ini terdaftar di Kernel.php
             ->name('redeem');
    });

    // Rute untuk Product Ratings
    Route::prefix('product-ratings')->name('member.product_ratings.')->group(function () {
        Route::get('/', [ProductRatingController::class, 'index'])->name('index');
        Route::get('/{product}/create', [ProductRatingController::class, 'create'])->name('create');
        Route::post('/{product}', [ProductRatingController::class, 'store'])->name('store');
    });

    // Rute untuk Riwayat Poin
    Route::get('/history', [PointHistoryController::class, 'index'])->name('member.history.index');
});


// --- Grup Routes untuk Admin ---
Route::prefix('admin')->name('admin.')->group(function () {

    // Routes untuk Authentikasi Admin (hanya bisa diakses oleh guest admin)
    Route::middleware('admin.guest')->group(function () {
        Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AdminLoginController::class, 'login']);
    });

    // Routes yang dilindungi oleh autentikasi admin (membutuhkan login sebagai admin)
    // Menggunakan guard 'admin' yang terdefinisi di config/auth.php
    Route::middleware('auth:admin')->group(function () {
        // Dashboard Admin
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        // Logout Admin
        Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');

        // --- RUTE ADMIN MODUL UTAMA ---

        // 1. Mengelola Anggota (Management User - khusus admin)
        Route::get('members', [MemberController::class, 'index'])->name('members.index');
        Route::get('members/{user}', [MemberController::class, 'show'])->name('members.show');
        Route::get('members/{user}/edit-status', [MemberController::class, 'edit_status'])->name('members.edit_status');
        Route::put('members/{user}/update-status', [MemberController::class, 'update_status'])->name('members.update_status');
        Route::delete('members/{user}', [MemberController::class, 'destroy'])->name('members.destroy');


        // 2. Transaksi (Pencatatan Transaksi oleh Admin)
        Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
        Route::post('transactions', [TransactionController::class, 'store'])->name('transactions.store');
        Route::get('transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
        Route::delete('transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');

        // 3. Mengelola Tingkatan Loyalitas (Tiers)
        Route::resource('tiers', TierController::class);

        // 4. Mengelola Katalog Hadiah/Produk Penukaran (CRUD Hadiah oleh Admin)
        Route::resource('rewards', AdminRewardController::class);

        // 5. Mengelola Penukaran Hadiah (Review dan Update Status Redeem oleh Admin)
        Route::prefix('redemptions')->name('redemptions.')->group(function () {
            Route::get('/', [AdminRewardRedemptionController::class, 'index'])->name('index');
            Route::get('{redemption}/edit', [AdminRewardRedemptionController::class, 'edit'])->name('edit');
            Route::put('{redemption}', [AdminRewardRedemptionController::class, 'update'])->name('update');
            Route::delete('{redemption}', [AdminRewardRedemptionController::class, 'destroy'])->name('destroy');
        });

        // 6. Membuat dan Mengelola Promosi
        Route::resource('promotions', PromotionController::class); // <-- Ini adalah rute yang baru saja kita kerjakan

        // 7. Mengelola Aturan Perolehan Poin
        Route::get('point-rules', [PointRuleController::class, 'index'])->name('point-rules.index');
        Route::post('point-rules', [PointRuleController::class, 'update'])->name('point-rules.update');

        // 8. Mengirim Notifikasi/Pengumuman Massal
        Route::resource('notifications', NotificationController::class)->only(['index', 'create', 'store', 'show']);

        // 9. Melihat Laporan & Analisis Program
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');

        // Rute sementara untuk update tier semua user (DIHAPUS karena ini adalah rute sementara yang tidak lagi diperlukan)
        // use App\Models\User;
        // use App\Models\Tier;
        // Route::get('/update-all-user-tiers', function () { ... })->name('update-all-user-tiers');
    });
});
