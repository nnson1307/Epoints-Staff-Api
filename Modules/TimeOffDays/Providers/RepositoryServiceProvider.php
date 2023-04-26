<?php
namespace Modules\TimeOffDays\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\TimeOffDays\Repositories\TimeOffType\TimeOffTypeRepo;
use Modules\TimeOffDays\Repositories\TimeOffType\TimeOffTypeRepoInterface;
use Modules\TimeOffDays\Repositories\TimeOffDays\TimeOffDaysRepo;
use Modules\TimeOffDays\Repositories\TimeOffDays\TimeOffDaysRepoInterface;
use Modules\TimeOffDays\Repositories\Staffs\StaffRepo;
use Modules\TimeOffDays\Repositories\Staffs\StaffRepoInterface;
use Modules\TimeOffDays\Repositories\TimeWorkingStaffs\TimeWorkingStaffsRepo;
use Modules\TimeOffDays\Repositories\TimeWorkingStaffs\TimeWorkingStaffsRepoInterface;

use Modules\TimeOffDays\Repositories\TimeOffDaysActivityApprove\TimeOffDaysActivityApproveRepo;
use Modules\TimeOffDays\Repositories\TimeOffDaysActivityApprove\TimeOffDaysActivityApproveRepoInterface;

use Modules\TimeOffDays\Repositories\TimeOffDaysFiles\TimeOffDaysFilesRepo;
use Modules\TimeOffDays\Repositories\TimeOffDaysFiles\TimeOffDaysFilesRepoInterface;

use Modules\TimeOffDays\Repositories\TimeOffDaysShifts\TimeOffDaysShiftsRepo;
use Modules\TimeOffDays\Repositories\TimeOffDaysShifts\TimeOffDaysShiftsRepoInterface;

use Modules\TimeOffDays\Repositories\TimeOffDaysLog\TimeOffDaysLogRepo;
use Modules\TimeOffDays\Repositories\TimeOffDaysLog\TimeOffDaysLogRepoInterface;

use Modules\TimeOffDays\Repositories\TimeOffDaysConfigApprove\TimeOffDaysConfigApproveRepo;
use Modules\TimeOffDays\Repositories\TimeOffDaysConfigApprove\TimeOffDaysConfigApproveRepoInterface;

use Modules\TimeOffDays\Repositories\SFShifts\SFShiftsRepo;
use Modules\TimeOffDays\Repositories\SFShifts\SFShiftsRepoInterface;

use Modules\TimeOffDays\Repositories\TimeOffDaysTotal\TimeOffDaysTotalRepo;
use Modules\TimeOffDays\Repositories\TimeOffDaysTotal\TimeOffDaysTotalRepoInterface;

use Modules\TimeOffDays\Repositories\TimeOffDaysTime\TimeOffDaysTimeRepo;
use Modules\TimeOffDays\Repositories\TimeOffDaysTime\TimeOffDaysTimeRepoInterface;

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
        $this->app->singleton(TimeOffTypeRepoInterface::class, TimeOffTypeRepo::class);
        $this->app->singleton(TimeOffDaysRepoInterface::class, TimeOffDaysRepo::class);
        $this->app->singleton(StaffRepoInterface::class, StaffRepo::class);
        $this->app->singleton(TimeWorkingStaffsRepoInterface::class, TimeWorkingStaffsRepo::class);
        $this->app->singleton(TimeOffDaysActivityApproveRepoInterface::class, TimeOffDaysActivityApproveRepo::class);
        $this->app->singleton(TimeOffDaysFilesRepoInterface::class, TimeOffDaysFilesRepo::class);
        $this->app->singleton(TimeOffDaysShiftsRepoInterface::class, TimeOffDaysShiftsRepo::class);
        $this->app->singleton(TimeOffDaysLogRepoInterface::class, TimeOffDaysLogRepo::class);
        $this->app->singleton(TimeOffDaysConfigApproveRepoInterface::class, TimeOffDaysConfigApproveRepo::class);
        $this->app->singleton(SFShiftsRepoInterface::class, SFShiftsRepo::class);
        $this->app->singleton(TimeOffDaysTotalRepoInterface::class, TimeOffDaysTotalRepo::class);
        $this->app->singleton(TimeOffDaysTimeRepoInterface::class, TimeOffDaysTimeRepo::class);


    }
}