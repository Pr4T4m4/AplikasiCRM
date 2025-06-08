<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'image_url',
        'is_active',
    ];

    // Jika ada relasi, contoh dengan rating
    // public function ratings()
    // {
    //     return $this->hasMany(ProductRating::class);
    // }
}