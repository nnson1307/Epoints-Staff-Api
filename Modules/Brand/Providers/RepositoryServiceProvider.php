<?php
namespace Modules\Brand\Providers;


use Modules\Brand\Repositories\Brand\BrandRepo;
use Modules\Brand\Repositories\Brand\BrandRepoInterface;

class RepositoryServiceProvider extends BrandServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Khai báo cái repository ở đây
        $this->app->singleton(BrandRepoInterface::class, BrandRepo::class);
    }
}