<?php
namespace Modules\Ticket\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Ticket\Repositories\TicketRepository;
use Modules\Ticket\Repositories\TicketRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(TicketRepositoryInterface::class,TicketRepository::class);
    }
}