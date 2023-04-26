<?php
namespace Modules\CustomerLead\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\CustomerLead\Repositories\CustomerLead\CustomerLeadRepoInterface;
use Modules\CustomerLead\Repositories\CustomerLead\CustomerLeadRepo;
use Modules\CustomerLead\Repositories\CustomerDeals\CustomerDealsRepoInterface;
use Modules\CustomerLead\Repositories\CustomerDeals\CustomerDealRepo;


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
        $this->app->singleton(CustomerLeadRepoInterface::class, CustomerLeadRepo::class);
        $this->app->singleton(CustomerDealsRepoInterface::class, CustomerDealRepo::class);
    }
}