<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 25/05/2021
 * Time: 16:55
 */

namespace Modules\Warranty\Repositories\WarrantyCard;


use MyCore\Repository\RepositoryExceptionAbstract;

class WarrantyCardRepoException extends RepositoryExceptionAbstract
{
    const GET_LIST_PACKAGE_FAILED = 0;
    const GET_LIST_WARRANTY_CARD_FAILED = 1;
    const GET_DETAIL_WARRANTY_CARD_FAILED = 2;
    const UPDATE_WARRANTY_CARD_FAILED = 3;
    const QUICK_UPDATE_CARD_FAILED = 4;
    const GET_LIST_STATUS_FAILED = 5;

    public function __construct(int $code = 0, string $message = "")
    {
        parent::__construct($message ? : $this->transMessage($code), $code);
    }

    protected function transMessage($code)
    {
        switch ($code)
        {
            case self::GET_LIST_PACKAGE_FAILED :
                return __('Lấy danh sách gói bảo hành thất bại.');

            case self::GET_LIST_WARRANTY_CARD_FAILED :
                return __('Lấy danh sách phiếu bảo hành thất bại.');

            case self::GET_DETAIL_WARRANTY_CARD_FAILED :
                return __('Lấy chi tiết phiếu bảo hành thất bại.');

            case self::UPDATE_WARRANTY_CARD_FAILED :
                return __('Cập nhật phiếu bảo hành thất bại.');

            case self::QUICK_UPDATE_CARD_FAILED :
                return __('Cập nhật nhanh phiếu bảo hành thất bại.');

            case self::GET_LIST_STATUS_FAILED :
                return __('Lấy danh sách trạng thái phiếu bảo hành thất bại.');

            default:
                return null;
        }
    }
}