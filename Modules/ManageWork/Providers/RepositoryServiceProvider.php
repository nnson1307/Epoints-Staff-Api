<?php
namespace Modules\ManageWork\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\ManageWork\Repositories\ManageWorkRepository;
use Modules\ManageWork\Repositories\ManageWorkRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ManageWorkRepositoryInterface::class,ManageWorkRepository::class);
    }
}