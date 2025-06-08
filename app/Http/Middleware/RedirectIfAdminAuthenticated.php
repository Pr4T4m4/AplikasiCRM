<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAdminAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? ['web'] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if ($guard === 'admin') {
                    return redirect(RouteServiceProvider::ADMIN_HOME);
                }
                // Jika Anda memiliki guard 'web' dan ingin mengarahkan pengguna biasa
                // yang sudah login dari halaman login mereka ke dashboard mereka.
                // if ($guard === 'web') {
                //     return redirect(RouteServiceProvider::HOME);
                // }
            }
        }

        return $next($request);
    }
}