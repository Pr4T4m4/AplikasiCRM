<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'user_id',
        'member_name', // Tetap 'member_name' untuk menyimpan snapshot nama saat transaksi
        'total_amount',
        'points_earned',
    ];

    // Relasi dengan model User (member)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}