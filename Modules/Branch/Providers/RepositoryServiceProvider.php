<?php
namespace Modules\Branch\Providers;

use Modules\Branch\Providers\BranchServiceProvider;
use Modules\Branch\Repositories\Branch\BranchRepo;
use Modules\Branch\Repositories\Branch\BranchRepoInterface;


class RepositoryServiceProvider extends BranchServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Khai báo cái repository ở đây
        $this->app->singleton(BranchRepoInterface::class, BranchRepo::class);
    }
}