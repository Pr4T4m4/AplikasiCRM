<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
// use App\Jobs\UpdateAllUserTiersJob; // <-- Baris ini dihapus/dikomentari

class TierController extends Controller
{
    /**
     * Display a listing of the tiers.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $tiers = Tier::orderBy('min_points', 'asc')->paginate(10);
        return view('admin.tiers.index', compact('tiers'));
    }

    /**
     * Show the form for creating a new tier.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.tiers.create');
    }

    /**
     * Store a newly created tier in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tiers,name',
            'min_points' => 'required|integer|min:0',
            'max_points' => 'nullable|integer|gt:min_points',
            'description' => 'nullable|string|max:1000',
        ]);

        // Periksa tumpang tindih rentang poin (overlap)
        $existingTiers = Tier::all();
        foreach ($existingTiers as $tier) {
            if (
                ($request->min_points >= $tier->min_points && $request->min_points <= ($tier->max_points ?? PHP_INT_MAX)) ||
                (($request->max_points ?? PHP_INT_MAX) >= $tier->min_points && ($request->max_points ?? PHP_INT_MAX) <= ($tier->max_points ?? PHP_INT_MAX)) ||
                ($tier->min_points >= $request->min_points && $tier->min_points <= ($request->max_points ?? PHP_INT_MAX))
            ) {
                 return redirect()->back()->with('error', 'Rentang poin tingkatan yang dimasukkan tumpang tindih dengan tingkatan yang sudah ada.')->withInput();
            }
        }
        
        try {
            Tier::create($request->all());

            // Pemanggilan Job dihapus dari sini
            // UpdateAllUserTiersJob::dispatch();

            return redirect()->route('admin.tiers.index')->with('success', 'Tingkatan berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Gagal menambahkan tingkatan: ' . $e->getMessage(), ['request_data' => $request->all()]);
            return redirect()->back()->with('error', 'Gagal menambahkan tingkatan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified tier.
     *
     * @param  \App\Models\Tier  $tier
     * @return \Illuminate\View\View
     */
    public function show(Tier $tier)
    {
        return view('admin.tiers.show', compact('tier'));
    }

    /**
     * Show the form for editing the specified tier.
     *
     * @param  \App\Models\Tier  $tier
     * @return \Illuminate\View\View
     */
    public function edit(Tier $tier)
    {
        return view('admin.tiers.edit', compact('tier'));
    }

    /**
     * Update the specified tier in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tier  $tier
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Tier $tier)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tiers')->ignore($tier->id),
            ],
            'min_points' => 'required|integer|min:0',
            'max_points' => 'nullable|integer|gt:min_points',
            'description' => 'nullable|string|max:1000',
        ]);

        // Periksa tumpang tindih rentang poin (overlap) kecuali dengan tier yang sedang diupdate
        $existingTiers = Tier::where('id', '!=', $tier->id)->get();
        foreach ($existingTiers as $existingTier) {
             if (
                ($request->min_points >= $existingTier->min_points && $request->min_points <= ($existingTier->max_points ?? PHP_INT_MAX)) ||
                (($request->max_points ?? PHP_INT_MAX) >= $existingTier->min_points && ($request->max_points ?? PHP_INT_MAX) <= ($existingTier->max_points ?? PHP_INT_MAX)) ||
                ($existingTier->min_points >= $request->min_points && $existingTier->min_points <= ($request->max_points ?? PHP_INT_MAX))
            ) {
                 return redirect()->back()->with('error', 'Rentang poin tingkatan yang dimasukkan tumpang tindih dengan tingkatan lain.')->withInput();
            }
        }

        try {
            $tier->update($request->all());

            // Pemanggilan Job dihapus dari sini
            // UpdateAllUserTiersJob::dispatch();

            return redirect()->route('admin.tiers.index')->with('success', 'Tingkatan berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui tingkatan: ' . $e->getMessage(), ['request_data' => $request->all(), 'tier_id' => $tier->id]);
            return redirect()->back()->with('error', 'Gagal memperbarui tingkatan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified tier from storage.
     *
     * @param  \App\Models\Tier  $tier
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Tier $tier)
    {
        // Pencegahan: Jangan biarkan menghapus tier jika ada user yang terkait dengan tier ini
        if ($tier->users()->exists()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus tingkatan karena ada anggota yang terkait. Harap ubah tingkatan anggota terlebih dahulu.');
        }

        // Jika tier ini adalah tier dasar (min_points = 0), mungkin Anda tidak ingin mengizinkannya dihapus
        if ($tier->min_points === 0) {
            // Anda bisa menambahkan logika untuk memastikan selalu ada satu tier dasar
            // atau mencegah penghapusan jika itu adalah satu-satunya tier.
        }

        try {
            $tier->delete();

            // Pemanggilan Job dihapus dari sini
            // UpdateAllUserTiersJob::dispatch();

            return redirect()->route('admin.tiers.index')->with('success', 'Tingkatan berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus tingkatan: ' . $e->getMessage(), ['tier_id' => $tier->id]);
            return redirect()->back()->with('error', 'Gagal menghapus tingkatan: ' . $e->getMessage());
        }
    }
}