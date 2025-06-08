<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointTransaction extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'point_transactions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'points',
        'type', // 'earned' or 'spent'
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // 'created_at' dan 'updated_at' secara otomatis di-cast oleh timestamps()
        // Anda bisa menambahkan casting untuk kolom lain jika diperlukan, misalnya:
        // 'transaction_date' => 'datetime',
    ];

    /**
     * Get the user that owns the point transaction.
     * Mendapatkan user yang memiliki transaksi poin ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
