<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RewardRedemption extends Model
{
    use HasFactory;

    // Nama tabel yang terkait dengan model
    protected $table = 'reward_redemptions';

    // Kolom yang dapat diisi secara massal (mass assignable)
    protected $fillable = [
        'user_id',
        'reward_id',
        'points_redeemed', // PASTIKAN INI ADA DI SINI
        'status',
        'redeemed_at',
    ];

    // Kolom yang harus di-cast ke tipe data tertentu
    protected $casts = [
        'redeemed_at' => 'datetime',
    ];

    /**
     * Get the user that owns the reward redemption.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the reward that was redeemed.
     */
    public function reward()
    {
        return $this->belongsTo(Reward::class);
    }
}
