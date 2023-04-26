<?php
namespace Modules\ProjectManagement\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\ProjectManagement\Repositories\Project\ProjectInterface;
use Modules\ProjectManagement\Repositories\Project\ProjectRepo;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ProjectInterface::class, ProjectRepo::class);
    }
}