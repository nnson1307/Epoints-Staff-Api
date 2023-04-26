<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 6/12/2020
 * Time: 2:50 PM
 */

namespace Modules\ProjectManagement\Repositories\Project;


use MyCore\Repository\RepositoryExceptionAbstract;

class ProjectRepoException extends RepositoryExceptionAbstract
{
    const GET_STATUS = 0;
    const GET_MANAGE = 1;
    const GET_DEPARTMENT = 2;
    const GET_CUSTOMER = 3;
    const GET_TAG = 4;
    const ADD_PROJECT = 5;
    const GET_LIST_PROJECT = 6;
    const GET_PROJECT_INFO = 7;
    const EDIT_PROJECT = 8;
    const DELETE_PROJECT = 9;
    const IS_DELETE = 10;
    const GET_LIST_DOCUMENTS = 11;
    const GET_BRANCH = 12;
    const GET_STAFF = 13;
    const ADD_PROJECT_MEMBER = 14;
    const EDIT_PROJECT_MEMBER = 15;
    const DELETE_MEMBER = 16;
    const GET_STATICTICAL = 17;
    const WORK_LIST = 18;
    const UPDATE_STATUS_PROJECT = 19;
    const ADD_ISSUE = 20;
    const LIST_ISSUE = 21;
    const GET_INFO_REPORT = 22;
    const GET_LIST_MEMBER_PROJECT = 23;
    const GET_ROLE = 24;
    const GET_DATA_PHASE = 25;
    const ADD_COMMENT_PROJECT = 26;
    const HISTORY_COMMENT_PROJECT = 27;
    const GET_LIST_EXPENDITURE = 28;
    const DELETE_DOCUMENT = 29;
    const ADD_DOCUMENT = 30;
    const GET_MANAGE_PROJECT_CREATED_REMINDER_FAILED = 31;
    const GET_MANAGE_LIST_REMIND_FAILED = 32;
    const GET_LIST_CONTRACT = 33;

    public function __construct(int $code = 0, string $message = "")
    {
        parent::__construct($message ? : $this->transMessage($code), $code);
    }

    protected function transMessage($code)
    {
        switch ($code)
        {
            case self::GET_STATUS :
                return __('Lấy danh sách trạng thái dự án thất bại.');
            case self::GET_MANAGE :
                return __('Lấy danh sách người quản trị dự án thất bại.');
            case self::GET_DEPARTMENT :
                return __('Lấy danh sách phòng ban thất bại.');
            case self::GET_CUSTOMER :
                return __('Lấy danh sách khách hàng thất bại.');
            case self::GET_TAG :
                return __('Lấy danh sách tag thất bại.');
            case self::ADD_PROJECT :
                return __('Tạo dự án thất bại.');
            case self::GET_LIST_PROJECT :
                return __('Lấy danh sách dự án thất bại.');
            case self::GET_PROJECT_INFO :
                return __('Lấy thông tin dự án thất bại.');
            case self::EDIT_PROJECT :
                return __('Chỉnh sửa thông tin dự án thất bại.');
            case self::DELETE_PROJECT :
                return __('Xóa dự án thất bại.');
            case self::IS_DELETE :
                return __('Chuyển trạng thái xóa thất bại.');
            case self::GET_LIST_DOCUMENTS :
                return __('Lấy danh sách tài liệu thất bại.');
            case self::GET_BRANCH :
                return __('Lấy danh sách chi nhánh thất bại.');
            case self::GET_STAFF :
                return __('Lấy danh sách nhân viên thất bại.');
            case self::ADD_PROJECT_MEMBER :
                return __('Thêm thành viên thất bại.');
            case self::EDIT_PROJECT_MEMBER :
                return __('Chỉnh sửa thành viên thất bại.');
            case self::DELETE_MEMBER :
                return __('Xóa thành viên thất bại.');
            case self::GET_STATICTICAL :
                return __('Lấy dữ liệu thống kê thất bại.');
            case self::WORK_LIST :
                return __('Lấy danh sách công việc thất bại.');
            case self::UPDATE_STATUS_PROJECT :
                return __('Cập nhật trạng thái dự án thất bại.');
            case self::ADD_ISSUE :
                return __('Thêm vấn đề thất bại.');
            case self::LIST_ISSUE :
                return __('Lấy danh sách vấn đề thất bại.');
            case self::GET_INFO_REPORT :
                return __('Lấy thông tin báo cáo dự án thất bại.');
            case self::GET_LIST_MEMBER_PROJECT :
                return __('Lấy danh sách thành viên dự án thất bại.');
            case self::GET_ROLE :
                return __('Lấy danh sách vai trò thất bại.');
            case self::GET_DATA_PHASE :
                return __('Lấy danh sách chi tiết giai đoạn thất bại.');
            case self::ADD_COMMENT_PROJECT :
                return __('Thêm bình luận thất bại.');
            case self::HISTORY_COMMENT_PROJECT :
                return __('Lấy lịch sử luận thất bại.');
            case self::GET_LIST_EXPENDITURE :
                return __('Lấy danh sách phiếu thu-chi thất bại.');
            case self::DELETE_DOCUMENT :
                return __('Xóa tài liệu thất bại.');
            case self::ADD_DOCUMENT :
                return __('Thêm tài liệu thất bại.');
            case self::GET_MANAGE_PROJECT_CREATED_REMINDER_FAILED :
                return __('Thêm nhắc nhở dự án thất bại.');
            case self::GET_MANAGE_LIST_REMIND_FAILED :
                return __('Lấy danh sách nhắc nhở dự án thất bại.');
            case self::GET_LIST_CONTRACT :
                return __('Lấy danh sách hợp đồng thất bại.');
            default:
                return null;
        }
    }
}