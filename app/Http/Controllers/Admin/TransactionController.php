<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Tier;
// use App\Models\PointTransaction; // <<< PASTIKAN INI DIKOMENTARI ATAU DIHAPUS
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('user');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_id', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('full_name', 'like', '%' . $search . '%');
                  });
            });
        }

        $transactions = $query->latest()->paginate(10);

        return view('admin.transactions.index', compact('transactions'));
    }

    public function create()
    {
        $users = User::where('is_admin', false)
                     ->whereNotNull('full_name')
                     ->orderBy('full_name')
                     ->get();
        return view('admin.transactions.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'invoice_id' => 'required|string|max:255|unique:transactions',
            'total_amount' => 'required|numeric|min:0',
        ]);

        $user = User::find($request->user_id);
        if (!$user) {
            return redirect()->back()->with('error', 'User (Member) tidak ditemukan.')->withInput();
        }

        $points_earned = floor($request->total_amount / 1000);

        DB::beginTransaction();

        try {
            $is_first_transaction = $user->transactions()->doesntExist();

            // Buat transaksi baru.
            // PENTING: PointTransaction::create() AKAN DITANGANI OLEH OBSERVER.
            // JANGAN TAMBAHKAN KODE PointTransaction::create() DI SINI LAGI.
            Transaction::create([
                'invoice_id' => $request->invoice_id,
                'user_id' => $user->id,
                'member_name' => $user->full_name,
                'total_amount' => $request->total_amount,
                'points_earned' => $points_earned,
            ]);

            // Tambahkan poin ke saldo user (current_points dan total_points_earned)
            $user->increment('current_points', $points_earned);
            $user->increment('total_points_earned', $points_earned);

            // <<< BARIS PointTransaction::create() DIHAPUS DARI SINI
            // PointTransaction::create([
            //     'user_id' => $user->id,
            //     'points' => $points_earned,
            //     'type' => 'earned',
            //     'description' => 'Poin dari transaksi pembelian: ' . $request->invoice_id,
            // ]);
            // >>> AKHIR PENGHAPUSAN

            if ($is_first_transaction && $user->status === 'pending') {
                $user->status = 'active';
            }

            $this->updateUserTier($user);

            $user->save();

            DB::commit();

            return redirect()->route('admin.transactions.index')->with('success', 'Transaksi dan poin berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menambahkan transaksi atau poin: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'user_id' => $request->user_id,
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
            ]);
            return redirect()->back()->with('error', 'Gagal menambahkan transaksi atau poin: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Transaction $transaction)
    {
        return view('admin.transactions.show', compact('transaction'));
    }

    public function destroy(Transaction $transaction)
    {
        DB::beginTransaction();

        try {
            $user = $transaction->user;

            if ($user) {
                $user->current_points = max(0, $user->current_points - $transaction->points_earned);
            }
            
            $transaction->delete();

            if ($user) {
                $user->save();
            }
            
            DB::commit();

            return redirect()->route('admin.transactions.index')->with('success', 'Transaksi berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menghapus transaksi: ' . $e->getMessage(), [
                'transaction_id' => $transaction->id,
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
            ]);
            return redirect()->back()->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }

    private function updateUserTier(User $user)
    {
        $totalPointsEarned = $user->total_points_earned;

        $tiers = Tier::orderByDesc('min_points')->get();

        $newTier = null;
        foreach ($tiers as $tier) {
            if ($totalPointsEarned >= $tier->min_points &&
                ($tier->max_points === null || $totalPointsEarned < $tier->max_points)) {
                $newTier = $tier;
                break;
            }
        }

        if ($newTier && ($user->tier_id !== $newTier->id)) {
            $user->tier_id = $newTier->id;
            Log::info("User {$user->id} tier changed to {$newTier->name} with {$totalPointsEarned} total points.");
        } elseif (!$newTier && $user->tier_id !== null) {
            $bronzeTier = Tier::where('min_points', 0)->first();
            $user->tier_id = $bronzeTier ? $bronzeTier->id : null;
            Log::info("User {$user->id} tier reset to Bronze/null with {$totalPointsEarned} total points.");
        }
    }
}
