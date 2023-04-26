<?php
/**
 * Created by PhpStorm
 * User: PhongDT
 */

namespace Modules\TimeOffDays\Repositories\TimeOffDaysActivityApprove;


use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\TimeOffDays\Models\TimeOffDaysActivityApproveTable;

use MyCore\Repository\PagingTrait;

class TimeOffDaysActivityApproveRepo implements TimeOffDaysActivityApproveRepoInterface
{
    use PagingTrait;
    protected $repo;

    public function __construct(
        TimeOffDaysActivityApproveTable $repo
    ) {
        $this->repo = $repo;
    }

    /**
     * Danh sách
     *
     * @param $input
     * @return mixed|void
     * @throws TimeOffDaysActivityApproveRepoException
     */
    public function getLists($input)
    {
        try {
            return $this->repo->getLists($input);

        } catch (\Exception $exception) {
            throw new TimeOffDaysActivityApproveRepoException(TimeOffDaysActivityApproveRepoException::GET_LIST_FAILED, $exception->getMessage());
        }
    }

    /**
     * Tạo TimeOffDaysActivityApprove
     *
     * @param $input
     * @return mixed|void
     * @throws TimeOffDaysActivityApproveRepoException
     */
    public function add($input)
    {
  
        try {
            $input['created_by'] = Auth()->id();

            return $this->repo->add($input);

        } catch (\Exception $exception) {
            
            throw new TimeOffDaysActivityApproveRepoException(TimeOffDaysActivityApproveRepoException::CREATE_FAILED, $exception->getMessage());
        }
    }
    

}