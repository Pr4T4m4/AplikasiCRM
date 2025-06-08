<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View; // Import untuk return type View
use Illuminate\Http\RedirectResponse; // Import untuk return type RedirectResponse
use Illuminate\Validation\Rule; // Pastikan ini diimport jika digunakan untuk validasi unique pada update

class PromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): View
    {
        // Logika pencarian dan filter bisa ditambahkan di sini jika diperlukan
        $promotions = Promotion::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.promotions.index', compact('promotions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create(): View
    {
        return view('admin.promotions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            // --- PERUBAHAN DI SINI: Validasi 'type' ---
            'type' => 'required|string|in:diskon,gratis_ongkir,cashback,hadiah,birthday,tanggal_kembar,umum', // Daftar tipe promo yang disepakati
            // --- AKHIR PERUBAHAN ---
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'target_audience' => 'required|string|in:all,specific_tiers,new_members,returning_members', // Contoh target audiens
            'terms_and_conditions' => 'nullable|string',
            'promo_code' => 'nullable|string|unique:promotions,promo_code',
            'status' => 'required|string|in:active,inactive,draft',
        ]);

        Promotion::create($request->all());

        return redirect()->route('admin.promotions.index')
                         ->with('success', 'Promosi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Promotion  $promotion
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Promotion $promotion): View
    {
        return view('admin.promotions.show', compact('promotion'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Promotion  $promotion
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Promotion $promotion): View
    {
        return view('admin.promotions.edit', compact('promotion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Promotion  $promotion
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Promotion $promotion): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            // --- PERUBAHAN DI SINI: Validasi 'type' ---
            'type' => 'required|string|in:diskon,gratis_ongkir,cashback,hadiah,birthday,tanggal_kembar,umum', // Daftar tipe promo yang disepakati
            // --- AKHIR PERUBAHAN ---
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'target_audience' => 'required|string|in:all,specific_tiers,new_members,returning_members',
            'terms_and_conditions' => 'nullable|string',
            'promo_code' => ['nullable', 'string', Rule::unique('promotions', 'promo_code')->ignore($promotion->id)], // Menggunakan Rule untuk unique pada update
            'status' => 'required|string|in:active,inactive,draft',
        ]);

        $promotion->update($request->all());

        return redirect()->route('admin.promotions.index')
                         ->with('success', 'Promosi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Promotion  $promotion
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Promotion $promotion): RedirectResponse
    {
        $promotion->delete();

        return redirect()->route('admin.promotions.index')
                         ->with('success', 'Promosi berhasil dihapus.');
    }
}
