<?php

namespace App\Observers;

use App\Models\Transaction;
use App\Models\PointTransaction; // Import model PointTransaction
use App\Models\User; // Import model User

class TransactionObserver
{
    public function created(Transaction $transaction)
    {
        $user = $transaction->user;
        if ($user) {
            PointTransaction::create([
                'user_id' => $user->id,
                'points' => $transaction->points_earned,
                'type' => 'earned',
                'description' => 'Poin dari transaksi pembelian: #' . $transaction->invoice_id,
            ]);
        }
    }

    // ... (method updated, deleted, restored, forceDeleted)
}