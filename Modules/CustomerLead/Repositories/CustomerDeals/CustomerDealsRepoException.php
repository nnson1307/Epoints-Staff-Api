<?php

/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 6/12/2020
 * Time: 2:50 PM
 */

namespace Modules\CustomerLead\Repositories\CustomerDeals;


use MyCore\Repository\RepositoryExceptionAbstract;

class CustomerDealsRepoException extends RepositoryExceptionAbstract
{
    const GET_DATA_FAILED = -1;
    const ADD_DEAL = 0;
    const GET_LIST_DEAL = 1;
    const GET_DETAIL = 2;
    const UPDATE_DEALS = 3;
    const DELETE_DEALS = 4;
    const ASSIGN_REVOKE_LEAD_FAILED = 5;
    const ORDER_HISTORY = 6;
    const CREATED_COMMENT = 7;
    const LIST_COMMENT_DEAL = 8;
    const CARE_DEALS = 9;


    public function __construct(int $code = 0, string $message = "")
    {
        parent::__construct($message ?: $this->transMessage($code), $code);
    }

    protected function transMessage($code)
    {
        switch ($code) {

            case self::ADD_DEAL:
                return __('Tạo cơ hội bán hàng thất bại.');
            case self::GET_LIST_DEAL:
                return __('Lấy danh sách cơ hội bán hàng thất bại.');
            case self::GET_DETAIL:
                return __('Lấy chi tiết cơ hội bán hàng thất bại.');
            case self::UPDATE_DEALS:
                return __('Chỉnh sửa chi tiết cơ hội bán hàng thất bại.');
            case self::DELETE_DEALS:
                return __('Xóa cơ hội bán hàng thất bại.');
            case self::ASSIGN_REVOKE_LEAD_FAILED:
                return __('Phân bổ hoặc thu hồi thất bại.');
            case self::ORDER_HISTORY:
                return __('Lấy lịch sử đơn hàng của Deal thất bại.');
            case self::CREATED_COMMENT:
                return __('Thêm Comment  Deal thất bại.');
            case self::LIST_COMMENT_DEAL:
                return __('Lấy danh sách Comment  Deal thất bại.');
            case self::GET_DATA_FAILED:
                return __('Lấy dữ liệu thất bại.');
            case self::CARE_DEALS:
                return __('Lấy lịch danh sách chăm sóc khách hàng thất bại.');
            default:
                return null;
        }
    }
}
