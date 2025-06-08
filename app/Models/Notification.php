<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'message',
        'sent_by', // ID admin yang mengirim
        'target_audience', // Misal: 'all', 'tier', 'specific_users'
        'target_tier_id', // Jika targetnya tier tertentu
        'sent_at',
        // Anda bisa menambahkan kolom lain seperti 'read_at' jika ingin melacak status baca
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'sent_at' => 'datetime',
    ];

    // Relasi dengan Admin yang mengirim (opsional)
    public function sender()
    {
        return $this->belongsTo(Admin::class, 'sent_by');
    }

    // Relasi dengan Tier (opsional)
    public function targetTier()
    {
        return $this->belongsTo(Tier::class, 'target_tier_id');
    }
}