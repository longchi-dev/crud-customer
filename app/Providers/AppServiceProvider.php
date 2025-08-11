<?php

namespace App\Providers;

use App\Repositories\CustomerDbRepository;
use App\Repositories\CustomerFileRepository;
use App\Repositories\ICustomerRepository;
use App\Services\CustomerLogService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ICustomerRepository::class, function ($app) {
            $filePath = config('customers.file_path');
            return new CustomerFileRepository($filePath);
        });
        // $this->app->bind(ICustomerRepository::class, CustomerDbRepository::class);

        $this->app->singleton(CustomerLogService::class, function ($app) {
            return new CustomerLogService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
