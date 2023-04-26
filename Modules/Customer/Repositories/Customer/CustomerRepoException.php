<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-01-04
 * Time: 10:57 AM
 * @author SonDepTrai
 */

namespace Modules\Customer\Repositories\Customer;


use MyCore\Repository\RepositoryExceptionAbstract;

class CustomerRepoException extends RepositoryExceptionAbstract
{
    const GET_DATA_FAILED = -1;
    const GET_CUSTOMER_DETAIL_FAILED = 0;
    const GET_CUSTOMER_UPDATE_FAILED = 1;
    const GET_CUSTOMER_FAILED = 2;
    const GET_CUSTOMER_GROUP_FAILED = 3;
    const STORE_CUSTOMER_FAILED = 4;
    const GET_HISTORY_ORDER_FAILED = 5;
    

    public function __construct(int $code = 0, string $message = "")
    {
        parent::__construct($message ? : $this->transMessage($code), $code);
    }

    protected function transMessage($code)
    {
        switch ($code)
        {
            case self::GET_CUSTOMER_DETAIL_FAILED :
                return __('Lấy thông tin khách hàng thất bại.');

            case self::GET_CUSTOMER_UPDATE_FAILED :
                return __('Cập nhật thông tin khách hàng thất bại.');

            case self::GET_CUSTOMER_FAILED :
                return __('Lấy danh sách khách hàng thất bại.');

            case self::GET_CUSTOMER_GROUP_FAILED :
                return __('Lấy danh sách khách hàng thất bại.');

            case self::STORE_CUSTOMER_FAILED :
                return __('Thêm khách hàng thất bại.');

            case self::GET_HISTORY_ORDER_FAILED :
                return __('Lấy lịch sử mua hàng thất bại.');
            case self::GET_DATA_FAILED :
                return __('Lấy dữ liệu thất bại.');
            default:
                return null;
        }
    }
}