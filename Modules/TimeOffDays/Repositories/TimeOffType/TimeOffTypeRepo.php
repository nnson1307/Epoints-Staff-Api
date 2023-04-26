<?php
/**
 * Created by PhpStorm
 * User: PhongDT
 */

namespace Modules\TimeOffDays\Repositories\TimeOffType;


use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\TimeOffDays\Models\TimeOffTypeTable;

use MyCore\Repository\PagingTrait;

class TimeOffTypeRepo implements TimeOffTypeRepoInterface
{
    use PagingTrait;
    protected $timeOffType;

    public function __construct(
        TimeOffTypeTable $timeOffType
    ) {
        $this->timeOffType = $timeOffType;
    }

    /**
     * Danh sách
     *
     * @param $input
     * @return mixed|void
     * @throws TimeOffTypeRepoException
     */
    public function getLists($input)
    {
        try {
            return $this->timeOffType->getLists($input);

        } catch (\Exception $exception) {
            throw new TimeOffTypeRepoException(TimeOffTypeRepoException::GET_LIST_FAILED, $exception->getMessage());
        }
    }


    /**
     * Danh sách
     *
     * @param $input
     * @return mixed|void
     * @throws TimeOffTypeRepoException
     */
    public function getListsChild($id)
    {
        try {
            return $this->timeOffType->getListsChild($id);

        } catch (\Exception $exception) {
            throw new TimeOffTypeRepoException(TimeOffTypeRepoException::GET_LIST_FAILED, $exception->getMessage());
        }
    }

    /**
     * Chi tiết
     *
     * @param $input
     * @return mixed|void
     * @throws TimeOffTypeRepoException
     */
    public function detail($id)
    {
        try {
            return $this->timeOffType->detail($id);

        } catch (\Exception $exception) {
            throw new TimeOffTypeRepoException(TimeOffTypeRepoException::GET_LIST_FAILED, $exception->getMessage());
        }
    }
}