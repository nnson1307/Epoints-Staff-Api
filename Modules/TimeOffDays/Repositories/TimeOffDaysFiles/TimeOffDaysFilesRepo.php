<?php
/**
 * Created by PhpStorm
 * User: PhongDT
 */

namespace Modules\TimeOffDays\Repositories\TimeOffDaysFiles;


use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\TimeOffDays\Models\TimeOffDaysFilesTable;

use MyCore\Repository\PagingTrait;

class TimeOffDaysFilesRepo implements TimeOffDaysFilesRepoInterface
{
    use PagingTrait;
    protected $repo;

    public function __construct(
        TimeOffDaysFilesTable $repo
    ) {
        $this->repo = $repo;
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
            return $this->repo->getLists($input);

        } catch (\Exception $exception) {
            throw new TimeOffDaysFilesRepoException(TimeOffDaysFilesRepoException::GET_LIST_FAILED, $exception->getMessage());
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
            throw new TimeOffDaysFilesRepoException(TimeOffDaysFilesRepoException::CREATE_FAILED, $exception->getMessage());
        }
    }

    /**
     * xóa
     *
     * @param $input
     * @return mixed|void
     * @throws TimeOffDaysFilesRepoException
     */
    public function remove($id)
    {
  
        try {
            return $this->repo->remove($id);

        } catch (\Exception $exception) {
            throw new TimeOffDaysFilesRepoException(TimeOffDaysFilesRepoException::REMOVE_FAILED, $exception->getMessage());
        }
    }

}