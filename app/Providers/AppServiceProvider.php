<?php

namespace App\Providers;

use App\Repositories\Customer\CustomerCachedRepository;
use App\Repositories\Customer\CustomerDbRepository;
use App\Repositories\Customer\CustomerFileRepository;
use App\Repositories\Customer\ICustomerRepository;
use App\Services\Customer\CustomerLogService;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ICustomerRepository::class, function ($app) {
            $filePath = config('customer.file_path');
            $fileRepo = new CustomerFileRepository($filePath);
            return new CustomerCachedRepository($fileRepo);
        });

//        $this->app->bind(ICustomerRepository::class, function ($app) {
//            $dbRepo = new CustomerDbRepository();
//            return new CustomerCachedRepository($dbRepo);
//        });

        $this->app->singleton(CustomerLogService::class, function ($app) {
            return new CustomerLogService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Passport::enablePasswordGrant();
        Passport::tokensExpireIn(now()->addMinutes(1));
        Passport::refreshTokensExpireIn(now()->addDays(1));
    }
}
