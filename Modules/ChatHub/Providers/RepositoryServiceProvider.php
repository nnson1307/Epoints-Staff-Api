<?php
namespace Modules\ChatHub\Providers;

use Modules\ChatHub\Providers\ChatHubServiceProvider;
use Modules\ChatHub\Repositories\ChatHub\ChatHubRepo;
use Modules\ChatHub\Repositories\ChatHub\ChatHubRepoInterface;
use Modules\ChatHub\Repositories\CustomerLead\CustomerLeadRepo;
use Modules\ChatHub\Repositories\CustomerLead\CustomerLeadRepoInterface;
use Modules\ChatHub\Repositories\Order\OrderRepo;
use Modules\ChatHub\Repositories\Order\OrderRepoInterface;
use Modules\ChatHub\Repositories\Product\ProductRepo;
use Modules\ChatHub\Repositories\Product\ProductRepoInterface;
use Modules\ChatHub\Repositories\Customer\CustomerRepo;
use Modules\ChatHub\Repositories\Customer\CustomerRepoInterface;

class RepositoryServiceProvider extends ChatHubServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Khai báo cái repository ở đây
        $this->app->singleton(ChatHubRepoInterface::class, ChatHubRepo::class);
        $this->app->singleton(CustomerLeadRepoInterface::class, CustomerLeadRepo::class);
        $this->app->singleton(OrderRepoInterface::class, OrderRepo::class);
        $this->app->singleton(ProductRepoInterface::class, ProductRepo::class);
        $this->app->singleton(CustomerRepoInterface::class, CustomerRepo::class);
    }
}