<?php

/**
 * Created by PhpStorm
 * User: PhongDT
 */

namespace Modules\TimeOffDays\Repositories\TimeOffDaysConfigApprove;


use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\TimeOffDays\Models\TimeOffTypeOptionTable;
use Modules\TimeOffDays\Models\StaffsTable;

use MyCore\Repository\PagingTrait;

class TimeOffDaysConfigApproveRepo implements TimeOffDaysConfigApproveRepoInterface
{
    use PagingTrait;
    protected $repo;
    protected $mStaff;

    public function __construct(
        TimeOffTypeOptionTable $repo,
        StaffsTable $mStaff
    ) {
        $this->repo = $repo;
        $this->mStaff = $mStaff;
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
            $arrOption = $this->repo->getLists($input);
            $optionInfo = [];
            foreach ($arrOption as $objOption) {
                if ($objOption['time_off_type_option_key'] == 'approve_level_1' || $objOption['time_off_type_option_key'] == 'approve_level_2' || $objOption['time_off_type_option_key'] == 'approve_level_3') {
                    $optionInfo[$objOption['time_off_type_option_key']] = $objOption['time_off_type_option_value'];
                }
            }

            $arrayData = [];
            foreach ($optionInfo as $item) {
                $staffInfo = $this->mStaff->getStaffApproveInfo($item);
                if (isset($staffInfo)) {
                    $arrayData[] =
                        [
                            'staff_id' => $staffInfo['staff_id'],
                            'full_name' => $staffInfo['full_name'],
                            'staff_avatar' => $staffInfo['staff_avatar'],
                            'staff_title' => $staffInfo['staff_title'],
                            'staff_title_id' => $staffInfo['staff_title_id'],
                        ];
                }
            }
            return $arrayData;
        } catch (\Exception $exception) {
            throw new TimeOffDaysConfigApproveRepoException(TimeOffDaysConfigApproveRepoException::GET_LIST_FAILED, $exception->getMessage());
        }
    }

    /**
     * Tạo TimeOffDaysConfigApprove
     *
     * @param $input
     * @return mixed|void
     * @throws TimeOffDaysConfigApproveRepoException
     */
    public function add($input)
    {

        try {
            $input['created_by'] = Auth()->id();

            return $this->repo->add($input);
        } catch (\Exception $exception) {

            throw new TimeOffDaysConfigApproveRepoException(TimeOffDaysConfigApproveRepoException::CREATE_FAILED, $exception->getMessage());
        }
    }
}
