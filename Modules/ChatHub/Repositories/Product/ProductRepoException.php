<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 6/12/2020
 * Time: 2:50 PM
 */

namespace Modules\ChatHub\Repositories\Product;


use MyCore\Repository\RepositoryExceptionAbstract;

class ProductRepoException extends RepositoryExceptionAbstract
{
    const GET_PRODUCT_TYPE_LIST_FAILED = 0;

    public function __construct(int $code = 0, string $message = "")
    {
        parent::__construct($message ? : $this->transMessage($code), $code);
    }

    protected function transMessage($code)
    {
        switch ($code)
        {
            case self::GET_PRODUCT_TYPE_LIST_FAILED :
                return __('Lấy danh sách sản phẩm theo type thất bại.');
            default:
                return null;
        }
    }
}