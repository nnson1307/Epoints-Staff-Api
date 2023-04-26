<?php namespace App\Providers;

use App\Auth\MyAuthUserCarrierProvider;
use Illuminate\Support\ServiceProvider;
use Auth;

class MyAuthCarrierProvider extends ServiceProvider {
    
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Auth::provider('custom-carrier', function($app, array $config) {
            return new MyAuthUserCarrierProvider();
        });
    }
    
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
    
}