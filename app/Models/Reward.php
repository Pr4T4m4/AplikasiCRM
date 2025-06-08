<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'points_required',
        'stock',
        'image_path',
        'is_active',
    ];

    protected $casts = [
        'points_required' => 'integer',
        'stock' => 'integer',
        'is_active' => 'boolean',
    ];

    // Anda bisa menambahkan relasi jika ada
    // Misalnya, jika ada tabel untuk riwayat penukaran hadiah
    // public function redemptions()
    // {
    //     return $this->hasMany(RewardRedemption::class);
    // }
}