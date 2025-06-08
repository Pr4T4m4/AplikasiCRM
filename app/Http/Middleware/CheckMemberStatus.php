<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckMemberStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $status = 'active'): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->status !== $status) {
                // Redirect ke halaman dashboard atau berikan pesan error
                return redirect()->route('member.dashboard')->with('error', 'Akun Anda tidak aktif atau ditangguhkan, Anda tidak dapat menukarkan poin.');
            }
        } else {
            // Jika tidak login, redirect ke halaman login
            return redirect()->route('login');
        }

        return $next($request);
    }
}