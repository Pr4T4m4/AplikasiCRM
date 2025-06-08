<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // Logika untuk menampilkan daftar produk
        return view('member.products.index'); // Pastikan view ini ada
    }
}