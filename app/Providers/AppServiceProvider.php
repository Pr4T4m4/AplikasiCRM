<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Transaction; // Import model Transaction
use App\Observers\TransactionObserver; // Import observer yang baru dibuat

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Daftarkan observer untuk model Transaction
        Transaction::observe(TransactionObserver::class);
    }
}
