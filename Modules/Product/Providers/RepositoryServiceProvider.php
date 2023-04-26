<?php
namespace Modules\Product\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Product\Repositories\Product\ProductRepo;
use Modules\Product\Repositories\Product\ProductRepoInterface;
use Modules\Product\Repositories\ProductCategory\ProductCategoryRepo;
use Modules\Product\Repositories\ProductCategory\ProductCategoryRepoInterface;


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
        $this->app->singleton(ProductRepoInterface::class, ProductRepo::class);
        $this->app->singleton(ProductCategoryRepoInterface::class, ProductCategoryRepo::class);
    }
}