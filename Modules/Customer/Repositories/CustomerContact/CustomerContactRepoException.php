<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 24/05/2021
 * Time: 14:27
 */

namespace Modules\Customer\Repositories\CustomerContact;

use MyCore\Repository\RepositoryExceptionAbstract;

class CustomerContactRepoException extends RepositoryExceptionAbstract
{
    const STORE_USER_CONTACT_FAILED = 0;
    const REMOVE_USER_CONTACT_FAILED = 1;
    const UPDATE_USER_CONTACT_FAILED = 2;

    public function __construct(int $code = 0, string $message = "")
    {
        parent::__construct($message ? : $this->transMessage($code), $code);
    }

    protected function transMessage($code)
    {
        switch ($code)
        {
            case self::STORE_USER_CONTACT_FAILED :
                return __('Thêm địa chỉ giao hàng thất bại.');

            case self::REMOVE_USER_CONTACT_FAILED :
                return __('Xóa địa chỉ giao hàng thất bại.');

            case self::UPDATE_USER_CONTACT_FAILED :
                return __('Chỉnh sửa địa chỉ giao hàng thất bại.');

            default:
                return null;
        }
    }
}