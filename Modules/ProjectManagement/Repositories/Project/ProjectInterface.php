<?php


namespace Modules\ProjectManagement\Repositories\Project;


interface ProjectInterface
{
    /**
     * ds trang thai
     * @return mixed
     */
    public function getStatus();

    /**
     * ds nguoi quan tri
     * @return mixed
     */
    public function getManage();

    /**
     * vai trò nhân viên
     * @return mixed
     */
    public function getRole();

    /**
     * ds phong ban
     * @return mixed
     */
    public function getDepartment();

    /**
     * ds khach hang
     * @param $input
     * @return mixed
     */
    public function getCustomer($input);

    /**
     * ds tag
     * @return mixed
     */
    public function getTag();

    /**
     * danh sach laoi cong viec
     * @return mixed
     */
    public function getTypeWork();

    /**
     * tao du an moi
     * @param $input
     * @return mixed
     */
    public function createdProject($input);

    /**
     * danh sach du an
     * @param $input
     * @return mixed
     */
    public function listProject($input);

    /**
     * thong tin du an
     * @param $input
     * @return mixed
     */
    public function projectInfo($input);

    /**
     * chinh sua du an
     * @param $input
     * @return mixed
     */
    public function editProject($input);

    /**
     * xoa du an
     * @param $input
     * @return mixed
     */
    public function deleteProject($input);

    /**
     * trang thai xoa du an
     * @param $input
     * @return mixed
     */
    public function isDelete($input);

    /**
     * danh sach hop dong
     * @return mixed
     */
    public function getListContract();

    /**
     * lay danh sach tai lieu
     * @param $input
     * @return mixed
     */
    public function getListDocument($input);

    /**
     * thêm tài liệu
     * @param $input
     * @return mixed
     */
    public function addDocument($input);

    /**
     * xóa tài liệu
     * @param $input
     * @return mixed
     */
    public function deleteDocument($input);

    /**
     * danh sach lich su haot dong
     * @param $input
     * @return mixed
     */
    public function getActivities($input);

    /**
     * danh sach thanh vien du an
     * @param $input
     * @return mixed
     */
    public function getListMem($input);

    /**
     * danh sach chi nhanh
     * @return mixed
     */
    public function getBranch();

    /**
     * danh sach nhan vien
     * @return mixed
     */
    public function getStaff($filter);

    /**
     * them thanh vien cho du an
     * @param $input
     * @return mixed
     */
    public function addMem($input);

    /**
     * chinh sua thanh vien
     * @param $input
     * @return mixed
     */
    public function editMem($input);

    /**
     * xoa thanh vien
     * @param $input
     * @return mixed
     */
    public function deleteMem($input);

    /**
     * số lượng tổng thành viên-công việc-đang thực hiện-chưa thực hiện-hoàn thành-đã đóng-quán hạn
     * @param $input
     * @return mixed
     */
    public function getDataStatictical($input);

    /**
     * danh sach cong viec du an
     * @param $input
     * @return mixed
     */
    public function getWorkList($input);

    /**
     * update trạng thái dự án
     * @param $input
     * @return mixed
     */
    public function updateStatusProject($input);

    /**
     * thêm vấn đề dự án
     * @param $input
     * @return mixed
     */
    public function addIssue($input);

    /**
     * danh sách vấn đề dự án
     * @param $input
     * @return mixed
     */
    public function listIssue($input);

    /**
     * thông tin report dự án
     * @param $input
     * @return mixed
     */
    public function getInfoReport($input);

    /**
     * thông tin chi tiết giai đoạn dự án
     * @param $input
     * @return mixed
     */
    public function getDataPhase($input);

    /**
     * thêm comment project
     * @param $input
     * @return mixed
     */
    public function addComment($input);

    /**
     * danh sách lịch sử bình luận
     * @param $input
     * @return mixed
     */
    public function getHistoryComment($input);

    /**
     * danh sách phiếu thu-chi
     * @param $input
     * @return mixed
     */
    public function getListExpenditure($input);
    /**
     * Tạo nhắc nhở
     * @param $data
     * @return mixed
     */
    public function createReminder($data);
    /**
     * Danh sách nhắc nhở
     * @param $data
     * @return mixed
     */
    public function listRemind($data);



}