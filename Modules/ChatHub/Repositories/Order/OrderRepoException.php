<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 6/12/2020
 * Time: 2:50 PM
 */

namespace Modules\ChatHub\Repositories\Order;


use MyCore\Repository\RepositoryExceptionAbstract;

class OrderRepoException extends RepositoryExceptionAbstract
{
    const GET_ORDER_LIST_FAILED = 0;

    public function __construct(int $code = 0, string $message = "")
    {
        parent::__construct($message ? : $this->transMessage($code), $code);
    }

    protected function transMessage($code)
    {
        switch ($code)
        {
            case self::GET_ORDER_LIST_FAILED :
                return __('Lấy danh sách đơn hàng thất bại.');
            default:
                return null;
        }
    }
}