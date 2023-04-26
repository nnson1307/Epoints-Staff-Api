<?php
namespace Modules\Chat\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Chat\Repositories\ChatInterface;
use Modules\Chat\Repositories\ChatRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ChatInterface::class,ChatRepository::class);
    }
}