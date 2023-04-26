<?php


namespace Modules\ManageWork\Repositories;


interface ManageWorkRepositoryInterface
{
    /**
     * Tổng ticket trang home
     * @param $data
     * @return mixed
     */
    public function getTotalWork($data);

    public function getTotalWorkSupport($data);

    /**
     * Tổng ticket , danh sách nhân viên chưa có công việc trong ngày
     * @param $data
     * @return mixed
     */
    public function jobOverview($data);

    /**
     * Danh sách chi nhánh
     * @return mixed
     */
    public function listBranch($data);

    /**
     * Lấy danh sách phòng ban
     * @return mixed
     */
    public function listDepartment($data);

    /**
     * Tạo nhắc nhở theo công việc hoặc theo nhân viên
     * @param $data
     * @return mixed
     */
    public function createReminder($data);

    /**
     * Danh sách công việc
     * @param $data
     * @return mixed
     */
    public function listWork($data);

    /**
     * Chi tiết công việc
     * @return mixed
     */
    public function workDetail($data);

    /**
     * Duyệt công việc
     * @param $data
     * @return mixed
     */
    public function workApprove($data);

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

    /**
     * Danh sách nhắc nhở
     * @param $data
     * @return mixed
     */
    public function listRemind($data);

    /**
     * Danh sách file
     * @param $data
     * @return mixed
     */
    public function listFile($data);

    /**
     * Danh sách lịch sử
     * @param $data
     * @return mixed
     */
    public function listHistory($data);

    /**
     * Tạo công việc
     * @param $data
     * @return mixed
     */
    public function addWork($data);

    /**
     * Chỉnh sửa công việc
     * @param $data
     * @return mixed
     */
    public function editWork($data);

    /**
     * Thêm dự án
     * @param $data
     * @return mixed
     */
    public function addProject($data);

    /**
     * Thêm loại công việc
     * @param $data
     * @return mixed
     */
    public function addTypeWork($data);

    /**
     * Danh sách tags
     * @param $data
     * @return mixed
     */
    public function listTags($data);

    /**
     * Danh sách nhân viên
     * @param $data
     * @return mixed
     */
    public function listStaff($data);

    /**
     * Upload file
     * @param $data
     * @return mixed
     */
    public function uploadFile($input);

    /**
     * Danh sách hồ sơ
     * @param $data
     * @return mixed
     */
    public function listDocument($data);

    /**
     * Cập nhật file hồ sơ
     * @param $data
     * @return mixed
     */
    public function uploadFileDocument($data);

    /**
     * Cập nhật tag cho công việc
     * @param $data
     * @return mixed
     */
    public function updateWorkTag($data);

    /**
     * Danh sách tác vụ con
     * @param $data
     * @return mixed
     */
    public function listChildWork($data);

    /**
     * Chỉnh sửa lặp lại
     * @param $data
     * @return mixed
     */
    public function editRepeatWork($data);

    /**
     * Danh sách dự án
     * @return mixed
     */
    public function listProject($data);

    /**
     * Công việc của tôi
     * @param $data
     * @return mixed
     */
    public function myWorkSearch($data);

    /**
     * Công việc của tôi quá hạn
     * @param $data
     * @return mixed
     */
    public function myWorkSearchOverdue($data);

    /**
     * Công việc của tôi tab của tôi
     * @return mixed
     */
    public function myWork($data);

    /**
     * Việc của tôi tab tôi giao
     * @return mixed
     */
    public function myAssignWork();

    /**
     * Danh sách nhắc nhở của tôi
     * @return mixed
     */
    public function myRemindWork($data);

    /**
     * Xoá nhắc nhở
     * @param $data
     * @return mixed
     */
    public function deleteRemind($data);

    /**
     * danh sách trạng thái
     * @return mixed
     */
    public function listStatus();

    /**
     * Xoá bình luận
     * @param $data
     * @return mixed
     */
    public function deleteComment($data);

    /**
     * Cập nhật nhân viên liên quan
     * @param $data
     * @return mixed
     */
    public function updateStaffSupport($data);

    /**
     * Xoá công việc
     * @param $data
     * @return mixed
     */
    public function deleteWork($data);

    /**
     * Danh sách loại công việc
     * @return mixed
     */
    public function listTypeWork();

    /**
     * Cập nhật nhanh công việc
     * @param $data
     * @return mixed
     */
    public function quickUpdateWork($data);

    /**
     * Danh sách khách hàng
     * @return mixed
     */
    public function listCustomer($data);

    /**
     * Tạo tag mới
     * @param $data
     * @return mixed
     */
    public function addTag($data);

    /**
     * Xoá hình ảnh
     * @param $data
     * @return mixed
     */
    public function deleteDocumentFile($data);

    /**
     * Danh sách công việc cần duyệt
     * @param $data
     * @return mixed
     */
    public function getListWorkApprove($data);

    /**
     * Gửi noti công việc
     * @param $data
     * @return mixed
     */
    public function staffNotification($data);

    /**
     * Loại khách hàng
     * @param $data
     * @return mixed
     */
    public function typeCustomer();

    /**
     * Lấy option phòng ban
     *
     * @return mixed
     */
    public function getDepartment();

    /**
     * Thêm vị trí công việc
     *
     * @param $input
     * @return mixed
     */
    public function createLocation($input);

    /**
     * Lấy vị trí làm việc của công việc
     *
     * @param $input
     * @return mixed
     */
    public function listLocation($input);

    /**
     * Xoá toạ độ
     *
     * @param $input
     * @return mixed
     */
    public function removeLocation($input);
    /**
     * Danh sách công việc
     * @param $data
     * @return mixed
     */
    public function listWorkParent($data);

    /**
     * Tổng quan công việc (v2)
     *
     * @param $input
     * @return mixed
     */
    public function jobOverViewV2($input);

    /**
     * Danh sách trạng thái (màn hình bộ lọc)
     *
     * @return mixed
     */
    public function listStatusV2();
}
