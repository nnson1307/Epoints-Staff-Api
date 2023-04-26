<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 26/05/2021
 * Time: 14:14
 */

namespace Modules\Report\Repositories\ReportRevenueOrder;


interface ReportRevenueOrderRepoInterface
{
    /**
     * Lấy tổng doanh thu bán hàng
     *
     * @param $input
     */
    public function totalRevenue($input);

    /**
     * Lấy danh sách chi nhánh
     *
     * @return mixed
     */
    public function getBranch();

    /**
     * Chi tiết doanh thu bán hàng
     *
     * @param $input
     * @return mixed
     */
    public function detailRevenue($input);
}