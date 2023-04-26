<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-01-09
 * Time: 11:23 AM
 * @author SonDepTrai
 */

namespace Modules\Ticket\Repositories;


use MyCore\Repository\RepositoryExceptionAbstract;

class TicketRepoException extends RepositoryExceptionAbstract
{
    const GET_TICKET_LIST_FAILED = 0;
    const GET_TICKET_NOT_COMPLETED_FAILED = 1;
    const GET_TICKET_DETAIL_FAILED = 2;
    const GET_TICKET_EDIT_FAILED = 3;
    const GET_REQUEST_FORM_ADD_FAILED = 4;
    const GET_MATERIALS_FAILED = 5;
    const GET_MATERIALS_DETAIL_FAILED = 6;
    const GET_REQUEST_FORM_EDIT_FAILED = 7;
    const GET_RATING_DETAIL_FAILED = 8;
    const GET_HISTORY_FAILED = 9;
    const GET_IMAGE_FAILED = 10;
    const GET_ACCEPTANCE_FAILED = 11;
    const GET_ACCEPTANCE_CREATE_FAILED = 12;
    const GET_ACCEPTANCE_EDIT_FAILED = 13;
    const GET_SEARCH_MATERIALS_FAILED = 14;
    const GET_ISSUEGROUP_FAILED = 15;
    const GET_ISSUE_FAILED = 16;
    const GET_TICKET_STATUS_FAILED = 17;
    const GET_MATERIAL_LIST_FAILED = 18;
    const GET_TICKET_QUEUE_FAILED = 19;
    const GET_LIST_MATERIALS_FAILED = 20;
    const GET_UPLOAD_FILE_FAILED = 21;
    const MAX_FILE_SIZE = 22;
    const FILE_NOT_TYPE = 23;
    const CREATED_ACCEPTANCE_FAILED = 24;
    const GET_ADD_IMAGE_FAILED = 25;
    const GET_REQUEST_FORM_DELETE_FAILED = 26;
    const GET_LIST_STAFF_QUEUE_FAILED = 27;
    const GET_LIST_TASK_OF_TICKET_FAILED = 28;
    const CREATE_LOCATION_FAILED = 29;
    const REMOVE_LOCATION_FAILED = 30;
    const GET_LOCATION_FAILED = 31;

    public function __construct(int $code = 0, string $message = "")
    {
        parent::__construct($message ?: $this->transMessage($code), $code);
    }

    protected function transMessage($code)
    {
        switch ($code) {
            case self::GET_TICKET_LIST_FAILED :
                return __('Lấy danh sách ticket thất bại.');
            case self::GET_TICKET_NOT_COMPLETED_FAILED :
                return __('Lấy ticket chưa hoàn thành thất bại.');
            case self::GET_TICKET_DETAIL_FAILED :
                return __('Lấy chi tiết ticket thất bại.');
            case self::GET_TICKET_EDIT_FAILED :
                return __('Chỉnh sửa ticket thất bại.');
            case self::GET_REQUEST_FORM_ADD_FAILED :
                return __('Tạo phiếu yêu cầu thất bại.');
            case self::GET_MATERIALS_FAILED :
                return __('Lấy thông tin vật tư thất bại.');
            case self::GET_MATERIALS_DETAIL_FAILED :
                return __('Lấy thông tin chi tiết vật tư thất bại.');
            case self::GET_REQUEST_FORM_EDIT_FAILED :
                return __('Chỉnh sửa phiếu yêu cầu thất bại.');
            case self::GET_RATING_DETAIL_FAILED :
                return __('Lấy chi tiết lịch sử thất bại.');
            case self::GET_HISTORY_FAILED :
                return __('Lấy danh sách lịch sử thất bại.');
            case self::GET_IMAGE_FAILED :
                return __('Lấy danh sách hình ảnh thất bại.');
            case self::GET_ACCEPTANCE_FAILED :
                return __('Lấy thông tin biên bản nghiệm thu thất bại.');
            case self::GET_ACCEPTANCE_CREATE_FAILED :
                return __('Tạo biên bản nghiệm thu thất bại.');
            case self::GET_ACCEPTANCE_EDIT_FAILED :
                return __('Chỉnh sửa biên bản nghiệm thu thất bại.');
            case self::GET_SEARCH_MATERIALS_FAILED :
                return __('Tìm kiếm vậy tư thất bại.');
            case self::GET_ISSUEGROUP_FAILED :
                return __('Lấy danh sách nhóm yêu cầu thất bại.');
            case self::GET_ISSUE_FAILED :
                return __('Lấy danh sách yêu cầu thất bại.');
            case self::GET_TICKET_STATUS_FAILED :
                return __('Lấy danh sách trạng thái ticket thất bại.');
            case self::GET_MATERIAL_LIST_FAILED :
                return __('Danh sách vật tư đề xuất là bắt buộc.');
            case self::GET_TICKET_QUEUE_FAILED :
                return __('Lấy danh sách queue thất bại.');
            case self::GET_LIST_MATERIALS_FAILED :
                return __('Lấy danh sách vật tư thất bại.');
            case self::GET_UPLOAD_FILE_FAILED :
                return __('Upload file thất bại.');
            case self::MAX_FILE_SIZE :
                return __('FIle có kích thước quá lớn, vui lòng upload file có kích thước tối đa 20MB.');
            case self::FILE_NOT_TYPE :
                return __('Ảnh/file không được trống.');
            case self::CREATED_ACCEPTANCE_FAILED :
                return __('Biên bản nghiệm thu đã được tạo.');
            case self::GET_ADD_IMAGE_FAILED :
                return __('Thêm hình ảnh thất bại.');
            case self::GET_REQUEST_FORM_DELETE_FAILED :
                return __('Xoá phiếu yêu cầu thất bại.');
            case self::GET_LIST_STAFF_QUEUE_FAILED :
                return __('Lấy danh sách nhân viên theo queue thất bại.');
            case self::GET_LIST_TASK_OF_TICKET_FAILED :
                return __('Lấy danh sách công việc của ticket thất bại.');
            case self::CREATE_LOCATION_FAILED :
                return __('Thêm vị trí thất bại');
            case self::REMOVE_LOCATION_FAILED :
                return __('Xóa vị trí thất bại');
            case self::GET_LOCATION_FAILED :
                return __('Lấy vị trí thất bại');
            default:
                return null; 
        }
    }
}