<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response; // Tambahkan ini
use Illuminate\Http\RedirectResponse; // Tambahkan ini
use Illuminate\Contracts\View\View; // Tambahkan ini
use Illuminate\Contracts\View\Factory; // Tambahkan ini


class RewardController extends Controller
{
    /**
     * Display a listing of the resource.
     * Menampilkan daftar semua hadiah.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View // Ubah tipe return
     */
    public function index(Request $request): Factory|View // Ubah deklarasi tipe return
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $rewards = Reward::query();

        if ($search) {
            $rewards->where(function($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($status === 'active') {
            $rewards->where('is_active', true);
        } elseif ($status === 'inactive') {
            $rewards->where('is_active', false);
        }

        $rewards->orderBy('created_at', 'desc');
        $rewards = $rewards->paginate(10);

        return view('admin.rewards.index', compact('rewards'));
    }

    /**
     * Show the form for creating a new resource.
     * Menampilkan form untuk membuat hadiah baru.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View // Ubah tipe return
     */
    public function create(): Factory|View // Ubah deklarasi tipe return
    {
        return view('admin.rewards.create');
    }

    /**
     * Store a newly created resource in storage.
     * Menyimpan hadiah baru ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse // Ubah tipe return
     */
    public function store(Request $request): RedirectResponse // Ubah deklarasi tipe return
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'points_required' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'boolean',
        ]);

        $reward = new Reward();
        $reward->name = $validatedData['name'];
        $reward->points_required = $validatedData['points_required'];
        $reward->stock = $validatedData['stock'];
        $reward->description = $validatedData['description'];
        $reward->is_active = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('rewards', 'public');
            $reward->image_path = $imagePath;
        }

        $reward->save();

        return redirect()->route('admin.rewards.index')->with('success', 'Hadiah berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     * Menampilkan detail hadiah tertentu.
     *
     * @param  \App\Models\Reward  $reward
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View // Ubah tipe return
     */
    public function show(Reward $reward): Factory|View // Ubah deklarasi tipe return
    {
        return view('admin.rewards.show', compact('reward'));
    }

    /**
     * Show the form for editing the specified resource.
     * Menampilkan form untuk mengedit hadiah tertentu.
     *
     * @param  \App\Models\Reward  $reward
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View // Ubah tipe return
     */
    public function edit(Reward $reward): Factory|View // Ubah deklarasi tipe return
    {
        return view('admin.rewards.edit', compact('reward'));
    }

    /**
     * Update the specified resource in storage.
     * Memperbarui hadiah tertentu di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reward  $reward
     * @return \Illuminate\Http\RedirectResponse // Ubah tipe return
     */
    public function update(Request $request, Reward $reward): RedirectResponse // Ubah deklarasi tipe return
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'points_required' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($reward->image_path && Storage::disk('public')->exists($reward->image_path)) {
                Storage::disk('public')->delete($reward->image_path);
            }
            $imagePath = $request->file('image')->store('rewards', 'public');
            $reward->image_path = $imagePath;
        }

        $reward->is_active = $request->boolean('is_active');

        unset($validatedData['image']);
        unset($validatedData['is_active']);

        $reward->update($validatedData);

        return redirect()->route('admin.rewards.index')->with('success', 'Hadiah berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     * Menghapus hadiah tertentu dari database.
     *
     * @param  \App\Models\Reward  $reward
     * @return \Illuminate\Http\RedirectResponse // Ubah tipe return
     */
    public function destroy(Reward $reward): RedirectResponse // Ubah deklarasi tipe return
    {
        if ($reward->image_path && Storage::disk('public')->exists($reward->image_path)) {
            Storage::disk('public')->delete($reward->image_path);
        }

        $reward->delete();

        return redirect()->route('admin.rewards.index')->with('success', 'Hadiah berhasil dihapus!');
    }
}
