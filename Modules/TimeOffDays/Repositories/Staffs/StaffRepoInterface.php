<?php
/**
 * Created by PhpStorm
 * User: PhongDT
 */

namespace Modules\TimeOffDays\Repositories\Staffs;


interface StaffRepoInterface
{
    /**
     * Danh sách người duyệt
     *
     * @param $input
     * @return mixed
     */
    public function getLists($input);

    public function getListStaffApprove($timeOffTypeId);

    /**
     * Chi tiết
     *
     * @param $input
     * @return mixed
     */
    public function getDetail($id);

    public function getDetailStaffApproveInfo($staffId);

    public function getDetailApproveLevel1($departmentId);
}