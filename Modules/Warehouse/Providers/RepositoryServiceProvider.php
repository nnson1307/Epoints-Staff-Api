<?php
namespace Modules\Warehouse\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Warehouse\Repositories\Warehouse\WarehouseRepo;
use Modules\Warehouse\Repositories\Warehouse\WarehouseRepoInterface;

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
        $this->app->singleton(WarehouseRepoInterface::class, WarehouseRepo::class);
    }
}