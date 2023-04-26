<?php
/**
 * Created by PhpStorm
 * User: PhongDT
 */

namespace Modules\TimeOffDays\Repositories\TimeOffDaysTotal;


use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\TimeOffDays\Models\TimeOffDaysTotalTable;

use MyCore\Repository\PagingTrait;

class TimeOffDaysTotalRepo implements TimeOffDaysTotalRepoInterface
{
    use PagingTrait;
    protected $repo;

    public function __construct(
        TimeOffDaysTotalTable $repo
    ) {
        $this->repo = $repo;
    }

    /**
     * Danh sách
     *
     * @param $id
     * @return mixed|void
     * @throws TimeOffDaysTotalRepoException
     */
    public function getLists($id)
    {
        try {
            
            return $this->repo->getLists($id);

        } catch (\Exception $exception) {
            
            throw new TimeOffDaysTotalRepoException(TimeOffDaysTotalRepoException::GET_LIST_FAILED, $exception->getMessage());
        }
    }

    public function checkValidTotal($staffId, $typeId){

        return $this->repo->checkValidTotal($staffId, $typeId);
    }

    public function edit($data, $staffId, $typeOffDaysId){
        return $this->repo->edit($data, $staffId, $typeOffDaysId);
    }

}