<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointRule extends Model
{
    use HasFactory;

    // Jika Anda hanya ingin satu baris untuk menyimpan semua aturan
    protected $primaryKey = 'id'; // Bisa juga id auto-increment biasa
    public $incrementing = false; // Nonaktifkan auto-increment jika primary key bukan id auto-increment
    protected $keyType = 'integer'; // Tipe primary key

    protected $fillable = [
        'purchase_point_ratio', // Misalnya, 1 poin per berapa rupiah (contoh: 1000 berarti 1 poin per Rp 1000)
        'rating_points', // Poin yang didapat dari rating produk
        'welcome_bonus_points', // Poin bonus saat registrasi
        // Tambahkan atribut untuk aturan perolehan poin lainnya
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'purchase_point_ratio' => 'integer',
        'rating_points' => 'integer',
        'welcome_bonus_points' => 'integer',
    ];
}