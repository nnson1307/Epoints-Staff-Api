<?php
namespace Modules\Report\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Report\Repositories\Inventory\InventoryRepo;
use Modules\Report\Repositories\Inventory\InventoryRepoInterface;
use Modules\Report\Repositories\ReportRevenueOrder\ReportRevenueOrderRepo;
use Modules\Report\Repositories\ReportRevenueOrder\ReportRevenueOrderRepoInterface;
use Modules\Report\Repositories\StaffCommission\StaffCommissionRepo;
use Modules\Report\Repositories\StaffCommission\StaffCommissionRepoInterface;


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
        $this->app->singleton(ReportRevenueOrderRepoInterface::class, ReportRevenueOrderRepo::class);
        $this->app->singleton(InventoryRepoInterface::class, InventoryRepo::class);
        $this->app->singleton(StaffCommissionRepoInterface::class, StaffCommissionRepo::class);
    }
}