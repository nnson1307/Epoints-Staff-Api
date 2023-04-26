<?php
/**
 * Created by PhpStorm
 * User: PhongDT
 */

namespace Modules\TimeOffDays\Repositories\TimeOffDaysTime;


use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\TimeOffDays\Models\TimeOffDayTimeTable;
use MyCore\Repository\PagingTrait;

class TimeOffDaysTimeRepo implements TimeOffDaysTimeRepoInterface
{
    use PagingTrait;
    protected $timeOffDaysTime;

    public function __construct(
        TimeOffDayTimeTable $timeOffDaysTime
    ) {
        $this->timeOffDaysTime = $timeOffDaysTime;
    }

    /**
     * Danh sách
     *
     * @param $input
     * @return mixed|void
     * @throws TimeOffTypeRepoException
     */
    public function getLists()
    {
        try {
            return $this->timeOffDaysTime->getLists();

        } catch (\Exception $exception) {
            throw new TimeOffDaysTimeException(TimeOffDaysTimeException::GET_LIST_FAILED, $exception->getMessage());
        }
    }

    /**
     * Danh sách
     *
     * @param $input
     * @return mixed|void
     * @throws TimeOffTypeRepoException
     */
    public function getOption()
    {
        try {
            return $this->timeOffDaysTime->getOption();

        } catch (\Exception $exception) {
            throw new TimeOffDaysTimeException(TimeOffDaysTimeException::GET_LIST_FAILED, $exception->getMessage());
        }
    }

     /**
     * Lấy thông ti tiết
     *
     * @param $input
     * @return mixed|void
     * @throws TimeOffTypeRepoException
     */
    public function getDetail($id){
        try {
            return $this->timeOffDaysTime->getDetail($id);

        } catch (\Exception $exception) {
            throw new TimeOffDaysTimeException(TimeOffDaysTimeException::GET_LIST_FAILED, $exception->getMessage(), $exception->getLine());
        }
    }
}