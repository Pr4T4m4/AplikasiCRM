<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    // KEMBALIKAN INI KE PATH DASHBOARD MEMBER ANDA
    public const HOME = '/dashboard'; // Atau '/home' jika itu default dashboard member Anda

    /**
     * The path to the "admin home" route for your application.
     *
     * Typically, admins are redirected here after authentication.
     *
     * @var string
     */
    // INI ADALAH KONSTANTA UNTUK REDIRECT ADMIN
    public const ADMIN_HOME = '/admin/dashboard';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers'; // Pastikan ini tetap di-comment atau dihapus jika tidak digunakan

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                // ->namespace($this->namespace) // Jangan gunakan ini jika Anda mengimpor controller secara langsung
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                // ->namespace($this->namespace) // Jangan gunakan ini jika Anda mengimpor controller secara langsung
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}