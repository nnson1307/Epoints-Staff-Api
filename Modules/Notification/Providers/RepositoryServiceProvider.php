<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-03-24
 * Time: 12:05 PM
 * @author SonDepTrai
 */

namespace Modules\Notification\Providers;


use Illuminate\Support\ServiceProvider;
use Modules\Notification\Repositories\Notification\NotificationRepo;
use Modules\Notification\Repositories\Notification\NotificationRepoInterface;


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
        $this->app->singleton(NotificationRepoInterface::class, NotificationRepo::class);
    }
}