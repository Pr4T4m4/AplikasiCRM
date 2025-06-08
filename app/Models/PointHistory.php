<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'points', // Jumlah poin yang ditambahkan/dikurangi (bisa positif/negatif)
        'type', // Misalnya: 'purchase', 'redeem', 'manual_adjustment', 'birthday_bonus', 'rating'
        'description', // Detail atau alasan transaksi
        'balance_after', // Saldo poin setelah transaksi
        'reference_id', // ID dari entitas terkait (misal: order_id, admin_id, reward_id)
        'reference_type', // Tipe entitas terkait (misal: 'order', 'admin', 'reward')
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'points' => 'integer',
        'balance_after' => 'integer',
    ];

    // Relasi dengan User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}