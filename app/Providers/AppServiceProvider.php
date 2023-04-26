<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
#use MyCore\Storage\Redis\AuthJwtStorage;
#use MyCore\Storage\Redis\AuthJwtStorageManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        #$this->app->singleton(AuthJwtStorageManager::class, AuthJwtStorage::class);
    }
}
