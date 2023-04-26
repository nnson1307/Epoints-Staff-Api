<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 27/05/2021
 * Time: 10:50
 */

namespace Modules\Report\Repositories\StaffCommission;


interface StaffCommissionRepoInterface
{
    /**
     * Tổng hoa hồng nhân viên
     *
     * @param $input
     * @return mixed
     */
    public function totalCommission($input);

    /**
     * Chi tiết hoa hồng nhân viên
     *
     * @param $input
     * @return mixed
     */
    public function detailCommission($input);
}