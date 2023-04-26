<?php

/**
 * Created by PhpStorm
 * User: PhongDT
 */

namespace Modules\TimeOffDays\Repositories\TimeOffDays;


use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\TimeOffDays\Models\TimeOffDaysTable;

use MyCore\Repository\PagingTrait;

class TimeOffDaysRepo implements TimeOffDaysRepoInterface
{
    use PagingTrait;
    protected $timeOffType;
    protected $timeOffDays;

    public function __construct(
        TimeOffDaysTable $timeOffDays
    ) {
        $this->timeOffDays = $timeOffDays;
    }

    /**
     * Danh sách
     *
     * @param $input
     * @return mixed|void
     * @throws TimeOffDaysRepoException
     */
    public function getLists($input)
    {
        try {
            return $this->timeOffDays->getLists($input);
        } catch (\Exception $exception) {
            throw new TimeOffDaysRepoException(TimeOffDaysRepoException::GET_LIST_FAILED, $exception->getMessage());
        }
    }

    /**
     * Thêm mới
     *
     * @param $input
     * @return mixed|void
     * @throws TimeOffDaysRepoException
     */
    public function add($input)
    {

        try {
            $input['created_by'] = Auth()->id();
            return $this->timeOffDays->add($input);
        } catch (\Exception $exception) {
            throw new TimeOffDaysRepoException(TimeOffDaysRepoException::CREATE_FAILED,  $exception->getMessage());
        }
    }


    /**
     * Chi tiết ngày phép
     *
     * @param $input
     * @return mixed|void
     * @throws TimeOffDaysRepoException
     */
    public function detail($id)
    {
        try {
            return $this->timeOffDays->detail($id);
        } catch (\Exception $exception) {
            throw new TimeOffDaysRepoException(TimeOffDaysRepoException::GET_DETAIL_FAILED, $exception->getMessage());
        }
    }

    /**
     * Xoa ngày phép
     *
     * @param $id
     * @return mixed|void
     * @throws TimeOffDaysRepoException
     */
    public function remove($id)
    {
        try {
            return $this->timeOffDays->remove($id);
        } catch (\Exception $exception) {
            throw new TimeOffDaysRepoException(TimeOffDaysRepoException::REMOVE_FAILED, $exception->getMessage());
        }
    }


    /**
     * Chinh sua ngày phép
     *
     * @param $id
     * @return mixed|void
     * @throws TimeOffDaysRepoException
     */
    public function edit($input, $id)
    {
        try {
            return $this->timeOffDays->edit($input, $id);
        } catch (\Exception $exception) {
            throw new TimeOffDaysRepoException(TimeOffDaysRepoException::EDIT_FAILED, $exception->getMessage());
        }
    }

    /**
     * Tổng ngày phép
     *
     * @param $id
     * @return mixed|void
     * @throws TimeOffDaysRepoException
     */
    public function total($id)
    {
        try {
            return $this->timeOffDays->total($id);
        } catch (\Exception $exception) {
            throw new TimeOffDaysRepoException(TimeOffDaysRepoException::GET_LIST_FAILED, $exception->getMessage());
        }
    }

    /**
     * Tổng ngày phép
     *
     * @param $id
     * @return mixed|void
     * @throws TimeOffDaysRepoException
     */
    public function countById($id)
    {
        try {
            return $this->timeOffDays->countById($id);
        } catch (\Exception $exception) {
            throw new TimeOffDaysRepoException(TimeOffDaysRepoException::GET_LIST_FAILED, $exception->getMessage());
        }
    }
}
