<?php

namespace Modules\CustomerLead\Repositories\CustomerDeals;

interface CustomerDealsRepoInterface
{


    /**
     * them deal
     * @param $params
     * @return mixed
     */
    public function createdDeal($params);

    /**
     * lay danh sach deal
     * @param $input
     * @return mixed
     */
    public function getDataDeal($input);

    /**
     * chi tiet deal
     * @param $input
     * @return mixed
     */
    public function getDetail($input);

    /**
     * lịch sử đơn hàng của deal
     * @param $input
     * @return mixed
     */
    public function getOrderHistory($input);

    /**
     * chi tiết Deals-chăm sóc KH
     * @param $input
     * @return mixed
     */
    public function getCareDeal($input);

    /**
     * tạo comment deal
     * @param $input
     * @return mixed
     */
    public function createMessageDeal($input);

    /**
     * danh sách comment deal
     * @param $input
     * @return mixed
     */
    public function getListMessageDeal($input);

    /**
     * xóa comment deal
     * @param $input
     * @return mixed
     */
    public function deleteMessageDeal($input);
    /**
     * update deal
     * @param $input
     * @return mixed
     */
    public function actionUpdate($input);

    /**
     * delete deal
     * @param $input
     * @return mixed
     */
    public function actionDelete($input);

    /**
     * Phân bổ hoặc thu hồi deal
     *
     * @param $input
     * @return mixed
     */
    public function assignRevoke($input);

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
