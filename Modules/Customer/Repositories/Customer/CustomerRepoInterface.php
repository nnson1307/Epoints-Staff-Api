<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-01-04
 * Time: 10:56 AM
 * @author SonDepTrai
 */

namespace Modules\Customer\Repositories\Customer;


interface CustomerRepoInterface
{
    /**
     * Lấy ds khách hàng
     *
     * @param $input
     * @return mixed
     */
    public function getCustomer($input);

    /**
     * Chi tiết khách hàng
     *
     * @param $input
     * @return mixed
     */
    public function getDetail($input);

    /**
     * Lấy option nhóm khách hàng
     *
     * @return mixed
     */
    public function getCustomerGroup();

    /**
     * Thêm khách hàng
     *
     * @param $input
     * @return mixed
     */
    public function store($input);

    /**
     * Lấy lịch sử mua hàng
     *
     * @param $input
     * @return mixed
     */
    public function historyOrder($input);

    /**
     * Cập nhật khách hàng
     *
     * @param $input
     * @return mixed
     */
    public function update($input);

    /**
     * Lấy danh sách bình luận
     * @param $data
     * @return mixed
     */
    public function listComment($data);

    /**
     * Tạo comment
     * @param $data
     * @return mixed
     */
    public function createdComment($data);
}