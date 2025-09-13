<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\StudentTransportContract;
use App\Observers\StudentTransportContractObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registrar observers
        StudentTransportContract::observe(StudentTransportContractObserver::class);
    }
}
