<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User; // Menggunakan model User (yang disesuaikan untuk anggota)
use App\Models\Tier; // Menggunakan model Tier
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule; // Untuk validasi Enum

class RegisterController extends Controller
{
    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone_number' => ['required', 'string', 'max:20', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'gender' => ['nullable', Rule::in(['Laki-laki', 'Perempuan', 'Tidak_Disebutkan'])],
            'city' => ['nullable', 'string', 'max:255'],
            'province' => ['nullable', 'string', 'max:255'],
            'address_line1' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'terms' => ['required', 'accepted'], // Memastikan checkbox dicentang
        ]);

        // Model User::create() sudah akan memanggil method booted()
        // untuk menetapkan default tier dan poin.
        $user = User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
            'city' => $request->city,
            'province' => $request->province,
            'address_line1' => $request->address_line1,
            'date_of_birth' => $request->date_of_birth,
            // Kolom lain seperti current_points, current_tier_id diatur di User::booted()
            // registration_date otomatis useCurrent() di migrasi
            // status otomatis default 'Aktif' di migrasi
        ]);

        // Otomatis login setelah register
        Auth::login($user);

        return redirect('/dashboard')->with('success', 'Pendaftaran berhasil! Selamat datang di program loyalitas kami.');
    }
}