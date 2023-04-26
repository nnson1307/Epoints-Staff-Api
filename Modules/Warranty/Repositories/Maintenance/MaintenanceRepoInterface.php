<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 27/09/2022
 * Time: 14:22
 */

namespace Modules\Warranty\Repositories\Maintenance;


interface MaintenanceRepoInterface
{
    /**
     * DS phiếu bảo trì
     *
     * @param $input
     * @return mixed
     */
    public function getMaintenance($input);

    /**
     * Lấy DS phiếu bảo hành của khách hàng
     *
     * @param $input
     * @return mixed
     */
    public function getWarrantyCardCustomer($input);

    /**
     * Lấy chi phí phát sinh
     *
     * @return mixed
     */
    public function getCostType();

    /**
     * Thêm phiếu bảo trì
     *
     * @param $input
     * @return mixed
     */
    public function store($input);

    /**
     * Chi tiết phiếu bảo trì
     *
     * @param $input
     * @return mixed
     */
    public function show($input);

    /**
     * Cập nhật phiếu bảo trì
     *
     * @param $input
     * @return mixed
     */
    public function update($input);

    /**
     * Thanh toán phiếu bảo trì
     *
     * @param $input
     * @return mixed
     */
    public function receiptMaintenance($input);

    /**
     * Lấy ds trạng thái phiếu bảo trì
     *
     * @return mixed
     */
    public function getListStatus();
}