<?php
namespace Modules\Customer\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Customer\Repositories\Address\AddressRepo;
use Modules\Customer\Repositories\Address\AddressRepoInterface;
use Modules\Customer\Repositories\Booking\BookingRepo;
use Modules\Customer\Repositories\Booking\BookingRepoInterface;
use Modules\Customer\Repositories\Commission\CommissionRepo;
use Modules\Customer\Repositories\Commission\CommissionRepoInterface;
use Modules\Customer\Repositories\Customer\CustomerRepo;
use Modules\Customer\Repositories\Customer\CustomerRepoInterface;
use Modules\Customer\Repositories\CustomerAppointment\CustomerAppointmentRepo;
use Modules\Customer\Repositories\CustomerAppointment\CustomerAppointmentRepoInterface;
use Modules\Customer\Repositories\CustomerContact\CustomerContactRepo;
use Modules\Customer\Repositories\CustomerContact\CustomerContactRepoInterface;
use Modules\Customer\Repositories\CustomerDebt\CustomerDebtRepo;
use Modules\Customer\Repositories\CustomerDebt\CustomerDebtRepoInterface;
use Modules\Customer\Repositories\CustomerServiceCard\ServiceCardRepo;
use Modules\Customer\Repositories\CustomerServiceCard\ServiceCardRepoInterface;
use Modules\Customer\Repositories\Faq\FaqRepo;
use Modules\Customer\Repositories\Faq\FaqRepoInterface;
use Modules\Customer\Repositories\News\NewRepo;
use Modules\Customer\Repositories\News\NewRepoInterface;
use Modules\Customer\Repositories\Order\OrderRepo;
use Modules\Customer\Repositories\Order\OrderRepoInterface;
use Modules\Customer\Repositories\Point\PointRepo;
use Modules\Customer\Repositories\Point\PointRepoInterface;
use Modules\Customer\Repositories\Product\ProductRepo;
use Modules\Customer\Repositories\Product\ProductRepoInterface;
use Modules\Customer\Repositories\Service\ServiceRepo;
use Modules\Customer\Repositories\Service\ServiceRepoInterface;
use Modules\Customer\Repositories\Voucher\VoucherRepo;
use Modules\Customer\Repositories\Voucher\VoucherRepoInterface;

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
        $this->app->singleton(CustomerRepoInterface::class, CustomerRepo::class);
        $this->app->singleton(CustomerContactRepoInterface::class, CustomerContactRepo::class);
    }
}