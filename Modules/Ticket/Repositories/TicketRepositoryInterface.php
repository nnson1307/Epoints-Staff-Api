<?php


namespace Modules\Ticket\Repositories;


interface TicketRepositoryInterface
{
//    Lấy tổng số ticket
    public function getTotalTicket();

//    Danh sách ticket , chưa phân công và đã phân công
    public function getMyTicket($data);

//    Danh sách ticket user có quyền xem
    public function getListTicket($data);

//    Thông tin ticket chưa hoàn thành
    public function getTicketNotCompleted();

//    Chi tiết ticket
    public function getDetail($data);

//    Cập nhật ticket
    public function editTicket($data);

//    Tạo phiếu yêu cầu
    public function addRequestForm($data);

//    Chỉnh sửa phiếu yêu cầu
    public function editRequestForm($data);

//    Xoá phiếu yêu cầu
    public function deleteRequestForm($data);

//    Vật tư
    public function infoMaterials($data);

//    Chi tiết vật tư
    public function infoMaterialsDetail($data);

//    Thông tin đánh giá
    public function ratingDetail($data);

//    Danh sách lịch sử
    public function getHistory($data);

//    Lấy danh sách ảnh
    public function getImage($data);

//    Thêm hình ảnh
    public function addImage($data);

//    Thông tin biên bản nghiệm thu
    public function acceptanceRecord($data);

//    Tạo biên bản nghiệm thu
    public function acceptanceRecordCreate($data);

//    Chỉnh sửa biên bản nghiệm thu
    public function acceptanceRecordEdit($data);

//    Tìm kiếm vậy tư
    public function searchMaterials($data);

//    Danh sách nhóm yêu cầu
    public function issueGroup($param);

//    Danh sách yêu cầu
    public function issue($param);

//    Danh sách trạng thái
    public function listStatus($ticketId = null);

//    Danh sách queue có quyền xem
    public function listQueue();

//    Upload file
    public function uploadFile($input);

//    Lấy danh sách nhân viên theo queue
    public function getListStaffByQueue($input);

    /**
     * Lấy ds công việc của ticket
     *
     * @param $input
     * @return mixed
     */
    public function getListTaskOfTicket($input);

    /**
     * Thêm vị trí trong ticket
     *
     * @param $input
     * @return mixed
     */
    public function addTikcetLocation($input);

    /**
     * Lấy danh sách vị trí trong ticket
     *
     * @param $input
     * @return mixed
     */
    public function listLocation($input);

    /**
     * xóa vị trí trong ticket
     *
     * @param $input
     * @return mixed
     */
    public function removeLocation($input);

     /**
     * tạo ticket
     *
     * @param $input
     * @return mixed
     */
    public function addTicket($data);

    /**
     * Lấy danh sách nhân viên chủ trì, xử lý
     *
     * @param $input
     * @return mixed
     */
    public function loadStaffByQueue($data);

    /**
     * Lấy danh sách queue
     *
     * @param $input
     * @return mixed
     */
    public function loadQueue();

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