<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'min_points',
        'max_points', // Ditambahkan: Agar kolom max_points bisa diisi secara massal
        'description', // Ditambahkan: Agar kolom description bisa diisi secara massal
    ];

    // Accessor untuk menghitung poin ke tier berikutnya
    public function getPointsToNextTierAttribute()
    {
        // Cari tier berikutnya yang memiliki min_points lebih besar dari min_points tier ini
        // dan urutkan secara ascending untuk mendapatkan tier terdekat.
        $nextTier = Tier::where('min_points', '>', $this->min_points)
                        ->orderBy('min_points', 'asc')
                        ->first();

        if ($nextTier) {
            // Poin yang dibutuhkan ke tier berikutnya adalah selisih antara
            // min_points tier berikutnya dan min_points tier saat ini.
            // Ini mengasumsikan bahwa tier berubah berdasarkan min_points yang dicapai.
            return $nextTier->min_points - $this->min_points;
        }

        // Jika ini adalah tier tertinggi (tidak ada tier dengan min_points yang lebih besar),
        // maka tidak ada poin ke tier berikutnya.
        return 0;
    }

    // Relasi ke User: Satu Tier bisa memiliki banyak User
    public function users()
    {
        return $this->hasMany(User::class);
    }
}