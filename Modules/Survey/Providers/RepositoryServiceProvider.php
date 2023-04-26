<?php
namespace Modules\Survey\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Survey\Repositories\ListData\ListDataInterface;
use Modules\Survey\Repositories\ListData\ListDataRepo;
use Modules\Survey\Repositories\Info\SurveyInfoInterface;
use Modules\Survey\Repositories\Info\SurveyInfoRepo;
use Modules\Survey\Repositories\SurveyProcess\SurveyProcessInterface;
use Modules\Survey\Repositories\SurveyProcess\SurveyProcessRepo;

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
        $this->app->singleton(ListDataInterface::class, ListDataRepo::class);
        $this->app->singleton(SurveyInfoInterface::class, SurveyInfoRepo::class);
        $this->app->singleton(SurveyProcessInterface::class, SurveyProcessRepo::class);
    }
}