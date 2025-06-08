<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Pastikan model Product ada
use App\Models\ProductRating; // Anda mungkin perlu membuat model ini juga

class ProductRatingController extends Controller
{
    public function index()
    {
        // Menampilkan daftar produk yang bisa di-rate atau rating yang sudah ada
        $products = Product::all();
        return view('product_ratings.index', compact('products'));
    }

    public function create(Product $product)
    {
        // Menampilkan form untuk memberikan rating pada produk tertentu
        return view('product_ratings.create', compact('product'));
    }

    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:500'],
        ]);

        // Logika menyimpan rating
        // Contoh: ProductRating::create([
        //     'user_id' => auth()->id(),
        //     'product_id' => $product->id,
        //     'rating' => $request->rating,
        //     'comment' => $request->comment,
        // ]);

        return redirect()->route('member.product_ratings.index')->with('success', 'Rating Anda berhasil disimpan!');
    }
}