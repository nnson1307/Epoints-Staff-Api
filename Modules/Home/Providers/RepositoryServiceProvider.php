<?php
namespace Modules\Home\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Home\Repositories\Home\HomeRepo;
use Modules\Home\Repositories\Home\HomeRepoInterface;


/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 8/7/2020
 * Time: 3:36 PM
 */

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Khai báo cái repository ở đây
//        $this->app->singleton(DeliveryRepoInterface::class, DeliveryRepo::class);
        $this->app->singleton(HomeRepoInterface::class, HomeRepo::class);
    }
}