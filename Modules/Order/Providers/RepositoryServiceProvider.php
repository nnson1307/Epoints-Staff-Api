<?php
namespace Modules\Order\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Order\Repositories\Order\OrderRepo;
use Modules\Order\Repositories\Order\OrderRepoInterface;


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
        $this->app->singleton(OrderRepoInterface::class, OrderRepo::class);

    }
}