<?php

namespace Modules\CustomerLead\Repositories\CustomerLead;

interface CustomerLeadRepoInterface
{
    /**
     * Lấy ds nguồn + loại khách hàng
     *
     * @return mixed
     */
    public function getOption();

    /**
     * Lấy ds pipeline
     *
     * @param $input
     * @return mixed
     */
    public function getPipe($input);

    /**
     * Lấy ds hành trình
     *
     * @param $input
     * @return mixed
     */
    public function getDataJourney($input);

    /**
     * Lấy ds tỉnh thành
     *
     * @return mixed
     */
    public function getDataProvince();

    /**
     * Lấy ds quận huyện
     *
     * @param $input
     * @return mixed
     */
    public function getDataDistrict($input);

    /**
     * Lấy ds người được phân bổ
     *
     * @return mixed
     */
    public function getDataAllocator();

    /**
     * tạo KHTN
     *
     * @param $params
     * @return mixed
     */
    public function createdCustomerLead($params);

    /**
     * thêm thông tin người liên hệ KHTN business
     * @param $params
     * @return mixed
     */
    public function addContact($params);

    /**
     * thêm tag
     * @param $params
     * @return mixed
     */
    public function addTag($params);

    /**
     * Lấy ds phường xã
     *
     * @param $input
     * @return mixed
     */
    public function getDataWard($input);

    /**
     * danh sách chức vụ
     * @param $input
     * @return mixed
     */
    public function getPosition($input);

    /**
     * Danh sách lính vực kinh doanh
     * @return mixed
     */
    public function getListBusinessAreas();

    /**
     * thêm lĩnh vực kinh doanh
     * @param $input
     * @return mixed
     */
    public function addBusinessAreas($input);

    /**
     * Lấy ds tên deal
     *
     * @return mixed
     */
    public function getDealName();

    /**
     * Lấy ds chi nhánh
     *
     * @return mixed
     */
    public function getBranch();

    /**
     * Lấy ds KH
     *
     * @return mixed
     */
    public function getCustomer();

    /**
     * Lấy ds nguồn đơn hàng
     *
     * @return mixed
     */
    public function getListOrderSource();

    /**
     * Lấy ds KHTN
     *
     * @param $input
     * @return mixed
     */
    public function getDataLead($input);

    /**
     * Lấy chi tiet KHTN
     * @param $input
     * @return mixed
     */
    public function getDetail($input);

    /**
     * chi tiết KHTN - thông tin deal
     * @param $input
     * @return mixed
     */
    public function detailLeadInfoDeal($input);

    /**
     * danh sach liên hệ
     * @param $input
     * @return mixed
     */
    public function getContactList($input);

    /**
     * danh sách message
     * @param $input
     * @return mixed
     */
    public function getListMessageLead($input);

    /**
     * tạo comment lead
     * @param $input
     * @return mixed
     */
    public function createMessageLead($input);

    /**
     * xóa comment lead
     * @param $input
     * @return mixed
     */
    public function deleteMessageLead($input);

    /**
     * chi tiet KHTN-lịch sử chăm sóc lead
     * @param $input
     * @return mixed
     */
    public function getCareLead($input);


    /**
     * danh sách trạng thái công việc
     * @param array $input
     * @return mixed
     */
    public function getStatusWork($input);

    /**
     * danh sách doanh nghiệp
     * @param $input
     * @return mixed
     */
    public function getListBusiness($input);

    /**
     * danh sách loại công việc
     * @param $input
     * @return mixed
     */
    public function getTypeWork($input);

    /**
     * lưu công việc chăm sóc khách hàng
     * @param $input
     * @return mixed
     */
    public function saveWork($input);

    /**
     * chinh sửa lead
     * @param $input
     * @return mixed
     */
    public function actionUpdate($input);

    /**
     * @param $input
     * @return mixed
     */
    public function actionDelete($input);


    /**
     * Lấy ds nhãn
     *
     * @return mixed
     */
    public function getTag();

    /**
     * Phân bổ hoặc thu hồi lead
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



