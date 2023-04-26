<?php
/**
 * Created by PhpStorm
 * User: PhongDT
 */

namespace Modules\TimeOffDays\Repositories\TimeWorkingStaffs;


use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\TimeOffDays\Models\TimeWorkingStaffsTable;

use MyCore\Repository\PagingTrait;

class TimeWorkingStaffsRepo implements TimeWorkingStaffsRepoInterface
{
    use PagingTrait;
    protected $timeWorkingStaffs;

    public function __construct(
        TimeWorkingStaffsTable $timeWorkingStaffs
    ) {
        $this->timeWorkingStaffs = $timeWorkingStaffs;
    }

    /**
     * Danh sách
     *
     * @param $input
     * @return mixed|void
     * @throws TimeWorkingStaffsRepoException
     */
    public function getLists($input)
    {
        try {
            
            return $this->timeWorkingStaffs->getLists($input);

        } catch (\Exception $exception) {
            
            throw new TimeWorkingStaffsRepoException(TimeWorkingStaffsRepoException::GET_LIST_FAILED, $exception->getMessage());
        }
    }

    public function edit($data, $id){
        try {
            
            return $this->timeWorkingStaffs->edit($data, $id);

        } catch (\Exception $exception) {
            
            throw new TimeWorkingStaffsRepoException(TimeWorkingStaffsRepoException::GET_LIST_FAILED, $exception->getMessage());
        }
        
    }

    public function removeTimeOffDay($id){
        try {
            
            return $this->timeWorkingStaffs->removeTimeOffDay($id);

        } catch (\Exception $exception) {
            
            throw new TimeWorkingStaffsRepoException(TimeWorkingStaffsRepoException::GET_LIST_FAILED, $exception->getMessage());
        }
       
    }
}