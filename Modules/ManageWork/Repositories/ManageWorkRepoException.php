<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-01-09
 * Time: 11:23 AM
 * @author SonDepTrai
 */

namespace Modules\ManageWork\Repositories;


use MyCore\Repository\RepositoryExceptionAbstract;

class ManageWorkRepoException extends RepositoryExceptionAbstract
{
    const GET_MANAGE_WORK_TOTAL_FAILED = 0;
    const GET_MANAGE_WORK_OVERVIEW_FAILED = 1;
    const GET_MANAGE_WORK_BRANCH_FAILED = 2;
    const GET_MANAGE_WORK_DEPARTMENT_FAILED = 3;
    const GET_MANAGE_WORK_CREATED_REMINDER_FAILED = 4;
    const GET_MANAGE_LIST_WORK_FAILED = 5;
    const GET_MANAGE_WORK_DETAIL_FAILED = 6;
    const GET_MANAGE_WORK_APPROVE_FAILED = 7;
    const GET_MANAGE_LIST_COMMENT_FAILED = 8;
    const GET_MANAGE_CREATED_COMMENT_FAILED = 9;
    const GET_MANAGE_LIST_REMIND_FAILED = 10;
    const GET_MANAGE_LIST_FILE_FAILED = 11;
    const GET_MANAGE_LIST_HISTORY_FAILED = 12;
    const GET_MANAGE_ADD_PROJECT_FAILED = 13;
    const GET_MANAGE_ADD_TYPE_WORK_FAILED = 14;
    const GET_MANAGE_LIST_TAGS_FAILED = 15;
    const GET_MANAGE_LIST_STAFF_FAILED = 16;
    const FILE_NOT_TYPE = 17;
    const MAX_FILE_SIZE = 18;
    const GET_UPLOAD_FILE_FAILED = 19;
    const GET_MANAGE_ADD_WORK_FAILED = 20;
    const GET_MANAGE_EDIT_WORK_FAILED = 21;
    const GET_LIST_DOCUMENT_FAILED = 22;
    const GET_UPLOAD_FILE_DOCUMENT_FAILED = 23;
    const GET_UPDATE_WORK_TAG_FAILED = 24;
    const GET_LIST_CHILD_WORK_FAILED = 25;
    const GET_EDIT_REPEAT_WORK_FAILED = 26;
    const GET_LIST_PROJECT_FAILED = 27;
    const GET_DELETE_REMIND_FAILED = 28;
    const GET_LIST_STATUS_FAILED = 29;
    const GET_DELETE_COMMENT_FAILED = 30;
    const GET_MY_WORK_FAILED = 31;
    const GET_MY_ASSIGN_WORK_FAILED = 32;
    const GET_MY_REMIND_WORK_FAILED = 33;
    const GET_UPDATE_STAFF_SUPPORT_FAILED = 34;
    const GET_DELETE_WORK_FAILED = 35;
    const GET_LIST_TYPE_WORK_FAILED = 36;
    const GET_QUICK_UPDATE_WORK_FAILED = 37;
    const GET_LIST_CUSTOMER_FAILED = 38;
    const GET_ADD_TAG_FAILED = 39;
    const GET_USING_STAFF_SUPPORT_FAILED = 40;
    const DELETE_IMAGE_FAILED = 41;
    const GET_REPEAT_TIME_FAILED = 42;
    const GET_LIST_WORK_APPROVE_FAILED = 43;
    const CHECK_CHILD_TASK = 44;
    const PARENT_TASK_CANT_UPDATE = 45;
    const GET_DEPARTMENT_FAILED = 46;
    const CREATE_LOCATION_FAILED = 47;
    const GET_LOCATION_FAILED = 48;
    const REMOVE_LOCATION_FAILED = 49;
    const INDISPENSABLE_APPROVER = 50;
    const WORK_NEEDED_ACTIVE = 51;
    const OUTSIDE_THE_PROJECT_TIME = 52;
    const WRONG_CHRONOLOGICAL_ORDER = 53;

    public function __construct(int $code = 0, string $message = "")
    {
        parent::__construct($message ?: $this->transMessage($code), $code);
    }

    protected function transMessage($code)
    {
        switch ($code) {
            case self::GET_MANAGE_WORK_TOTAL_FAILED :
                return __('Lấy tổng công việc thất bại.');
            case self::GET_MANAGE_WORK_OVERVIEW_FAILED :
                return __('Lấy tổng quan công việc thất bại.');
            case self::GET_MANAGE_WORK_BRANCH_FAILED :
                return __('Lấy chi nhánh thất bại.');
            case self::GET_MANAGE_WORK_DEPARTMENT_FAILED :
                return __('Lấy phòng ban thất bại.');
            case self::GET_MANAGE_WORK_CREATED_REMINDER_FAILED :
                return __('Tạo nhắc nhở thất bại.');
            case self::GET_MANAGE_LIST_WORK_FAILED :
                return __('Lấy danh sách công việc thất bại.');
            case self::GET_MANAGE_WORK_DETAIL_FAILED :
                return __('Lấy chi tiết công việc thất bại.');
            case self::GET_MANAGE_WORK_APPROVE_FAILED :
                return __('Duyệt công việc thất bại.');
            case self::GET_MANAGE_LIST_COMMENT_FAILED :
                return __('Lấy danh sách bình luận thất bại.');
            case self::GET_MANAGE_CREATED_COMMENT_FAILED :
                return __('Tạo bình luận bình luận thất bại.');
            case self::GET_MANAGE_LIST_REMIND_FAILED :
                return __('Lấy danh sách nhắc nhở thất bại.');
            case self::GET_MANAGE_LIST_FILE_FAILED :
                return __('Lấy danh sách file thất bại.');
            case self::GET_MANAGE_LIST_HISTORY_FAILED :
                return __('Lấy danh sách lịch sử thất bại.');
            case self::GET_MANAGE_ADD_PROJECT_FAILED :
                return __('Tạo dự án thất bại.');
            case self::GET_MANAGE_ADD_TYPE_WORK_FAILED :
                return __('Tạo loại công việc thất bại.');
            case self::GET_MANAGE_LIST_TAGS_FAILED :
                return __('Lấy danh sách tags thất bại.');
            case self::GET_MANAGE_LIST_STAFF_FAILED :
                return __('Lấy danh sách nhân viên thất bại.');
            case self::FILE_NOT_TYPE :
                return __('Ảnh/file không được trống.');
            case self::MAX_FILE_SIZE :
                return __('FIle có kích thước quá lớn, vui lòng upload file có kích thước tối đa 20MB.');
            case self::GET_UPLOAD_FILE_FAILED :
                return __('Upload file thất bại.');
            case self::GET_MANAGE_ADD_WORK_FAILED :
                return __('Tạo công việc thất bại.');
            case self::GET_MANAGE_EDIT_WORK_FAILED :
                return __('Chỉnh sửa công việc thất bại.');
            case self::GET_LIST_DOCUMENT_FAILED :
                return __('Lấy danh sách hồ sơ thất bại.');
            case self::GET_UPLOAD_FILE_DOCUMENT_FAILED :
                return __('Cập nhật file trong hồ sơ thất bại.');
            case self::GET_UPDATE_WORK_TAG_FAILED :
                return __('Cập nhật tag cho công việc thất bại.');
            case self::GET_LIST_CHILD_WORK_FAILED :
                return __('Lấy danh sách công việc con thất bại.');
            case self::GET_EDIT_REPEAT_WORK_FAILED :
                return __('Chỉnh sửa lặp lại công việc thất bại.');
            case self::GET_LIST_PROJECT_FAILED :
                return __('Lấy danh sách dự án thất bại.');
            case self::GET_DELETE_REMIND_FAILED :
                return __('Xoá nhắc nhở thất bại.');
            case self::GET_LIST_STATUS_FAILED :
                return __('Lấy danh sách trạng thái thất bại.');
            case self::GET_DELETE_COMMENT_FAILED :
                return __('Xoá bình luận thất bại.');
            case self::GET_MY_WORK_FAILED :
                return __('Lấy việc của tôi thất bại.');
            case self::GET_MY_ASSIGN_WORK_FAILED :
                return __('Lấy việc tôi giao thất bại.');
            case self::GET_MY_REMIND_WORK_FAILED :
                return __('Lấy nhắc nhở của tôi thất bại.');
            case self::GET_UPDATE_STAFF_SUPPORT_FAILED :
                return __('Cập nhật nhân viên liên quan thất bại.');
            case self::GET_DELETE_WORK_FAILED :
                return __('Xoá công việc thất bại.');
            case self::GET_LIST_TYPE_WORK_FAILED :
                return __('Lấy danh sách loại công việc thất bại.');
            case self::GET_QUICK_UPDATE_WORK_FAILED :
                return __('Cập nhật công việc thất bại.');
            case self::GET_LIST_CUSTOMER_FAILED :
                return __('Lấy danh sách khách hàng thất bại.');
            case self::GET_ADD_TAG_FAILED :
                return __('Tạo tag thất bại.');
            case self::GET_USING_STAFF_SUPPORT_FAILED :
                return __('Nhân viên liên quan trùng với nhân viên xử lý hoặc nhân viên duyệt.');
            case self::DELETE_IMAGE_FAILED :
                return __('Xoá file thất bại.');
            case self::GET_REPEAT_TIME_FAILED :
                return __('Vui lòng chọn giờ lặp.');
            case self::GET_LIST_WORK_APPROVE_FAILED :
                return __('Lấy danh sách công việc cần duyệt thất bại.');
            case self::CHECK_CHILD_TASK :
                return __('Công việc hiện tại đã có công việc con không thể thêm công việc cha');
            case self::PARENT_TASK_CANT_UPDATE :
                return __('Công việc cha không thể cập nhật tiến độ');
            case self::GET_DEPARTMENT_FAILED :
                return __('Lấy danh sách phòng ban thất bại');
            case self::CREATE_LOCATION_FAILED :
                return __('Thêm vị trí làm việc thất bại');
            case self::GET_LOCATION_FAILED :
                return __('Lấy vị trí làm việc thất bại');
            case self::REMOVE_LOCATION_FAILED :
                return __('Xoá vị trí làm việc thất bại');
            case self::INDISPENSABLE_APPROVER :
                return __('Không thể thiếu người phê duyệt');
            case self::WORK_NEEDED_ACTIVE :
                return __('Công việc phải đang hoạt động (id=1)');
            case self::OUTSIDE_THE_PROJECT_TIME :
                return __('Thời gian đã chọn nằm ngoài thời gian dự án');
            case self::WRONG_CHRONOLOGICAL_ORDER :
                return __('Thời gian kết thức phải sau thời gian bắt đầu');

            default:
                return null;
        }
    }
}