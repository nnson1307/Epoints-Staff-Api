<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 6/12/2020
 * Time: 2:50 PM
 */

namespace Modules\ChatHub\Repositories\CustomerLead;


use MyCore\Repository\RepositoryExceptionAbstract;

class CustomerLeadRepoException extends RepositoryExceptionAbstract
{
    const GET_TYPE_AND_SOURCE_CUSTOMER = 0;
    const GET_PIPELINE = 1;
    const GET_JOURNEY = 2;
    const GET_PROVINCE = 3;
    const GET_DISTRICT = 4;
    const GET_ALLOCATOR = 5;
    const ADD_LEAD = 6;
    const GET_DEAL_NAME = 7;
    const GET_BRANCH = 8;
    const GET_CUSTOMER = 9;
    const GET_ORDER_SOURCE = 10;
    const ADD_DEAL = 11;
    const GET_WARD = 12;
    const GET_LIST_CUSTOMER_LEAD = 13;
    const GET_DETAIL = 14;
    const UPDATE_CUSTOMER_LEAD = 15;
    const DELETE_CUSTOMER_LEAD = 16;
    const GET_TAG_FAILED = 17;
    const ASSIGN_REVOKE_LEAD_FAILED = 18;
    const VALID_FAIL = 19;

    public function __construct(int $code = 0, string $message = "")
    {
        parent::__construct($message ? : $this->transMessage($code), $code);
    }

    protected function transMessage($code)
    {
        switch ($code)
        {
            case self::GET_TYPE_AND_SOURCE_CUSTOMER :
                return __('Lấy loại và nguồn khách hàng thất bại.');
            case self::GET_PIPELINE :
                return __('Lấy pipeline thất bại.');
            case self::GET_JOURNEY :
                return __('Lấy hành trình thất bại.');
            case self::GET_PROVINCE :
                return __('Lấy tỉnh thành thất bại.');
            case self::GET_DISTRICT :
                return __('Lấy quận huyện thất bại.');
            case self::GET_ALLOCATOR :
                return __('Lấy người được phân bổ thất bại.');
            case self::ADD_LEAD :
                return __('Thêm khách hàng tiềm năng thất bại.');
            case self::GET_DEAL_NAME :
                return __('Lấy tên deals thất bại.');
            case self::GET_BRANCH :
                return __('Lấy tên chi nhánh thất bại.');
            case self::GET_CUSTOMER :
                return __('Lấy danh sách khách hàng thất bại.');
            case self::GET_ORDER_SOURCE :
                return __('Lấy danh sách nguồn đơn hàng thất bại.');
            case self::ADD_DEAL :
                return __('Tạo cơ hội bán hàng thất bại.');
            case self::GET_WARD :
                return __('Lấy danh sách phường xã thất bại.');
            case self::GET_LIST_CUSTOMER_LEAD :
                return __('Lấy danh sách khách hàng tiềm năng thất bại.');
            case self::GET_DETAIL :
                return __('Lấy chi tiết khách hàng tiềm năng thất bại.');
            case self::UPDATE_CUSTOMER_LEAD :
                return __('Chỉnh sửa khách hàng tiềm năng thất bại.');
            case self::DELETE_CUSTOMER_LEAD :
                return __('Xóa khách hàng tiềm năng thất bại.');
            case self::GET_TAG_FAILED :
                return __('Lấy danh sách nhãn thất bại.');
            case self::ASSIGN_REVOKE_LEAD_FAILED :
                return __('Phân bổ hoặc thu hồi thất bại.');
            case self::VALID_FAIL :
                return __('Thao tác thất bại.');
            default:
                return null;
        }
    }
}