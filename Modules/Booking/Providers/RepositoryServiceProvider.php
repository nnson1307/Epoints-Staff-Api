<?php
namespace Modules\Booking\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Booking\Repositories\Address\AddressRepo;
use Modules\Booking\Repositories\Address\AddressRepoInterface;
use Modules\Booking\Repositories\Booking\BookingRepo;
use Modules\Booking\Repositories\Booking\BookingRepoInterface;

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
        $this->app->singleton(AddressRepoInterface::class, AddressRepo::class);
        $this->app->singleton(BookingRepoInterface::class, BookingRepo::class);
    }
}