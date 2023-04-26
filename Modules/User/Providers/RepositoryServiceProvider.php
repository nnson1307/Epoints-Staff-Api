<?php
namespace Modules\User\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\User\Repositories\Authen\AuthenRepo;
use Modules\User\Repositories\Authen\AuthenRepoInterface;
use Modules\User\Repositories\Brand\BrandRepo;
use Modules\User\Repositories\Brand\BrandRepoInterface;
use Modules\User\Repositories\Debt\DebtRepo;
use Modules\User\Repositories\Debt\DebtRepoInterface;
use Modules\User\Repositories\Device\DeviceRepo;
use Modules\User\Repositories\Device\DeviceRepoInterface;
use Modules\User\Repositories\ForgotPassword\ForgotPasswordRepo;
use Modules\User\Repositories\ForgotPassword\ForgotPasswordRepoInterface;
use Modules\User\Repositories\OTP\OtpRepo;
use Modules\User\Repositories\OTP\OtpRepoInterface;
use Modules\User\Repositories\STSUserManage\STSUserManageInterface;
use Modules\User\Repositories\STSUserManage\STSUserManageRepo;
use Modules\User\Repositories\Upload\UploadRepo;
use Modules\User\Repositories\Upload\UploadRepoInterface;
use Modules\User\Repositories\User\UserRepo;
use Modules\User\Repositories\User\UserRepoInterface;
use Modules\User\Repositories\UserCarrier\UserCarrierRepo;
use Modules\User\Repositories\UserCarrier\UserCarrierRepoInterface;
use MyCore\Storage\Azure\UploadFileToAzureManager;
use MyCore\Storage\Azure\UploadFileToAzureStorage;
use Modules\User\Repositories\UploadAvatar\UploadAvatarRepoInterface;
use Modules\User\Repositories\UploadAvatar\UploadAvatarRepo;

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
        $this->app->singleton(DeviceRepoInterface::class, DeviceRepo::class);
        $this->app->singleton(OtpRepoInterface::class, OtpRepo::class);
        $this->app->singleton(AuthenRepoInterface::class, AuthenRepo::class);
        $this->app->singleton(ForgotPasswordRepoInterface::class, ForgotPasswordRepo::class);
        $this->app->singleton(UploadFileToAzureManager::class, UploadFileToAzureStorage::class);
        $this->app->singleton(ForgotPasswordRepoInterface::class, ForgotPasswordRepo::class);
        $this->app->singleton(BrandRepoInterface::class, BrandRepo::class);
        $this->app->singleton(UploadAvatarRepoInterface::class, UploadAvatarRepo::class);

    }
}