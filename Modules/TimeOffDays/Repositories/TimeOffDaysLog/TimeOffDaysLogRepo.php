<?php
/**
 * Created by PhpStorm
 * User: PhongDT
 */

namespace Modules\TimeOffDays\Repositories\TimeOffDaysLog;


use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\TimeOffDays\Models\TimeOffDaysLogTable;

use MyCore\Repository\PagingTrait;

class TimeOffDaysLogRepo implements TimeOffDaysLogRepoInterface
{
    use PagingTrait;
    protected $repo;

    public function __construct(
        TimeOffDaysLogTable $repo
    ) {
        $this->repo = $repo;
    }

    /**
     * Danh sách
     *
     * @param $input
     * @return mixed|void
     * @throws TimeOffDaysLogRepoException
     */
    public function getLists($input)
    {
        try {
            $input['created_by'] = Auth()->id();
            return $this->repo->getLists($input);

        } catch (\Exception $exception) {
            throw new TimeOffDaysLogRepoException(TimeOffDaysLogRepoException::GET_LIST_FAILED, $exception->getMessage());
        }
    }


    /**
     * Thêm mới
     *
     * @param $input
     * @return mixed|void
     * @throws TimeOffDaysFilesRepoException
     */
    public function add($input)
    {
  
        try {
            $input['created_by'] = Auth()->id();
            return $this->repo->add($input);

        } catch (\Exception $exception) {
            throw new TimeOffDaysLogRepoException(TimeOffDaysLogRepoException::CREATE_FAILED, $exception->getMessage());
        }
    }


}