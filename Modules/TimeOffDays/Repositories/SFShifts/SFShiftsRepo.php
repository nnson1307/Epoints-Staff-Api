<?php
/**
 * Created by PhpStorm
 * User: PhongDT
 */

namespace Modules\TimeOffDays\Repositories\SFShifts;


use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\TimeOffDays\Models\SFShiftsTable;

use MyCore\Repository\PagingTrait;

class SFShiftsRepo implements SFShiftsRepoInterface
{
    use PagingTrait;
    protected $repo;

    public function __construct(
        SFShiftsTable $repo
    ) {
        $this->repo = $repo;
    }

    /**
     * Danh sách
     *
     * @param $input
     * @return mixed|void
     * @throws SFShiftsRepoException
     */
    public function getLists($input)
    {
        try {
            
            return $this->repo->getLists($input);

        } catch (\Exception $exception) {
            
            throw new SFShiftsRepoException(SFShiftsRepoException::GET_LIST_FAILED, $exception->getMessage());
        }
    }

}