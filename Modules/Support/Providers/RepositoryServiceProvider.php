<?php
namespace Modules\Support\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Support\Repositories\Support\SupportRepoInterface;
use Modules\Support\Repositories\Support\SupportRepo;

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
        $this->app->singleton(SupportRepoInterface::class, SupportRepo::class);
    }
}