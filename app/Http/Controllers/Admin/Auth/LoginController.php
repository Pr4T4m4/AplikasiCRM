<?php

namespace App\Http\Controllers\Admin\Auth; // Pastikan namespace ini benar

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider; // Digunakan untuk redirect setelah login
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Digunakan untuk fasilitas otentikasi
use Illuminate\Validation\ValidationException; // Untuk menangani error validasi

class LoginController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Middleware 'guest:admin' akan me-redirect admin yang sudah login
        // dari halaman login admin. Kecuali untuk aksi 'logout'.
        $this->middleware('admin.guest')->except('logout');
    }

    /**
     * Show the application's login form for admin.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('admin.auth.login'); // Mengarahkan ke view resources/views/admin/auth/login.blade.php
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        // Validasi input dari form
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string', // String agar tidak ada masalah dengan karakter khusus
        ]);

        // Coba otentikasi menggunakan guard 'admin'
        // attempt() akan mengembalikan true jika kredensial cocok dan user sudah login,
        // false jika tidak cocok.
        if (Auth::guard('admin')->attempt(
            ['email' => $request->email, 'password' => $request->password],
            $request->remember // Cekbox 'remember me'
        )) {
            // Jika login berhasil, redirect ke home admin dashboard
            return redirect()->intended(RouteServiceProvider::ADMIN_HOME);
        }

        // Jika login gagal, lemparkan error validasi kembali ke form
        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')], // Menggunakan pesan standar Laravel 'auth.failed'
        ]);
    }

    /**
     * Log the admin out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // Logout admin dari guard 'admin'
        Auth::guard('admin')->logout();

        // Invalidasi sesi dan regenerasi token CSRF untuk keamanan
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect admin ke halaman login admin setelah logout
        return redirect()->route('admin.login');
    }
}