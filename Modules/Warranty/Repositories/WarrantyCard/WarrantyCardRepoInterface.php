<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 27/09/2022
 * Time: 09:31
 */

namespace Modules\Warranty\Repositories\WarrantyCard;


interface WarrantyCardRepoInterface
{
    /**
     * Lấy ds gói bảo hành
     *
     * @return mixed
     */
    public function getPackage();

    /**
     * Lấy ds thẻ bảo hành
     *
     * @param $input
     * @return mixed
     */
    public function getWarrantyCard($input);

    /**
     * Chi tiết phiếu bảo hành
     *
     * @param $input
     * @return mixed
     */
    public function show($input);

    /**
     * Chỉnh sửa phiếu bảo hành
     *
     * @param $input
     * @return mixed
     */
    public function update($input);

    /**
     * Cập nhật nhanh phiếu bảo hành
     *
     * @param $input
     * @return mixed
     */
    public function quickUpdate($input);

    /**
     * Lấy ds trạng thái phiếu bảo hành
     *
     * @return mixed
     */
    public function getListStatus();
}