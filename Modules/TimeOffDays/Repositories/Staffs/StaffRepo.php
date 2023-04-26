<?php

/**
 * Created by PhpStorm
 * User: PhongDT
 */

namespace Modules\TimeOffDays\Repositories\Staffs;


use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\TimeOffDays\Models\StaffsTable;
use Modules\TimeOffDays\Models\TimeOffTypeTable;

use MyCore\Repository\PagingTrait;

class StaffRepo implements StaffRepoInterface
{
    use PagingTrait;
    protected $staffs;

    public function __construct(
        StaffsTable $staffs
    ) {
        $this->staffs = $staffs;
    }

    /**
     * Danh sách
     *
     * @param $input
     * @return mixed|void
     * @throws StaffRepoException
     */
    public function getLists($input)
    {
        try {

            return $this->staffs->getLists($input);
        } catch (\Exception $exception) {

            throw new StaffRepoException(StaffRepoException::GET_LIST_FAILED, $exception->getMessage());
        }
    }

    public function getListStaffApprove($timeOffTypeId)
    {
        $timeOffType = new TimeOffTypeTable();
        $item = $timeOffType->getDetail($timeOffTypeId);
        $arrayData = [];
        if($item['direct_management_approve'] != 0){
            $staffInfo = $this->staffs->getDetailApproveLevel1(Auth::user()->department_id);
           
            if(isset($staffInfo)){
                $arrayData[] =
                    [
                        'staff_id' => $staffInfo['staff_id'],
                        'full_name' => $staffInfo['full_name'],
                        'staff_avatar' => $staffInfo['staff_avatar'],
                        'staff_title' => $staffInfo['staff_title'],
                        'staff_title_id' => $staffInfo['staff_title_id'],
                        'department_name' => $staffInfo['department_name'],
                    ];
            }
        }
        if(isset($item['staff_id_approve_level2'])){
            $staffInfo = $this->staffs->getDetailStaffApproveInfo($item['staff_id_approve_level2']);
            if(isset($staffInfo)){
                $arrayData[] =
                    [
                        'staff_id' => $staffInfo['staff_id'],
                        'full_name' => $staffInfo['full_name'],
                        'staff_avatar' => $staffInfo['staff_avatar'],
                        'staff_title' => $staffInfo['staff_title'],
                        'staff_title_id' => $staffInfo['staff_title_id'],
                        'department_name' => $staffInfo['department_name'],
                    ];
            }
        }
        if(isset($item['staff_id_approve_level3'])){
            $staffInfo = $this->staffs->getDetailStaffApproveInfo($item['staff_id_approve_level3']);
            if(isset($staffInfo)){
                $arrayData[] =
                [
                    'staff_id' => $staffInfo['staff_id'],
                    'full_name' => $staffInfo['full_name'],
                    'staff_avatar' => $staffInfo['staff_avatar'],
                    'staff_title' => $staffInfo['staff_title'],
                    'staff_title_id' => $staffInfo['staff_title_id'],
                    'department_name' => $staffInfo['department_name'],
                ];
            }
        }
       
        return $arrayData;
        // $data = array(
        //     [
        //         'staff_id' => 139,
        //         'full_name' => 'Vũ Ngô',
        //         'staff_avatar' => 'https://epoint-bucket.s3.ap-southeast-1.amazonaws.com/0f73a056d6c12b508a05eea29735e8a5/2022/05/18/qayd74165284687218052022_avatar.jpg',
        //         'staff_title' => 'Trưởng Phòng',
        //         'staff_title_id' => 1,
        //     ],
        //     [
        //         'staff_id' => 144,
        //         'full_name' => 'Nguyễn Phương Bình',
        //         'staff_avatar' => 'https://epoint-bucket.s3.ap-southeast-1.amazonaws.com/0f73a056d6c12b508a05eea29735e8a5/2022/06/10/vjgLo1165482900510062022_avatar.jpg',
        //         'staff_title' => 'P Giám Đốc',
        //         'staff_title_id' => 2,
        //     ],
        //     [
        //         'staff_id' => 94,
        //         'full_name' => 'Dương Thanh Tâm',
        //         'staff_avatar' => 'https://epoint-bucket.s3.ap-southeast-1.amazonaws.com/0f73a056d6c12b508a05eea29735e8a5/2022/05/10/knEIc3165215248710052022_avatar.png',
        //         'staff_title' => 'Giám Đốc',
        //         'staff_title_id' => 3,
        //     ]

        // );
        // return $data;
    }


    /**
     * Chi tiết
     *
     * @param $input
     * @return mixed|void
     * @throws StaffRepoException
     */
    public function getDetail($id)
    {
        try {

            return $this->staffs->getDetail($id);
        } catch (\Exception $exception) {

            throw new StaffRepoException(StaffRepoException::GET_LIST_FAILED, $exception->getMessage());
        }
    }
    public function getDetailStaffApproveInfo($staffId){
        return $this->staffs->getDetailStaffApproveInfo($staffId);
    }
    public function getDetailApproveLevel1($departmentId){
        return $this->staffs->getDetailApproveLevel1($departmentId);
    }
    
}
