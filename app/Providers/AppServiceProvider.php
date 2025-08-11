<?php

namespace App\Providers;

use App\Repositories\CustomerDbRepository;
use App\Repositories\CustomerFileRepository;
use App\Repositories\ICustomerRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ICustomerRepository::class, CustomerFileRepository::class);
        // $this->app->bind(ICustomerRepository::class, CustomerDbRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
