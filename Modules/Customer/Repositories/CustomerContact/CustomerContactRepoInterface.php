<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 24/05/2021
 * Time: 14:27
 */

namespace Modules\Customer\Repositories\CustomerContact;


interface CustomerContactRepoInterface
{
    /**
     * Thêm địa chỉ giao hàng
     *
     * @param $input
     * @return mixed
     */
    public function store($input);

    /**
     * Xoá địa chỉ giao hàng
     *
     * @param $input
     * @return mixed
     */
    public function remove($input);

    /**
     * Cập nhật địa chỉ giao hàng mặc định
     *
     * @param $input
     * @return mixed
     */
    public function setDefault($input);

    /**
     * Chỉnh sửa địa chỉ giao hàng
     *
     * @param $input
     * @return mixed
     */
    public function update($input);
}