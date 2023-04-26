<?php
namespace Modules\TimeKeeping\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\TimeKeeping\Repositories\TimeKeepingInterface;
use Modules\TimeKeeping\Repositories\TimeKeepingRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(TimeKeepingInterface::class,TimeKeepingRepository::class);
    }
}