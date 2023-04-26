<?php
namespace Modules\Promotion\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Promotion\Repositories\Promotion\PromotionRepo;
use Modules\Promotion\Repositories\Promotion\PromotionRepoInterface;


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
        $this->app->singleton(PromotionRepoInterface::class, PromotionRepo::class);
    }
}