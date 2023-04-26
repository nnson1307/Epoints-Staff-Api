<?php
/**
 * Created by PhpStorm
 * User: PhongDT
 */

namespace Modules\TimeOffDays\Repositories\TimeOffDaysShifts;


use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\TimeOffDays\Models\TimeOffDaysShiftsTable;

use MyCore\Repository\PagingTrait;

class TimeOffDaysShiftsRepo implements TimeOffDaysShiftsRepoInterface
{
    use PagingTrait;
    protected $repo;

    public function __construct(
        TimeOffDaysShiftsTable $repo
    ) {
        $this->repo = $repo;
    }

    /**
     * Danh sách
     *
     * @param $input
     * @return mixed|void
     * @throws TimeOffDaysShiftsRepoException
     */
    public function getLists($input)
    {
        try {
            return $this->repo->getLists($input);

        } catch (\Exception $exception) {
            throw new TimeOffDaysShiftsRepoException(TimeOffDaysShiftsRepoException::GET_LIST_FAILED, $exception->getMessage());
        }
    }

    /**
     * Thêm mới
     *
     * @param $input
     * @return mixed|void
     * @throws TimeOffDaysShiftsRepoException
     */
    public function add($input)
    {
  
        try {
            $input['created_by'] = Auth()->id();
            return $this->repo->add($input);

        } catch (\Exception $exception) {
            throw new TimeOffDaysShiftsRepoException(TimeOffDaysShiftsRepoException::CREATE_FAILED, $exception->getMessage());
        }
    }

    /**
     * xóa
     *
     * @param $input
     * @return mixed|void
     * @throws TimeOffDaysShiftsRepoException
     */
    public function remove($id)
    {
  
        try {
            return $this->repo->remove($id);

        } catch (\Exception $exception) {
            throw new TimeOffDaysShiftsRepoException(TimeOffDaysShiftsRepoException::REMOVE_FAILED, $exception->getMessage());
        }
    }

    /**
     * Lấy tổng số ngày phép đã nghĩ
     * @data filter: 
     *  staff_id
     *  time_off_type_id
     *  month
     *  years
     *  month_reset
     */
    public function getNumberDaysOff($data){
        return $this->repo->getNumberDaysOff($data);
    }

    public function getListsByDaysOff($daysOffId){
        return $this->repo->getListsByDaysOff($daysOffId);
    }
    
    public function edit($data, $id){
        return $this->repo->edit($data, $id);
    }
}