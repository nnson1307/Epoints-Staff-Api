<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 27/09/2022
 * Time: 14:23
 */

namespace Modules\Warranty\Repositories\Maintenance;

use MyCore\Repository\RepositoryExceptionAbstract;

class MaintenanceRepoException extends RepositoryExceptionAbstract
{
    const GET_LIST_MAINTENANCE_FAILED = 0;
    const GET_WARRANTY_CARD_CUSTOMER_FAILED = 1;
    const GET_COST_TYPE_FAILED = 2;
    const CREATE_MAINTENANCE_FAILED = 3;
    const GET_DETAIL_FAILED = 4;
    const UPDATE_MAINTENANCE_FAILED = 5;
    const RECEIPT_MAINTENANCE_FAILED = 6;
    const GET_LIST_STATUS_FAILED = 7;

    public function __construct(int $code = 0, string $message = "")
    {
        parent::__construct($message ? : $this->transMessage($code), $code);
    }

    protected function transMessage($code)
    {
        switch ($code)
        {
            case self::GET_LIST_MAINTENANCE_FAILED :
                return __('Lấy danh sách phiếu bảo trì thất bại.');

            case self::GET_WARRANTY_CARD_CUSTOMER_FAILED :
                return __('Lấy danh sách phiếu bảo hành của khách hàng thất bại.');

            case self::GET_COST_TYPE_FAILED :
                return __('Lấy chi phí phát sinh thất bại.');

            case self::CREATE_MAINTENANCE_FAILED :
                return __('Thêm phiếu bảo trì thất bại.');

            case self::GET_DETAIL_FAILED :
                return __('Lấy chi tiết phiếu bảo trì thất bại.');

            case self::UPDATE_MAINTENANCE_FAILED :
                return __('Cập nhật phiếu bảo trì thất bại.');

            case self::RECEIPT_MAINTENANCE_FAILED :
                return __('Thanh toán phiếu bảo trì thất bại.');

            case self::GET_LIST_STATUS_FAILED :
                return __('Lấy danh sách trạng thái phiếu bảo trì thất bại.');

            default:
                return null;
        }
    }
}