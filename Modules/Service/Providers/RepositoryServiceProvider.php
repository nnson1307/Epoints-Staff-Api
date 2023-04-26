<?php
namespace Modules\Service\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Service\Repositories\Service\ServiceRepo;
use Modules\Service\Repositories\Service\ServiceRepoInterface;
use Modules\Service\Repositories\ServiceCategory\ServiceCategoryRepo;
use Modules\Service\Repositories\ServiceCategory\ServiceCategoryRepoInterface;

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
        $this->app->singleton(ServiceRepoInterface::class, ServiceRepo::class);
        $this->app->singleton(ServiceCategoryRepoInterface::class, ServiceCategoryRepo::class);
    }
}