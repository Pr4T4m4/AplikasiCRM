<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // Asumsi model user Anda adalah App\Models\User
use Illuminate\Support\Facades\Hash; // Jika nanti perlu hash password secara manual
use Illuminate\Validation\Rule; // Untuk validasi unik email saat update
use Illuminate\Support\Facades\Log; // Untuk logging error atau debugging

class MemberController extends Controller
{
    /**
     * Menampilkan daftar anggota.
     * Rute: GET /admin/members
     * Nama Rute: admin.members.index
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Menggunakan scope members() yang sudah didefinisikan di App\Models\User.php
        // Ini memastikan hanya non-admin user yang ditampilkan
        $query = User::members();

        // Fitur pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', '%' . $search . '%') // Asumsi ada kolom full_name
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Paginate hasil
        $members = $query->paginate(10); // Menampilkan 10 anggota per halaman

        // Pastikan view ini ada: resources/views/admin/members/index.blade.php
        return view('admin.members.index', compact('members'));
    }

    /**
     * Menampilkan detail satu anggota.
     * Rute: GET /admin/members/{user}
     * Nama Rute: admin.members.show
     * @param  \App\Models\User  $user // Menggunakan $user, bukan $member
     * @return \Illuminate\View\View
     */
    public function show(User $user) // Menggunakan $user sebagai parameter
    {
        // Pastikan view ini ada: resources/views/admin/members/show.blade.php
        return view('admin.members.show', compact('user')); // Mengirim $user ke view
    }

    /**
     * Menampilkan form untuk mengedit status anggota.
     * Rute: GET /admin/members/{user}/edit-status
     * Nama Rute: admin.members.edit_status
     * @param  \App\Models\User  $user // Menggunakan $user, bukan $member
     * @return \Illuminate\View\View
     */
    public function edit_status(User $user) // Menggunakan $user sebagai parameter
    {
        // Mengirim daftar status yang valid ke view untuk dropdown
        $statuses = ['pending', 'active', 'inactive', 'suspended'];
        // Pastikan view ini ada: resources/views/admin/members/edit_status.blade.php
        return view('admin.members.edit_status', compact('user', 'statuses')); // Mengirim $user dan $statuses
    }

    /**
     * Mengupdate status anggota.
     * Rute: PUT /admin/members/{user}/update-status
     * Nama Rute: admin.members.update_status
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user // Menggunakan $user, bukan $member
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update_status(Request $request, User $user) // Menggunakan $user sebagai parameter
    {
        $request->validate([
            // Menambahkan 'pending' ke daftar status yang valid
            'status' => ['required', 'string', Rule::in(['pending', 'active', 'inactive', 'suspended'])],
        ]);

        try {
            $user->status = $request->status; // Asumsi ada kolom 'status' di tabel users
            $user->save();

            return redirect()->route('admin.members.index')->with('success', 'Status anggota berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui status anggota: ' . $e->getMessage(), ['user_id' => $user->id]);
            return redirect()->back()->with('error', 'Gagal memperbarui status anggota. Silakan coba lagi.');
        }
    }

    /**
     * Menghapus anggota.
     * Rute: DELETE /admin/members/{user}
     * Nama Rute: admin.members.destroy
     * @param  \App\Models\User  $user // Menggunakan $user, bukan $member
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user) // Menggunakan $user sebagai parameter
    {
        try {
            $user->delete();
            return redirect()->route('admin.members.index')->with('success', 'Anggota berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus anggota: ' . $e->getMessage(), ['user_id' => $user->id]);
            return redirect()->back()->with('error', 'Gagal menghapus anggota. Silakan coba lagi.');
        }
    }

    // --- Metode Tambahan (Jika Anda ingin mengimplementasikan fitur CRUD lengkap untuk anggota) ---

    /**
     * Menampilkan form untuk membuat anggota baru.
     * Rute: GET /admin/members/create (Jika Anda menambahkan ini di routes/web.php)
     * Nama Rute: admin.members.create
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // return view('admin.members.create'); // Uncomment dan buat view ini jika diaktifkan
        abort(404, 'Admin Member Create not implemented yet.'); // Placeholder
    }

    /**
     * Menyimpan anggota baru ke database.
     * Rute: POST /admin/members (Jika Anda menambahkan ini di routes/web.php)
     * Nama Rute: admin.members.store
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi input
        // $request->validate([
        //     'full_name' => 'required|string|max:255',
        //     'email' => 'required|string|email|max:255|unique:users',
        //     'password' => 'required|string|min:8|confirmed',
        // ]);

        // try {
        //     User::create([
        //         'full_name' => $request->full_name,
        //         'email' => $request->email,
        //         'password' => Hash::make($request->password),
        //         'status' => 'pending', // Default status untuk user baru yang dibuat admin
        //     ]);
        //     return redirect()->route('admin.members.index')->with('success', 'Anggota baru berhasil ditambahkan.');
        // } catch (\Exception $e) {
        //     Log::error('Gagal menambahkan anggota baru: ' . $e->getMessage());
        //     return redirect()->back()->with('error', 'Gagal menambahkan anggota baru. Silakan coba lagi.');
        // }
        abort(404, 'Admin Member Store not implemented yet.'); // Placeholder
    }

    /**
     * Menampilkan form untuk mengedit anggota.
     * Rute: GET /admin/members/{user}/edit (Jika Anda menambahkan ini di routes/web.php)
     * Nama Rute: admin.members.edit
     * @param  \App\Models\User  $user // Menggunakan $user, bukan $member
     * @return \Illuminate\View\View
     */
    public function edit(User $user) // Menggunakan $user sebagai parameter
    {
        // return view('admin.members.edit', compact('user')); // Uncomment dan buat view ini jika diaktifkan
        abort(404, 'Admin Member Edit not implemented yet.'); // Placeholder
    }

    /**
     * Mengupdate data anggota.
     * Rute: PUT/PATCH /admin/members/{user} (Jika Anda menambahkan ini di routes/web.php)
     * Nama Rute: admin.members.update
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user // Menggunakan $user, bukan $member
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user) // Menggunakan $user sebagai parameter
    {
        // Validasi input
        // $request->validate([
        //     'full_name' => 'required|string|max:255',
        //     'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        //     'status' => ['required', 'string', Rule::in(['pending', 'active', 'inactive', 'suspended'])],
        //     'password' => 'nullable|string|min:8|confirmed', // Opsional jika password tidak diubah
        // ]);

        // try {
        //     $user->full_name = $request->full_name;
        //     $user->email = $request->email;
        //     $user->status = $request->status;
        //     if ($request->filled('password')) {
        //         $user->password = Hash::make($request->password);
        //     }
        //     $user->save();
        //     return redirect()->route('admin.members.index')->with('success', 'Anggota berhasil diperbarui.');
        // } catch (\Exception $e) {
        //     Log::error('Gagal memperbarui anggota: ' . $e->getMessage(), ['user_id' => $user->id]);
        //     return redirect()->back()->with('error', 'Gagal memperbarui anggota. Silakan coba lagi.');
        // }
        abort(404, 'Admin Member Update not implemented yet.'); // Placeholder
    }
}