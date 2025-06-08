<?php

namespace App\Http\Controllers\Admin; // <-- PASTIKAN BARIS INI BENAR

use App\Http\Controllers\Controller;
use App\Models\RewardRedemption; // <-- PASTIKAN MODEL INI DI-IMPORT
use App\Models\User; // <-- PASTIKAN MODEL INI DI-IMPORT
use App\Models\Reward; // <-- PASTIKAN MODEL INI DI-IMPORT
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminRewardRedemptionController extends Controller
{
    /**
     * Tampilkan daftar penukaran hadiah.
     */
    public function index()
    {
        $redemptions = RewardRedemption::with(['user', 'reward'])
                                        ->orderBy('redeemed_at', 'desc')
                                        ->paginate(15);

        return view('admin.redemptions.index', compact('redemptions'));
    }

    /**
     * Tampilkan form untuk mengedit status penukaran hadiah.
     */
    public function edit(RewardRedemption $redemption)
    {
        $redemption->load('user', 'reward');
        return view('admin.redemptions.edit', compact('redemption'));
    }

    /**
     * Update status penukaran hadiah.
     */
    public function update(Request $request, RewardRedemption $redemption)
    {
        $request->validate([
            'status' => 'required|in:pending,processed,completed,cancelled',
            'notes' => 'nullable|string|max:500',
        ]);

        $oldStatus = $redemption->status;

        $redemption->status = $request->status;
        $redemption->admin_notes = $request->notes;
        $redemption->save();

        if ($oldStatus !== 'cancelled' && $redemption->status === 'cancelled') {
            if ($redemption->user) {
                $redemption->user->increment('current_points', $redemption->points_used);
                Log::info("Poin dikembalikan ke user {$redemption->user->id} ({$redemption->points_used} poin) karena penukaran hadiah {$redemption->id} dibatalkan.");
            }
        }

        return redirect()->route('admin.redemptions.index')->with('success', 'Status penukaran hadiah berhasil diperbarui.');
    }

    /**
     * Hapus entri penukaran hadiah.
     */
    public function destroy(RewardRedemption $redemption)
    {
        $redemption->delete();
        return redirect()->route('admin.redemptions.index')->with('success', 'Penukaran hadiah berhasil dihapus.');
    }
}