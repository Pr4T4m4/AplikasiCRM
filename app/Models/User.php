<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users'; // Pastikan nama tabel Anda adalah 'users'

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'email',
        'password',
        'phone_number',
        'gender',
        'date_of_birth',
        'address_line1',
        'address_line2',
        'city',     // Ditambahkan ke fillable
        'province', // Ditambahkan ke fillable
        'current_points',
        'total_points_earned',
        'total_points_redeemed',
        'status', // 'pending', 'active', 'inactive', 'suspended'
        'tier_id',
        'is_admin', // Digunakan untuk membedakan admin dan member
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        // 'password' => 'hashed', // Baris ini dihapus sebelumnya
        'date_of_birth' => 'date',
        'current_points' => 'integer',
        'total_points_earned' => 'integer',
        'total_points_redeemed' => 'integer',
        'is_admin' => 'boolean',
    ];

    // Relasi ke model Tier
    public function tier()
    {
        return $this->belongsTo(Tier::class);
    }

    // Relasi ke model Transaction
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Relasi ke model RewardRedemption (Ditambahkan)
    public function rewardRedemptions()
    {
        return $this->hasMany(RewardRedemption::class);
    }

    /**
     * Relasi ke model PointTransaction (DITAMBAHKAN)
     * Mendapatkan semua transaksi poin yang dimiliki oleh user ini.
     */
    public function pointTransactions()
    {
        return $this->hasMany(PointTransaction::class);
    }

    /**
     * Scope a query to only include non-admin users (members).
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMembers($query)
    {
        return $query->where('is_admin', false);
    }

    /**
     * Scope a query to only include admin users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAdmins($query)
    {
        return $query->where('is_admin', true);
    }


    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // Mengatur nilai default saat User baru dibuat
        static::creating(function ($user) {
            if (!isset($user->status)) {
                $user->status = 'pending'; // Default status: pending (belum ada transaksi)
            }
            if (!isset($user->tier_id)) {
                // Temukan tier 'Bronze' atau tier dengan min_points 0 sebagai default
                $bronzeTier = Tier::where('min_points', 0)->first();
                $user->tier_id = $bronzeTier ? $bronzeTier->id : null; // Atur tier_id
            }
            if (!isset($user->current_points)) {
                $user->current_points = 0;
            }
            if (!isset($user->total_points_earned)) {
                $user->total_points_earned = 0;
            }
            if (!isset($user->total_points_redeemed)) {
                $user->total_points_redeemed = 0;
            }
            if (!isset($user->is_admin)) {
                $user->is_admin = false; // Default: bukan admin
            }
        });
    }
}
