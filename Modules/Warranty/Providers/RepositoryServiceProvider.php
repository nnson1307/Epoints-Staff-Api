<?php
namespace Modules\Warranty\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Warranty\Repositories\Maintenance\MaintenanceRepo;
use Modules\Warranty\Repositories\Maintenance\MaintenanceRepoInterface;
use Modules\Warranty\Repositories\WarrantyCard\WarrantyCardRepo;
use Modules\Warranty\Repositories\WarrantyCard\WarrantyCardRepoInterface;

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
        $this->app->singleton(WarrantyCardRepoInterface::class, WarrantyCardRepo::class);
        $this->app->singleton(MaintenanceRepoInterface::class, MaintenanceRepo::class);
    }
}